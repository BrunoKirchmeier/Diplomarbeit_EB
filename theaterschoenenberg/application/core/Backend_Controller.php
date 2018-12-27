<?php
!defined('BASEPATH') and exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * CI_Mpdelerweitern
 * ----------------------------------------------------------
 *
 * @author		  Bruno Kirhmeier
 * @copyright	  Frei einsetzbar mit (c)-Hinweisen
 *			        you are free to use this code as long as you credit the
 *              author - and provide him with your improvements
 * @version		  1.1 | 2018-12-01
 */
class Backend_Controller extends MX_Controller {

  	/**
  	* Javascript und Css Files Controller spezifisch laden
  	*
  	*  @var array mSeitenJs   Array mit den zu ladenden JavaScript Files
  	* */
  	public $mSeitenJs      	= array();


    /** Member Variablen */
  	public $identity_column = '';
    public $data                = array();
    public $o_aktiv_user_daten;

  	/**
  	* Konstruktor
  	*
  	* */
  	public function __construct()
  	{
  		parent::__construct();

      /** Helpers laden */
      $this->load->helper(array('form',
                                'url'));

      /** Libaries laden */
      $this->load->library(array('ion_auth',
  															 'form_validation'));

      /** Error Anzeige von Form Validation formatieren */
      $this->
      form_validation->
      set_error_delimiters('<p style="color:red;">', '</p>');

      /** Models laden */
      $this->load->model('MY_Model');

      /** Authenticication */
      $this->
  		identity_column = $this->
  											config->
  											item('identity', 'ion_auth');

      /** letzte Seite vor aufruf Login speichern
       *
       * Immer wenn Funktion Login aufgerufen wird, die letzte Seite NICHT überschreiben
       * Diese Seite wird benötigt, wenn Login oder logout aktiv ist
       *
       * n=1 for controller, n=2 for method, etc
       * $this->uri->segment(n) */
      if($this->uri->segment(2) <> 'Backend_Auth') {

         /** Nur Url string, aber relativ zu base_url() */
         $this->
         session->
         set_userdata('s_letzte_url_relativ',
                      $this->
                      uri->
                      uri_string());
      }

       /** Prüfen ob ein User eingeloggt ist
        * Wenn ja, dann Namen in Aside schreiben
        *  */
       if ($this->ion_auth->logged_in()) {

          /** Userdaten des angemeldeten Users */
          $this->
          o_aktiv_user_daten = $this->
                               MY_Model->get('users',
                                             $this->
                                             ion_auth->
                                             get_user_id());
          $this->
          data['s_active_user_name']  = $this->
                                        o_aktiv_user_daten[0]->
                                        username;
          $this->
          data['i_active_user_id']    = $this->
                                        ion_auth->
                                        get_user_id();

       } else {

          $this->
          data['s_active_user_name'] = '';

          $this->
          data['i_active_user_id'] = -1;

       }

       /** Aktuellen Controller Mitgeben, damit im Menü dieser farblich markiert
        * werden kann
        *  */
        $this->
        data['s_current_controller'] = $this->
                                       uri->
                                       segment(2);

         /** Prüfeung on ein Benutzer eingeloggt ist, ansonsten wird gar keine Seite angezeigt */
         if($this->
            uri->
            segment(2) != 'Backend_Auth')
         {
           $this->
        		checkPermissions(array('admin'),
        	                   FALSE,
        	                   site_url() . "Admin/Backend_Auth",
                             'Keine Berechtigung für diese Seite');
         }

         /**
         * Messages für eigens erstellte Funktionen für Form Validations
         *  */

         // Funktion:    search_key
         $this->
         form_validation->
         set_message('search_key',
                     'Der Fremdschlüssel des Feldes {field} wurde nicht gefunden');

         // Funktion:    valid_date
         $this->
         form_validation->
         set_message('valid_date',
                     'Das Feld {field} ist kein valides Datum');

         // Funktion:    search_key
         $this->
         form_validation->
         set_message('valid_time', 'Im Feld {field} ist keine gültige Zeitangabe');
  	}



    /**
     * name:        renderView
     *
     * Mit dieser Funktion wird die gesamte View zusammengebaut, bestehend aus header
     * Body und Footer.
     * Im Body kpnnen mittels  der variablen $a_eingebettete_views HTML Elemente
     * eingebaut werden. Dafür muss aber jeweils ein Div mit einer bestimmten id
     * angesprochen werden können.
     *
     * @param string     $s_parent_view             Body von Ausgabe view
     *                                              eingebettete view swerden in diese
     *                                              view eingefügt
     * @param array     $a_view_daten               Daten als asoziatives Array zum einfügen
     *                                              in der View
     * @param array     $a_eingebettete_views       Assoziatives Array mit view Namen, welche
     *                                              im $s_parent_view eingebaut werden.
     *                                              Key =   zu ladende View
     *                                              Value = Div id, in welcher die views
     *                                                      jewils eingefügt wird
     * @param string   $s_header                    Header file
     * @param string   $s_footer                    Footer file
     *
     * @author      Bruno Kirchmeier
     * @date        20181201
     *
     *  */
    public function renderView($s_parent_view,
                               $a_view_daten      = [],
                               $a_embeddet_views  = [],
                               $s_header          = '_headerAdmin',
                               $s_footer          = '_footerAdmin')
   	{
      /** Ausgabe der Daten an Browser erst am Ende Ausführen */
      ob_start();

      /* View Daten */
      $this->data['output'] = $a_view_daten;

      /** Header File laden */
      $this->load->view($s_header,
                        $this->data);

      /** Wenn eingebette Daten vorhanden sind, dann die view nicht direkt ausgeben,
       * sondern als String zurückgeben und mittels Key den string nach der gesuchten
       * id durchsuchen */
      if( count($a_embeddet_views) > 0) {

        /** Variablen initisieren
         * Body view laden und als string Ausgeben
         * In diesem String werden die eingebetteten Views eingefügt */
        $s_body_view = $this->load->view($s_parent_view,
                                        '',
                                        true);

        /** einzelne eingebettete views abbarbeiten */
        foreach ($a_embeddet_views as $key => $value) {

          /** Variablen initisieren
           * Embedded view laden und als string Ausgeben
           * dieser String wird in den body string eingefügt
          */
          $s_embeddet_view = $this->load->view($key,
                                               '',
                                               true);

          /** im string nach der id suchen. Mittels preg_match_all alle resultate
           * in ein Multidimensionales Array speichern
           * https://www.php-einfach.de/php-tutorial/regulaere-ausdruecke/
           * https://www.bglerchenfeld.at/php
          */
          $s_regex = "/<\s*div.*id\s*=\s*[\'|\"]{1}". strtolower($value) . "[\'|\"]{1}.*>/";
          preg_match_all($s_regex,
                         strtolower($s_body_view),
                         $a_string,
                         PREG_OFFSET_CAPTURE);

          /** Prüfen ob Array vorhanden ist */
          if (isset($a_string[0][0][1]) )
          {
            /** Variablen initisieren
             * Damit die embedded View ebenfalls als string in die body view eingefügt
             * werden kann, muss die Body view aufgesplitet werden, damit nichts
             * überschrieben wird
             * Teil 1:  Alles vor der Embeddet view
             * Teil 2:  Die Embeddet view
             * Teil 3:  Allesnachde Embeddet view */

            /** Teilstring 1 */
            $i_teilstring_1_start_pos = 0;
            $i_teilstring_1_end_pos   = strlen($a_string[0][0][0]) +
                                               $a_string[0][0][1];

            $s_teilstring_1           = substr($s_body_view,
                                               0,
                                               $i_teilstring_1_end_pos);

            /** Teilstring 2 */
            $s_teilstring_2 = $s_embeddet_view;

            /** Teilstring 3 */
            $i_teilstring_3_start_pos = $i_teilstring_1_end_pos + 1;
            $i_teilstring_3_end_pos   = strlen($s_body_view);

            $s_teilstring_3           = substr($s_body_view,
                                               $i_teilstring_3_start_pos,
                                               $i_teilstring_3_end_pos);

            /** Zusammengesetzt View mit Embeddet View */
            $s_body_view = $s_teilstring_1 .
                           $s_teilstring_2 .
                           $s_teilstring_3;

           echo $s_body_view;

          /** Die gesuchte Stelle mit der vorgegeben ID zum einfügen der embeddet
           * View wurde nicht gefunden. Die Embedet view wurde nicht eingefügt */
          } else {}

        /** Ende des Loops */
        }

     /** Keine eingebetteten Daten vorhanden */
     } else {

       /**  Body Files laden */
       $this->load->view($s_parent_view);
     }

     /** Footer File laden */
     $this->load->view($s_footer);

     /** Effektive Ausgabe aller View daten */
     ob_end_flush();
   	}



     /**
      * name:        checkPermissions
      *
      * Mit dieser Funktion werden einzelne Teilfunktionen der Ion-Auth Bibliothek
      * zusammengefasst
      * 1. Prüfen ob jemand eingeloggt ist, ansonsten keine BErechtigung
      * 2. Prüfen ob eingeloggte PErson mindestens in der Standartgruppe ist
      *    oder die Funktion in_group der ion-auth Lib ausführen
      * 3. Entweder bei false Seite mit redirect oder nur Funktions return Value
      *
      * @param array     $a_needed_group_names    Argument für ion-auth Funktion
      *                                           in_group. Siehe dort für details
      * @param boolean   $b_check_all             Dito $a_needed_group_names
      * @param string    $s_redirect_url          Sofern ein Reload der Seite aufgrund
      *                                           des Parameters: $b_redirect_activ
      *                                           erfolgt, wird hier die zu ladende
      *                                           URL vorgegeben.
      *                                           Default bei leerem String:
      *                                           zuletz Besuchte Seite ausserhalb
      *                                           Auth Controller
      * @param string    $s_session_message       Message, welche in die Session:
      *                                           flashdata('message') geschrieben wird
      *                                           => Jedoch nur wenn return == false ist
      * @param boolean   $b_redirect_activ        Redirect aktivieren/deaktivieren
      *                                           Wenn deaktiviert, so gint Funktion
      *                                           TRUE / FALSE zurück
      *
      * @author      Bruno Kirchmeier
      * @date        20181201
      *
      *  */
    public function checkPermissions($a_needed_group_names  = array(),
                                     $b_check_all           = FALSE,
                                     $s_redirect_url        = '',
                                     $s_session_message     = '',
                                     $b_redirect_activ      = true)
   	{
      /** Hillfsvariable */
      $b_return           = false;
      $b_login            = false;
      $b_in_group_default = false;
      $b_in_group         = false;


      /** Redirect URL bestimmen, wenn keine Vorgabe durch User */
      if($s_redirect_url == '')
      {
        $s_redirect_url = site_url() . $this->
                                       session->
                                       set_userdata('s_letzte_url_relativ');
      }

      /** Prüfen ob ein User eingeloogt ist oder nicht */
      if($this->
         ion_auth->
         logged_in() )
      {
        $b_login = true;
      }

      /** Prüfen ob User mit mindestens der
       * Standart Gruppe eingeloggt ist */
      if($this->
         ion_auth->
         in_group($this->
  							  config->
  							  item('default_group', 'ion_auth'),
                  $this->
                  ion_auth->
                  get_user_id()) )
      {
        $b_in_group_default = true;
      }

      /** Wenn Keine Vorgaben von Gruppen, dann alles okay und True */
      if(empty($a_needed_group_names))
      {
        $b_in_group = true;

      /** Prüfen ob User in einer/allen gesuchten Gruppe ist */
      } else {

        if($this->
           ion_auth->
           in_group($a_needed_group_names,
                    $this->
                    ion_auth->
                    get_user_id(),
                    $b_check_all) )
        {
          $b_in_group = true;
        }
      }

      /** Endauswertung, ob Berechtigung erteilt wird */
      if($b_login &&
         $b_in_group_default &&
         $b_in_group )
      {
        $b_return = true;
      }

      /** Message für Session Variable, damit Fehlermeldung bei Redirect
       * nicht verlogren geht */
      if($s_session_message <> '' &&
         $b_return == false)
      {
        $this->
        session->
        set_flashdata('message',
                      $s_session_message);
      }

      /** Return oder redirect
       * REDIRECT */
      if($b_redirect_activ)
      {
        /** redirect nur, wenn return Value False ist*/
        if($b_return == false) {

          redirect($s_redirect_url);

        } else {

          return true;
        }

      /** RETURN */
      } else {

        return $b_return;
      }

    }



    /**
     * name:        login
     *
     * Login Funktion
     *
     * Der User welcher per Post übergeben wurde wird versucht einzuloggen
     * Mit dieser Funktion wird auf Ion Auth Grungfunktionen zurückgegriffen
     *
     * Es braucht eine minimale Gruppenberechtigung von admin, ansonsten eerfolgt ein
     * redirect über den Koonstruktor
     *
     * Wenn Login erfolgreich war, redirect auf letzte backend_aktuellesStueck
     *
     * @author      Bruno Kirchmeier
     * @date        20181201
     *
     *  */
  	public function login()
  	{
  		/** Loginversuch */
  		if ($this->
  				ion_auth->
  				login($this->
  							input->
  							post('benutzername'),
  							$this->
  							input->
  							post('password')))
  		{
  			/** Login war erfolgreich
  			 * Messsages in Session schreiben für Redirect */
   			$this->
   			renderView('Backend_auth_login',
   								 $this->
   								 data,
                   array(),
                   '_headerAdmin',
                   '_footerAdmin');


  		/** Login Fehlgeschlagen */
  		} else {

  			/** Messsages in Session schreiben für Redirect */
  			$this->
  			session->
  			set_flashdata('message',
  										$this->
  										ion_auth->
  										errors());

  			/** View Daten aufbereiten*/
  			$this->
  			data['s_benutzername'] 	= strtolower($this->
  																					 input->
  																					 post('benutzername'));
  			$this->
  			data['message'] = $this->
  												session->
  												flashdata('message');

  			/** View laden */
  			$this->
  			renderView('Backend_auth_login',
                   $this->
                   data,
                   array(),
                   '_headerAdmin',
                   '_footerAdmin');
  		}
  	}



    /**
  	 * User auslogen
  	 * automaitischer redirect zu home page backend
  	 */
  	public function logout()
  	{
  		// log the user out
  		$logout = $this->
  							ion_auth->
  							logout();

  		// redirect them to the login page
  		$this->
  		session->
  		set_flashdata('message', $this->
  														 ion_auth->
  														 messages());

  		redirect(site_url() . 'Admin');
  	}



  }

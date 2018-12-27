<?php defined('BASEPATH') OR exit('No direct script access allowed');

/** Authentifizierung für Front und BAckend mittels Ion Auth
 * Class Auth
 *
 */
class Auth extends MY_Controller
{

	/**
	* Javascript und Css Files Controller spezifisch laden
	*
	*  @var array mSeitenJs   Array mit den zu ladenden JavaScript Files
	* */
	public $mSeitenJs      	= array();

	public $identity_column = '';


	/**
	* Konstruktor
	*
	* */
	public function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form',
															'url'));

		$this->load->library(array('ion_auth',
															 'form_validation'));

		$this->
		identity_column = $this->
											config->
											item('identity', 'ion_auth');

// print_r($this->db->queries);
	}



	/**
	 * Mit Index wird die View Login aufgerufen
	 *
	 */
	public function index()
	{
		/** Validierungsfehler für view daten aufberetiten */
		$this->
		data['message'] = (validation_errors()
										? validation_errors()
										: ($this->
											 ion_auth->
											 errors()
											 ? $this->ion_auth->errors()
											 : $this->session->flashdata('message')));

		/** Login Page starten */
		$this->
		data['s_benutzername'] = '';

		$this->
		data['s_email'] = '';

		$this->renderView('auth_login');
	}


	/**
	 * Login Funktion
	 *
	 * Der User welcher per Post übergeben wurde wird versucht einzuloggen
	 * Mit dieser Funktion wird auf Ion Auth Grungfunktionen zurückgegriffen
	 *
	 * Wenn Login erfolgreich war, redirect auf letzte Seite ausserhalb des Auth Controllers
	 * auf welchen ohne Ajax zugeriffen wurde. Mittels Session flasdata kann die NAchricht
	 * für denredirect eonmalig gespeichert werden
 	 *
	 */
	public function login()
	{
		/** Loginversuch */
		if ($this->
				ion_auth->
				login($this->
							input->
							post('benutzername', true),
							$this->
							input->
							post('password', true)))
		{
			/** Login war erfolgreich
			 * Messsages in Session schreiben für Redirect */
			$this->
			session->
			set_flashdata('message',
										$this->
										ion_auth->
										messages());

			redirect(site_url(). $this->
								       		 session->
								       		 userdata('s_letzte_url_relativ'));


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
																					 post('benutzername', true));
			$this->
			data['message'] = (validation_errors()
											? validation_errors()
											: ($this->
												 ion_auth->
												 errors()
												 ? $this->ion_auth->errors()
												 : $this->session->flashdata('message')));

			/** View laden */
			$this->
			renderView('auth_login',
								 $this->
								 data);
		}

	}


	/**
	 * User auslogen
	 * automaitischer redirect zu login Seite
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

		redirect(site_url() . 'Auth');
	}


	/**
	 * Kunden können einen Account erstellen für den Ticketshop. Die Emailadresse muss
	 * mit einem Bestägitungsemail verifiziert werden
	 * Eingabeverifizierung mittels Codignitter formvalidation
	 *
	 * @param array   $a_group_id          	Wenn andere Gruppen als member vergeben werden sollen
	 *
	 * @author      	Bruno Kirchmeier
	 * @date        	20181201
	 *
	 */
	public function createKunde($a_group_id = array() )
	{
		/** Daten aus configdatei laden */
		$tables = $this->
							config->
							item('tables', 'ion_auth');

		$identity_column = $this->
											 identity_column;

		/** Formulardaten an View zurückgeben sofern es einen Fehler geben wird
		* Bei den nachfolgenden Validierungen */
		$this->
		data['s_benutzername'] = $this->
														 input->
														 post('benutzername', true);
		$this->
		data['s_nachname'] = $this->
											 	 input->
											 	 post('nachname', true);
	  $this->
		data['s_vorname'] = $this->
											  input->
											  post('vorname', true);
    $this->
 		data['s_email'] 	= strtolower($this->
																   input->
																   post('email', true));

	  /** Benutzereingaben Inputfelder Validierung
		 * Regeln setzen
		 *
		 * Benutzername - ist zugeleich der Datenabnk Schlüssels */
		$this->
		form_validation->
		set_rules('benutzername',
						  'benutzername',
						  'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
		/** Nachname */
		$this->
		form_validation->
		set_rules('nachname',
							'Nachname',
							'trim');
		/** Vorname */
		$this->
		form_validation->
		set_rules('vorname',
							'Vorname',
							'trim');
		/** Email - ist zugeleich der Datenabnk Schlüssels */
		$this->
		form_validation->
		set_rules('email',
							'Email',
							'trim|required|valid_email');
		/** Passwort */
		$this->
		form_validation->
		set_rules('password',
							'Passwort',
							'required|' .
							'min_length[' . $this->
														  config->
														  item('min_password_length', 'ion_auth') . ']|'.
							'max_length[' . $this->
															config->
															item('max_password_length', 'ion_auth') . ']');
		/** Passwort Bestätigung */
		$this->
		form_validation->
		set_rules('passwortConfirm',
							'Passwort Bestätigung',
							'required|matches[password]');


		/** Züsätzliche felddaten, welche nicht al seinzelparameter
		 * für Register Funktion benötigt werden*/
		$additional_data = array('first_name' => $this->
																						 input->
																						 post('vorname', true),
														 'last_name' 	=> $this->
																						 input->
																						 post('nachname', true)
														);

		/** Benutzereingaben Inputfelder Validierung und
		 * zugleich die Funktion ausführen zum erstellen des Users
		 *
		 * Validierung starten
		 */
		 if ($this->
 				form_validation->
 				run() === TRUE &&
 				$this->
 				ion_auth->
 				register($this->
		 						 data['s_benutzername'],
 								 $this->
 								 input->
 								 post('password', true),
 								 $this->
 						  	 data['s_email'],
 								 $additional_data,
								 $a_group_id) )
		{

			/** Aktion war erfolgreich
			 * Loginmaske aufrufen - Meldung:
			 * alles erfolgreich */
			$this->
			session->
			set_flashdata('message', $this->
															 ion_auth->
															 messages());

			/** Seite neu laden damit Formualr nicht ei nzweites MAl ausgeführt wird */
 			redirect(site_url() . 'Auth/createKunde');

		} else {

				/** Validierungsfehler für view daten aufberetiten */
				$this->
				data['message'] = (validation_errors()
												? validation_errors()
												: ($this->
													 ion_auth->
													 errors()
													 ? $this->ion_auth->errors()
													 : $this->session->flashdata('message')));

				/** Loginmaske aufrufen - Meldung:
				 * Validierungsfehler oder Fehler biem erstellen  des Datensates  */
				$this->
				renderView('auth_createKunde',
									 $this->
									 data);
		}

	}


	/** Funktion aus Ion Aut Controller
	 * Activate the user
	 *
	 * @param int         $id   The user ID
	 * @param string|bool $code The activation code
	 */
	public function activate($id, $code = FALSE)
	{
		if ($code !== FALSE)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			// redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect(site_url() . 'Auth');
		}
		else
		{
			// redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect(site_url() . 'Auth');
		}
	}



	/**
	 * Kunden können die Daten ihres Accountes updaten. Wenn die Email verändert wird
	 * so muss dies ebenfalls zuerst mit einem Bestägitungsemail verifiziert werden, ansonsten werden
	 * nur die anderen änderungen übernommen.
	 *
	 * Damit kein fremder User die Datenveerändern kann, wird geprüft ob der aktive User
	 * an diesem Gerät angemeldet ist.
	 *
	 * Eingabeverifizierung mittels Codignitter formvalidation
	 *
	 * @param integer   $i_id          	id de Kunden, welcher seine DAten verändern möchte
	 *
	 * @author      	Bruno Kirchmeier
	 * @date        	20181201
	 *
	 */
	public function editKunde($i_id = -1)
	{
		/** Prüfen ob User berechtigt ist, diese Funktion zu starten */

		$this->
		checkPermissions(array(),
										 false,
										 site_url() . 'Auth',
									 	 "Bitte zuerst einloggen");

		/** Hilfsvariablen */
		$s_update_daten = array();
		/** Gesamte Userdaten laden */
		$a_userdaten = $this->
									 MY_Model->
									 get('users',
											 $i_id);

		/** Daten aus configdatei laden */
		$tables = $this->
							config->
							item('tables', 'ion_auth');

		$identity_column = $this->
											 identity_column;

		 /** Formular Angaben laden*/
		 $s_benutzername = $this->
											 input->
											 post('benutzername', true);
		 $s_nachname 		 = $this->
											 input->
											 post('nachname', true);
		 $s_vorname			 = $this->
											 input->
											 post('vorname', true);
		 $s_email				 = $this->
											 input->
											 post('email', true);
		 $s_passwort		 = $this->
											 input->
											 post('password', true);

		 /** Array für schreiben Datenbank*/
		 $s_update_daten['username'] 							= $s_benutzername;
		 $s_update_daten['last_name'] 						= $s_nachname;
		 $s_update_daten['first_name']						= $s_vorname;
		 $s_update_daten['password'] 							= $s_passwort;

		 $s_update_daten['newEmail'] 							= $s_email;
		 $s_update_daten['newEmailActivationCode']= sha1(md5(microtime()));


		/** Wenn Benutzername leer, dann wurde View noch nicht mit den Aktuellen
		 * Datenbank Informationen des User abgefüllt. In diesem Falle diese
		 * Vieew laden und Funktion vorzeitig beenden */
		 if($s_benutzername == '' &&
		    $i_id != -1) {

			 /** Viewdaten aufbereiten */
			 $this->
 			 data['message'] = (validation_errors()
 											 ? validation_errors()
 											 : ($this->
 												 ion_auth->
 												 errors()
 												 ? $this->ion_auth->errors()
 												 : $this->session->flashdata('message')));
			 $this->
			 data['s_benutzername']  = $a_userdaten[0]->
	 			 												 username;
			 $this->
			 data['s_nachname'] 		 = $a_userdaten[0]->
			 													 last_name;
			 $this->
			 data['s_vorname']  		 = $a_userdaten[0]->
			 							 						 first_name;
			 $this->
			 data['s_email'] 	 			 = $a_userdaten[0]->
			 						 							 email;


			 /** Formularmaske aufrufen  */
			 $this->
			 renderView('auth_editKunde',
									$this->
									data);

			 return;

		/** Viewdaten wurden einmalig aus datenbank abgefüllt und können nun direkt aus den
		 * Postdaten geldaen werden */
		 } else {

			 /** Viewdaten aufbereiten */
			 $this->
			 data['s_benutzername'] = $s_benutzername;
			 $this->
			 data['s_nachname'] 		 = $s_nachname;
			 $this->
			 data['s_vorname']  		 = $s_vorname;
			 $this->
			 data['s_email'] 	 		 = $s_email;

		 }

	 	/** Benutzereingaben Inputfelder Validierung
		 * Wenn durch Post Variablen neue Werte mitgegeben werden, diese Validieren
		 * Regeln setzen
		 * Benutzername - ist zugeleich der Datenabnk Schlüssels
		 *  */
		if($s_benutzername != $a_userdaten[0]->
			 										username)
		{
			/** Feld hat sich verändert. Eingabe neu validieren */
			$this->
			form_validation->
			set_rules('benutzername',
								'benutzername',
								'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
		}

		/** Nachname */
		$this->
		form_validation->
		set_rules('nachname',
							'Nachname',
							'trim');
		/** Vorname */
		$this->
		form_validation->
		set_rules('vorname',
							'Vorname',
							'trim');
		/** Email - ist zugeleich der Datenabnk Schlüssels */
		$this->
		form_validation->
		set_rules('email',
							'Email',
							'trim|required|valid_email');

		/** Passwort */
		$this->
		form_validation->
		set_rules('password',
							'Passwort',
							'min_length[' . $this->
														  config->
														  item('min_password_length', 'ion_auth') . ']|'.
							'max_length[' . $this->
															config->
															item('max_password_length', 'ion_auth') . ']');

		/** Passwort Bestätigung */
		$this->
		form_validation->
		set_rules('passwortConfirm',
							'Passwort Bestätigung',
							'matches[password]');


		/** Benutzereingaben Inputfelder Validierung und
		 * zugleich die Funktion ausführen zum erstellen des Users
		 *
		 * Validierung starten
		 */
		if ($this->
 				form_validation->
 				run() === TRUE &&
				$this->
				ion_auth->
				update($i_id,
							 $s_update_daten) )
		{
			/**  */
			$this->
			session->
			set_flashdata('message', $this->
															 ion_auth->
															 messages());

		 /** Wenn Email geändert wurde und eine valide Email ist, so wird ein Link
			* versendet an dieses Email. Erst wenn der Link bestätigt wurde, wird diese
			* Email gespeichert
			*  */
		 if($s_email != $a_userdaten[0]->
										email)
		 {
			 $message = 'Bitte auf diesen Link klicken, um die Email zu bestätigen und definitiv zu verändern: <br><br>' .
									 site_url() . 'Auth/editKundenEmail/' . $s_update_daten['newEmailActivationCode'] . "/" . $i_id;
			 $this->email->clear();
			 $this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
			 $this->email->to($s_email);
			 $this->email->subject($this->config->item('site_title', 'ion_auth'));
			 $this->email->message($message);

			 /** Emialversan für bestätigung Emaildresse
			  * Standartinformation mit Zusatnand Emailversand erweitern*/
			 $s_browse_message = $this->
													 session->
													 flashdata('message');

			/** Emailversand aauslöxen */
			 if ($this->email->send())
			 {
				 $s_browse_message .= '<br><br>Es wurde ein Email verschickt an die neu angegebene Adresse. Erst mit bestätigen des Links wird die neue Emailadresse übernommen';
				 $s_message = $this->
										  session->
										  set_flashdata('message', $s_browse_message);

			 } else {

				 $s_browse_message .= '<br><br>Emailversand fehlgeschlagen. Versuchen Sie erneut die Email zu ändern';
				 $s_message = $this->
											session->
											set_flashdata('message', $s_browse_message);
			}

		 }

		 /** Messsages in Session schreiben für Redirect */
		 $this->
		 session->
		 set_flashdata('message',
									 $this->
									 ion_auth->
									 errors());

		 /** Formularmaske aufrufen  */
		 $this->
		 renderView('auth_editKunde',
								$this->
								data);

		// redirect(site_url() . 'Auth/editKunde/' . $i_id);


		/**  */
		} else {

			/** Messsages in Session schreiben für Redirect */
			$this->
			session->
			set_flashdata('message',
										$this->
										ion_auth->
										errors());

			/** Validierungsfehler für view daten aufberetiten */
			$this->
			data['message'] = (validation_errors()
											? validation_errors()
											: ($this->
												 ion_auth->
												 errors()
												 ? $this->ion_auth->errors()
												 : $this->session->flashdata('message')));

			/** Formularmaske aufrufen  */
			$this->
			renderView('auth_editKunde',
								 $this->
								 data);

		}

	}




	/**
	 * Funktion zum Bestätigen des Bestätigungslinkes für editKunde() bei Emailänderung
	 *
	 *
	 * @author      	Bruno Kirchmeier
	 * @date        	20181201
	 *
	 */
	public function editKundenEmail()
	{
		/** URL Segemente auslesen */
		$i_id 						= $this-> uri->segment(4);
		$i_code_from_url 	= $this-> uri->segment(3);
		$s_message				= '';
		$a_update_daten		= array();

		/** Gesamte Userdaten laden mit gesuchter id */
		if (is_numeric($i_id) &&
				$i_id > 0)
		{
			$a_userdaten = $this->
										 MY_Model->
										 get('users',
												 $i_id);

		}

		/** Die id konnte einem Benutzer auf der Datenabnk zugortnet werden */
		if(isset($a_userdaten[0]))
		{

			/** Acvtivationcode prüfen */
			if( $i_code_from_url ==
					$a_userdaten[0]->
					newEmailActivationCode )

			{
					/** Neue Daten erstellen */
					$a_update_daten = array('email' 									=> $a_userdaten[0]->
																															 newEmail,
																	'newEmail' 			 					=> '',
																	'newEmailActivationCode'  => '' );

					/** Updatesatrten */
					if($this->
						 MY_Model->
						 update('users',
										$i_id,
										$a_update_daten) == true )
					{
							/** Erfolgreiech */
							$s_message = "Email wurde ge&auml;ndert";

					/** Datenbank Fehler */
					} else {
						$s_message = "unbekannter Fehler. Emailadresse wurde nicht ge&auml;ndert";
					}

			/** Validierungscode konnte nicht gefuden werden bei entsprechendem Benutzer */
			} else {
					$s_message = "Unbekannter Validierungscode. Emailadresse wurde nicht ge&auml;ndert";
			}

			/** Keine Benutzerdaten gefunden */
			} else {
				$s_message = "Unbekannter Benutzer. Emailadresse wurde nicht ge&auml;ndert";
			}

			/** Funktion beendet, Rückgabe des Resultates */
			echo $s_message;

		}

	}

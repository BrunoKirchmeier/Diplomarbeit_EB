<?php
/**
 * Name:    Ion Auth Model
 * Author:  Ben Edmunds
 *           ben.edmunds@gmail.com
 * @benedmunds
 *
 * Added Awesomeness: Phil Sturgeon
 *
 * Created:  10.01.2009
 *
 * Description:  Modified auth system based on redux_auth with extensive customization. This is basically what Redux Auth 2 should be.
 * Original Author name has been kept but that does not mean that the method has not been modified.
 *
 * Requirements: PHP5 or above
 *
 * @package    CodeIgniter-Ion-Auth
 * @author     Ben Edmunds
 * @link       http://github.com/benedmunds/CodeIgniter-Ion-Auth
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Ion Auth Model
 * @property Bcrypt $bcrypt The Bcrypt library
 * @property Ion_auth $ion_auth The Ion_auth library
 */
class Backend_AktuellesStueck_model extends CI_Model
{


 /** Sache TinyMce Daten
  *
  *
  */
 public function insertView($s_view_content,
 														$s_view_name,
														$s_html_insert_by_id,
														$s_category_name)
 {
	 /** Hilfsvariablen */
	 $i_id_kategorie 				= -1;
	 $s_view_name 					= strtolower($s_view_name);
	 $b_view_name_unique 		= 0;
	 $i_insert_id						=	-1;
	 $i_insert_success			=	-1;
	 $a_return['i_status']	= 0;
	 $a_return['b_success']	= 0;


   // id der Kategorie suchen
	 $query = $this->
						db->
						get_where('sort_categories',
								  		array('name' => $s_category_name) );

   /** Resulat von Query lesen */
   if($query !== FALSE &&
      $query->num_rows() >= 0)
   {
     /** Resulat von Query lesen */
     $a_resultat = $query->result();

     /** Prüfen on Anzahl genau 1 ist */
      if(count($a_resultat) == 1)
      {
        $i_id_kategorie = $a_resultat[0]->
                          id;
      }

   }

	 /** Prüfen, ob view Name bereits in Datenbank vorhanden ist
	  * NAme muss unikat sein */
		// id der Kategorie suchen
		$query = $this->
						 db->
						 get_where('embedded_view',
											 array('name' => $s_view_name) );

     /** Resulat von Query lesen */
     if($query !== FALSE &&
        $query->num_rows() >= 0)
     {
       /** Resulat von Query lesen */
       $a_resultat = $query->result();

       /** Prüfen on Anzahl genau 1 ist */
        if(count($a_resultat) == 0 &&
 			 		$s_view_name <> '')
        {
          $b_view_name_unique = 1;
        }

     }

		  /** Wenn die beiden überprüfungen wahr sind, dann in Datenbank eintragen*/
			if($b_view_name_unique &&
				 $i_id_kategorie > -1 &&
				 $s_html_insert_by_id <> '')
			{
				/** Array mit Date naufbereiten */
				$array = array('sort_categories_id' 	=> $i_id_kategorie,
							         'name' 					      => $s_view_name,
							         'html_content' 				=> $s_view_content,
											 'html_id_name_insert'  => $s_html_insert_by_id
				);

				/** Eintarg in DAtenbank ausführen */
				$a_return['b_success'] = $this->
      													 db->
      													 insert('embedded_view', $array);
			 }

				/** Erstellte Id Prüfen */
				$i_datensatz_id = $this->
													db->
													insert_id();

		 /** Return Verarbeitung
 		  * 1. leere Argumente */
 	 	 if($s_view_name == '' ||
 	 	 		$s_html_insert_by_id == '')
 	 	 {
 	 		 $a_return['i_status'] = 1;

		 /** View Name ist nicht Unique */
 	 	 } else if(! $b_view_name_unique) {

 		 		$a_return['i_status'] = 2;

		 /** System Fehler */
	 	 } else if($i_id_kategorie == -1 ||
							 $a_return['b_success'] == false ||
							 $i_datensatz_id < 0 )
	 	 {
			 	$a_return['i_status'] = 3;
	   }


		 /** Return */
		 return $a_return;
	 }




	 /** Sache TinyMce Daten
	  *
	  *
	  */
	 public function getView($s_category_name = '',
	 												 $i_id = -1)
	 {
		 /** Hilfsvariablen */
		 $i_id_kategorie 				= -1;
		 $a_resultat						= array();
		 $a_return['a_daten']		= array();
		 $a_return['i_status']	= 0;
		 $a_return['b_success']	= false;


		 /** Zuerst bestimmen, welche id, die gesuchte Kategorie hat
		  * Jedoch nur, wenn mit $i_id kein einzerlner Datensatz gesucht wird */
			if($i_id == -1)
			{
				// id der Kategorie suchen
		 	 $query = $this->
		 						db->
		 						get_where('sort_categories',
		 								  		array('name' => $s_category_name) );

        /** Resulat von Query lesen */
        if($query !== FALSE &&
           $query->num_rows() >= 0)
        {
          /** Resulat von Query lesen */
          $a_resultat = $query->result();

          /** Prüfen on Anzahl genau 1 ist */
           if(count($a_resultat) == 1)
           {
             $i_id_kategorie = $a_resultat[0]->
                               id;
           }
         }
        }

			/** Alle Views mit bestimmter Kategorie suchen */
			if($i_id ==  -1 &&
				 $i_id_kategorie <> -1)
			{
				/** Query erstellen */
				$query = $this->
								 db->
								 get_where('embedded_view',
													 array('sort_categories_id' => $i_id_kategorie) );

				/** Resulat von Query lesen */
        if($query !== FALSE &&
           $query->num_rows() >= 0)
        {
				    $a_resultat = $query->result();
        }

			/** Ein bestimmtes Views nach id suchen, ohne Kategorie einschränkung */
			} else
			{
				/** Query erstellen */
				$query = $this->
 		 						 db->
 		 					   get_where('embedded_view',
 		 								  		 array('id' => $i_id) );

         /** Resulat von Query lesen */
         if($query !== FALSE &&
            $query->num_rows() >= 0)
         {
 				    $a_resultat = $query->result();
         }

			}

			/** Prüfen ob Antwort ein Array ist */
			if(is_array($a_resultat) )
			{
			 /** Prüfen ob mindest ein ergebnisse gefunden wurden */
				if(count($a_resultat) > 0)
				{
					/** Aktion erfolgreich */
					$a_return['a_daten'] 	 = $a_resultat;
					$a_return['b_success'] = true;
				}
			}

		 /** Return Verarbeitung
 		  * Keine gültige Kategorie gefunden*/
 	 	 if($i_id <> -1 &&
 	 	 	  $i_id_kategorie == -1)
 	 	 {
 	 		 $a_return['i_status'] = 1;

 	 	 }

		 /** Return */
		 return $a_return;
	 }



	 /** Sache TinyMce Daten
	  *
	  *
	  */
	 public function activateView($s_category_name,
	 														 	$i_id)
	 {
		/** Hilfsvariablen */
		$i_id_kategorie 					= -1;
		$b_id_is_in_kategorie			= 0;
		$b_update_inaktiv_status	= 0;
		$a_return['i_status']	    = 0;
		$a_return['b_success']	 = false;

		/** Id der KAtegorie auslesen. Es darf nur eine Resultat als Antwort geben */
		$query = $this->
						 db->
						 get_where('sort_categories',
											 array('name' => $s_category_name) );

     /** Resulat von Query lesen */
     if($query !== FALSE &&
        $query->num_rows() >= 0)
     {
       /** Resulat von Query lesen */
       $a_resultat = $query->result();

       /** Prüfen on Anzahl genau 1 ist */
        if(count($a_resultat) == 1)
        {
          $i_id_kategorie = $a_resultat[0]->
                            id;
        }

     }

		/** Prüfen ob die zu ändernde id auch der entsprechenden Kategorie
		 * angehört */
		if($i_id_kategorie <> -1)
		{
			$query = $this->
							 db->
							 get_where('embedded_view',
												 array('id' => $i_id) );

			/** Resulat von Query lesen */
      if($query !== FALSE &&
         $query->num_rows() >= 0)
      {
			     $a_resultat = $query->result();
      }

			/** Prüfen ob Antwort ein Array ist */
			if(is_array($a_resultat) )
			{
			 /** Prüfen on Anzahl genau 1 ist */
				if(count($a_resultat) == 1)
				{
					$b_id_is_in_kategorie = 1;
				}
			}
		}


		/** Alle Views mit bestimmter Kategorie suchen */
		if($b_id_is_in_kategorie)
		{
			/** Nur Views mit dieser Kategorie updaten auf inaktiv */
			$this->db->where('sort_categories_id',
											 $i_id_kategorie);

			/** Updatebefehl: 	 alle inaktiv setzen durchführen */
			$b_update_inaktiv_status = $this->
																 db->
																 update('embedded_view',
													 						  array('view_is_active' => false) );

			/** Updatebefehl:		 auswahl aktiv setzen durchführen */
			if($b_update_inaktiv_status)
			{
				/** Auswahl view nach Kategorie */
				$this->db->where('id',
												 $i_id);

				$a_return['b_success'] =   $this->
																	 db->
																	 update('embedded_view',
																					array('view_is_active' => true) );
			}
		}

		/** Return Verarbeitung
		 * Kategorie nicht gefunden */
		if($i_id_kategorie == -1)
		{
			 $a_return['i_status'] = 1;

		/** Zu ändernde id ist nicht in der gescuhten Kategorie vorhanden */
		} else if(! $b_id_is_in_kategorie) {

			 $a_return['i_status'] = 2;

		/** Fehler beim inaktiv setzen */
		} else if(! $b_update_inaktiv_status) {

			 $a_return['i_status'] = 3;

	 /** Alle Views sind inaktiv, aber fehler beim aktiv setzen */
	 } else if(! $a_return['b_success'] ) {

			 $a_return['i_status'] = 4;
	 }

		/** Return */
		return $a_return;
	}












}

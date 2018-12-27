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
class Backend_Tickets_model extends CI_Model
{



	 /** Sache TinyMce Daten
	  *
	  *
	  */
	 public function getTypeOfTicket($i_id = -1)
	 {
		 /** Hilfsvariablen */
		 $a_resultat						= array();
		 $a_return['a_daten']		= array();
		 $a_return['i_status']	= 0;
		 $a_return['b_success']	= false;


		/** Query erstellen
		 * Join mit Tabellen:
		 * 1. seating plan und
		 * 2. ticket_category  */
		$this->
		db->
		select('type_of_ticket.* ,,
						ticket_category.name AS ticket_category_name,
						ticket_category.description AS ticket_category_description ');

		$this->
		db->
		from('type_of_ticket');

		/** WEnn nur ein einzelner Datensatz nach id gesucht ist */
		if($i_id !=  -1)
		{
			$this->
			db->
			where('type_of_ticket.id', $i_id);
		}

	 $this->
	 db->
	 join('ticket_category',
				'type_of_ticket.ticket_category_id = ticket_category.id');

	 $this->
	 db->
	 order_by("type_of_ticket.event_date", "asc");


		/** Query ausführen */
		$query = $this->
						 db->
						 get();

		/** Resulat von Query lesen */
		if($query !== FALSE &&
			 $query->num_rows() >= 0)
		{
				$a_resultat = $query->result();
		}


			/** Prüfen ob Antwort ein Array ist */
			if(is_array($a_resultat) )
			{
					/** Aktion erfolgreich */
					$a_return['a_daten'] 	 = $a_resultat;
					$a_return['b_success'] = true;


			} else {
        	$a_return['b_success'] = false;
      }

		 /** Return */
		 return $a_return;
	 }




	 /** Sache TinyMce Daten
	  *
	  */
	 public function createTypeOfTicket($a_daten)
	 {
		 /** Für Datenbankabfrage später ist ein kombiniertes datetime feld besser als
			* als seperate daet und time. Daher hier aus den beiden html Feldern ein
			* datetime frld erstellen und abspeichern  */

			/** FELD:		start_sale_time  */
 		 $a_daten['event_datetime'] =
 		 $this->
  	 __htmlInputToDatetime($a_daten['event_time'],
 		 											 $a_daten['event_date']);

 		 /** FELD:		start_sale_time  */
 		 $a_daten['start_sale_datetime'] =
 		 $this->
  	 __htmlInputToDatetime($a_daten['start_sale_time'],
 		 											 $a_daten['start_sale_date']);

 		 /** FELD:		start_sale_time  */
 		 $a_daten['end_sale_datetime'] =
 		 $this->
 		 __htmlInputToDatetime($a_daten['end_sale_time'],
 													 $a_daten['end_sale_date']);


			// Tabelleneintrag für Tabelle 1:	ticket_administration erstellen
			$a_return = $this->
 		 							MY_Model->
 		 							insert_with_validation('type_of_ticket',
 																					$a_daten);

		 	// Return value
		 	return $a_return;
	 }




	 /** Sache TinyMce Daten
		*
		* @return array  $a_return            Indexe:
	  *                                     ['i_status']:   Fehlermeldungen. Jeweils nur einen Error in Form
	  *                                                     eines Integers (Es könnten aber auch mehrere aktiv sein)
	  *                                                     0:  Kein Fehler
		*                                                     1:  Aktion  fehlerhaft
		*
	  *                                     ['b_success']:  Wenn alles erfolgreich war, dann True
		*/
	 public function updateTypeOfTicket($i_id,
	 																		$a_daten )
	 {
			 /** Für Datenbankabfrage später ist ein kombiniertes datetime feld besser als
				* als seperate daet und time. Daher hier aus den beiden html Feldern ein
				* datetime frld erstellen und abspeichern  */

			 /** FELD:		start_sale_time  */
			 $a_daten['event_datetime'] =
			 $this->
	 	 	 __htmlInputToDatetime($a_daten['event_time'],
			 											 $a_daten['event_date']);

			 /** FELD:		start_sale_time  */
			 $a_daten['start_sale_datetime'] =
			 $this->
	 	 	 __htmlInputToDatetime($a_daten['start_sale_time'],
			 											 $a_daten['start_sale_date']);

			 /** FELD:		start_sale_time  */
			 $a_daten['end_sale_datetime'] =
			 $this->
			 __htmlInputToDatetime($a_daten['end_sale_time'],
														 $a_daten['end_sale_date']);


			// Hilfsvariablen
			$a_return['i_status']		= 0;
			$a_return['b_success'] 	= 0;


			// Tabelleneintrag für Tabelle 1:	type_of_ticket updaten
			$a_return['b_success'] = $this->
															 MY_Model->
															 update('type_of_ticket',
																	 	  $i_id,
																		  $a_daten);

			// Aktion war erfolgreich
			if($a_return['b_success'] != true) {

				// Aktion war fehlerhaft
				// Status setzen
				$a_return['i_status']	= 1;
			}

			// Return value
			return $a_return;
	 }


	 /** Sache TinyMce Daten
		*
		* @return array  $a_return            Indexe:
	  *                                     ['i_status']:   Fehlermeldungen. Jeweils nur einen Error in Form
	  *                                                     eines Integers (Es könnten aber auch mehrere aktiv sein)
	  *                                                     0:  Kein Fehler
		*                                                     1:  Fremdschlüssel nicht gefunden
		*                                                     2:  Aktion Tabelle 1 fehlerhaft
		*																											3:  ktion Tabelle 2 fehlerhaft
		*
	  *                                     ['b_success']:  Wenn alles erfolgreich war, dann True
		*/
	 public function deleteTypeOfTicket($i_id)
	 {
		 /**
			* Beim löschen müssen 2 Tabellen grlöscht werden beide sin ohne die andere Wertlos
			* Zuerst den Fremdkey der Tabelle 1 aus der Tabelle 2 lesen
			* danach löschen de Tbaelle 1 und zuleszt der Tabelle 2
			*
			* Tabelle 1:	type_of_ticket
			* Tabelle 2:	ticket_administration
			*
			* */

			// Hilfsvariablen
			$a_return['i_status']		= 0;
			$a_return['b_success'] 	= 0;
			$i_fremdkey							= -1;
			$a_daten 								= array();

			// Transaction starten
			$this->
			db->
			trans_start();

			// Fremkey der Tabelle 2 suchen
			$a_daten = $this->
								 MY_Model->
								 get('type_of_ticket',
							 			 $i_id);


				// Fremdschlüssel suchen
				// Aktion erfolgreich
				// Fremdkey zwischenspeichern und Tabelle 1 löschen
	 			if(isset($a_daten[0]) &&
					 count($a_daten) == 1 &&
				 	 is_object($a_daten[0]) &&
					 property_exists($a_daten[0],
					 								 'ticket_administration_id') )
	 			{
					// Key speichern
					$i_fremdkey = $a_daten[0]->
												ticket_administration_id;

					// Löschen: Tabelle 1:	type_of_ticket
					$a_return = $this->
											MY_Model->
											delete('type_of_ticket',
														 $i_id);

					// Tabelle 1:	type_of_ticket
					// Aktion war erfolgreich - weiter zu tabelle 2
					if($a_return['b_success'] == true)
					{

						// Löschen: Tabelle 2:	ticket_administration
						$a_return = $this->
												MY_Model->
												delete('ticket_administration',
															 $i_fremdkey);

						// Tabelle 2:	ticket_administration
						// Aktion war fehlerhaft
						if($a_return['b_success'] != true) {

							// Query aufheben - kein Eintrag in die Datenbank
							$this->
							db->
							trans_rollback();

							// Status setzen
							$a_return['i_status']	= 3;
						}

					// Tabelle 1:	type_of_ticket
					// Aktion war fehlerhaft
					} else {

						// Query aufheben - kein Eintrag in die Datenbank
						$this->
						db->
						trans_rollback();

						// Status setzen
						$a_return['i_status']	= 2;
					}

				// Fremdschlüssel suchen
				// Aktion fehlerhaft
				} else {

					// Query aufheben - kein Eintrag in die Datenbank
					$this->
					db->
					trans_rollback();

					// Status setzen
					$a_return['i_status']	= 1;
				}

				// Transaction beendet
				// sofern keine Rollback gemacht wurden, werden nun die Tabellen Erstellt
				// Ansonsten keine
				$this->
				db->
				trans_complete();

				// Return value
				return $a_return;
		}





/** HTML Felder DAte und Time zu einem kombinierten Datentyp datetime zusammenfügen
*
* Return string als datetime
* */
private function __htmlInputToDatetime($s_time,
																			 $s_date)
{
	/** FELD:		event_time
	* HTML Feld Zeit - Stunden in Sekunden seit Tagesbeginn */
 $i_event_stunden = substr($s_time,
																0,
																2) * 3600;
 /** HTML Feld Zeit - Minuten in Sekunden seit Tagesbeginn */
 $i_event_minuten = substr($s_time,
																3,
																2) * 60;
 /** Timestamp erstellen:
	* FELD:		event_date mit FELD Zeit konmbinieren */
 return date('Y-m-d H:i:s', strtotime($s_date)
														+ $i_event_stunden + $i_event_minuten);
}










}

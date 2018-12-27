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
class Ticket_model extends CI_Model
{



	/**
   * name:        getTypeOfTicketForSale
   *
   * Alle Tickettypen laden, welche gemäss start und end verkaufsdatum zum verkauf
	 * anstehend sind
	 *
   *
   * @param int  		$i_id   Wenn dieses Argument ungleich -1 ist, dann wird ein Datensatz
	 *												geladen mit dieser id, egal ob verkaufsdatum korrekt ist
	 *												Wenn -1, dann wirkt verkaufsdatum
	 *
   * @return array  $a_return            Indexe:
   *                                     ['b_success']:  Wenn alles erfolgreich war, dann True
   *                                     ['a_daten']:   html Content für View
   *
   * @author      Bruno Kirchmeier
   * @date        20181201
   *
   **/
	 public function getTypeOfTicketForSale($i_id = -1)
	 {
		 /** Hilfsvariablen */
		 $datetime_aktuell 			= date('Y-m-d H:i:s');
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
		select( 'type_of_ticket.* ,
						ticket_category.id AS ticket_category_id,
					  ticket_category.name AS ticket_category_name,
						ticket_category.description AS ticket_category_description,
						ticket_category.price_children AS ticket_category_price_children,
						ticket_category.price_adult AS ticket_category_price_adult ');

		$this->
		db->
		from('type_of_ticket');

		/** Wenn nur ein einzelner Datensatz nach id gesucht ist */
		if($i_id !=  -1)
		{
			$this->
			db->
			where('type_of_ticket.id', $i_id);

		/** Andernfalls alle Tickets welche sich innerhalb des aktiven Verkaufdatums befinden */
		} else {

			$this->
			db->
			where('start_sale_datetime' . ' < ', $datetime_aktuell);
			$this->
			db->
			where('end_sale_datetime' . ' > ', $datetime_aktuell);

	}

	 $this->
	 db->
	 join('ticket_category',
				'type_of_ticket.ticket_category_id = ticket_category.id');

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



		/**
		 * name:        addReservierteTickets
		 *
		 * Ticket wurden in warenkorb gestellt. Auf der Datenbank wir ein Ticket erstellt
		 * jedoch mit dem Status 1 (reserviert).
		 * Beim Aufruf dieser Funktion wird zuerst geschaut, ob es Tickets dieses
		 * Tickettypen hat, welche seit mehr als 15 Minuten erstellt worden sind. Alle
		 * Diese Tickets werden gelöscht und die reservierten Tickets so freigegeben
		 *
		 * Danach werden neue Tickets einigefügt. entsprechend der Anzahl. Ticket für Kinder
		 * haben einen anderen Preis, welches durch ein Flag definiert ist. Deshalb wird die
		 * subfunuktion 2 mal mit einer Schlaufe aufgerufen. Pro Schlaufe wir ein Ticket
		 * erstellt
		 *
		 * @param int  	 	$i_tickettyp_id   	Tickettyp nahc welchem das Ticket erstellt werden soll
		 * @param int  	$i_anz_child       	Anzahl zu erstellende Tickets mit Preis Kinder
		 * @param int  	$i_anz_adult       	Anzahl zu erstellende Tickets mit Preis Erwachsene
		 *
		 * @return array  $a_return            Indexe:
		 *                                     ['i_status']:   Fehlermeldungen. Jeweils nur einen Error in Form
		 *                                                     eines Integers (Es könnten aber auch mehrere aktiv sein)
		 *                                                     0:  Kein Fehler
		 *                                                     1:  Fehler beim insert
		 *                                                     2:	Datenbankfehler lesen ticketinformationen
		 *																											3:	Validerung ist fehlgeschlagen - Update ungültig
		 *
		 *                                     ['b_success']:  Wenn alles erfolgreich war, dann True
		 *
		 * @author      Bruno Kirchmeier
		 * @date        20181201
		 *
		 **/
	 public function addReservierteTickets($i_tickettyp_id,
																				 $i_anz_child,
																				 $i_anz_adult)
	 {
		 /** Hilfsvariablen */
		 $i_anz = $i_anz_child + $i_anz_adult;
		 $i_alter_in_sek_zum_loeschen	=	900;
		 $timestamp_15_minuten_alt 		= strtotime(date('Y-m-d H:i:s')) - $i_alter_in_sek_zum_loeschen;
		 $datetime_15_minuten_alt			= date('Y-m-d H:i:s',
		 																		 $timestamp_15_minuten_alt);

		 $a_return['i_freie_tickets']	= -1;
		 $a_return['i_status']				= 0;
		 $a_return['b_success'] 			= 0;

		 // Alle Tickets mit status reserviert, welche bereits seit 15 Minuten eingetragen sind
		 // löschen
		 // Query aufbauen
		 $this->
		 db->
		 where('type_of_ticket_id',
		 			 $i_tickettyp_id);
		 $this->
		 db->
		 where('ticketState',
					 1);
		 $this->
		 db->
		 where('update_time <',
		 			 $datetime_15_minuten_alt);

		 // SQL AKtion ausführen und status speichern
		 $this->
		 db->
		 delete('tickets');

		 // Transaction starten
		 // 1.	erstellen eines neuen Tickets mit Status 1 (reserviert)
		 //			=> Pro Loop wir ein ticket erstellt
		 // 2.	alle Tickets mit diesem Tickettyp und Status annuliert lesen und anzahl retour geben
		 // 3.	Aus Type of Ticket di maximal zu verkaufende Ticketanzahl lesen
		 // 4.	alle Tickets mit diesem Tickettyp und Status bookes lesen und anzahl retour geben
		 // 		Anzahl reserviert + Anazhl verkauft <= maximal anzahl
		 $this->
		 db->
		 trans_start();

		 // Kinder Tickets
		 for ($i = 0; $i < $i_anz_child; $i++)
		 {
			 // Daten aufbereiten für reservierte Tickets
			 $a_daten = array('ticket_state'				=>  1,
			 									'last_chaned_by' 			=> 	$this->
			 																						data['i_active_user_id'],
												'is_child'						=>  TRUE,
												'users_id'			 			=>	$this->
									 		 														data['i_active_user_id'],
												'type_of_ticket_id'		=> 	$i_tickettyp_id	);

			 // Eintarg in Datenbank erstellen
			 if (! $this->
					   db->
					   insert('tickets',
									  $a_daten) )
			 {
			 	// Insert war fehlerhaft
				$a_return['i_status'] = 1;
			 }
		 }

		 // Erwachsenen Tickets
		 for ($i = 0; $i < $i_anz_adult; $i++)
		 {
			 // Daten aufbereiten für reservierte Tickets
			 $a_daten = array('ticket_state'				=>  1,
												'last_chaned_by' 			=> 	$this->
																									data['i_active_user_id'],
												'is_child'						=>  FALSE,
												'users_id'			 			=>	$this->
																									data['i_active_user_id'],
												'type_of_ticket_id'		=> 	$i_tickettyp_id	);

			 // Eintarg in Datenbank erstellen
			 if (! $this->
						 db->
						 insert('tickets',
										$a_daten) )
			 {
				// Insert war fehlerhaft
				$a_return['i_status'] = 1;
			 }
		 }

		 // Wenn mindesten ein insert fehlerhaft war, aktion rückgängig machen
		 if ($a_return['i_status'] == 1)
		 {
			 $this->
			 db->
			 trans_rollback();

		 //	Wenn alle Insert erfolgreich waren, so wird Aktion fortgesetzt
		 // Starten der überprüfenungen 2 bis 4
	 	 } else {

			 // Ticket Typ lesen
			 $a_type_of_ticket = 		$this->
															MY_Model->
															get_where('type_of_ticket',
																				array('id' => $i_tickettyp_id) );

			 // Anzahl Tickets mit Status booked lesen
			 $a_state_booked 		= 	$this->
															MY_Model->
													  	get_where('tickets',
			                           		 		array('type_of_ticket_id' => $i_tickettyp_id,
																							'ticket_state'			=> 0) );

			 // Anzahl Tickets mit Status Reserviert lesen
			 $a_state_reserviert = 	$this->
															MY_Model->
													  	get_where('tickets',
			                           		 		array('type_of_ticket_id' => $i_tickettyp_id,
																							'ticket_state'			=> 1) );

			 // Prüfen ob alle drei Abfragen ohne Fehler statgefunden haben
			 // Wenn ja dann die überprfüung mit der Ticketmenge durchführen
			 if(is_array($a_type_of_ticket) &&
			 		is_array($a_state_booked) &&
			 		is_array($a_state_reserviert) &&
					isset($a_type_of_ticket[0]) &&
					count($a_type_of_ticket) == 1 &&
					is_object($a_type_of_ticket[0]) &&
					property_exists($a_type_of_ticket[0],
						 							'max_number_of_tickets') )
			 {
				 // Freie Tickets berechnen
				 $a_return['i_freie_tickets']	= $a_type_of_ticket[0]->
				 																max_number_of_tickets -
																				count($a_state_booked) -
																				count($a_state_reserviert);

				 // Validierung:
				 // Aktion fehlgeschlagen - Aktion abbrechen
				 // Anzahl reserviert + Anazhl verkauft <= maximal anzahl
				 if($a_return['i_freie_tickets'] < 0)
				 {
					 $this->
					 db->
					 trans_rollback();

					 // Freie Tickets neu berechnen, da neu reservierte verworfen werden berechnen
					 $a_return['i_freie_tickets']	+= $i_anz;

					 $a_return['i_status'] = 3;
				 }

			 // Mindestens eine der beiden Abfragen war Fehlerhaft. Aktion abbrechen
			 } else {

				 $this->
				 db->
				 trans_rollback();

				 $a_return['i_status'] = 2;
			 }

		 }
		 // Transaction beendet
		 // sofern keine Rollback gemacht wurden, werden nun die Tabellen Erstellt
		 // Ansonsten keine
		 $this->
		 db->
		 trans_complete();

		 return $a_return;
	 }



	 /**
		* name:        getTickets
		*
		* Alle Tickets mit detaailinformation üver fremdbeziehungen. Diese detailinformationen
		* werden für den Warenkorb gebraucht. Mittels where können die Tickets eingegrenzt
		* werden
		*
		* @param 	array  	 	$a_array   				Array of objekte mit Query definitionen
		* @param string  	$s_order_by     	Spalte nach welcher die Resulate geortnet werden sollen
		* @param string  	$s_auf_absteigend Auf/Absteigende sortierung wählen
		*
		* @return array/bool  $a_return       Indexe:
		*                                     ['a_daten']:   DAtensätze, oder false, wwenn ein error
		*																											aufgetreten ist
		*
		* @author      Bruno Kirchmeier
		* @date        20181201
		*
		**/
	 public function getTickets($a_array,
	 														$s_order_by = 'ticket_type_event_datetime',
															$s_auf_absteigend = 'DESC')  // ASC   DESC
	 {
	 	/** Hilfsvariablen */
	 	$a_return['a_daten']		= array();
		$a_return['i_status']		= 0;


	  /** Query erstellen
	 	* Join mit Tabellen:
	 	* 1. seating plan und
	 	* 2. ticket_category  */
	  $this->
	  db->
	  select('tickets.* ,
	 				 ticket_category.id AS category_id,
	 				 ticket_category.name AS category_name,
	 				 ticket_category.description AS category_description,
	 				 ticket_category.price_children AS price_children,
	 				 ticket_category.price_adult AS price_adult,
					 type_of_ticket.name AS ticket_type_name,
					 type_of_ticket.description AS ticket_type_description,
					 type_of_ticket.event_date AS ticket_type_event_date,
	 				 type_of_ticket.event_time AS ticket_type_event_time,
	 				 type_of_ticket.event_datetime AS ticket_type_event_datetime  ');


	  $this->
	  db->
	  from('tickets');

		$this->
		db->
		order_by($s_order_by,
						 $s_auf_absteigend);

		// Nur wenn Value gesetzt die Auswahl einscgränken
		if(! empty($a_array))
		{
			foreach ($a_array as $key => $value)
			{
				// Where Statments
				$this->
				db->
				where($key,
							$value);
			}
		}

		 // Joim - Ticket Type
		 $this->
		 db->
		 join('type_of_ticket',
		 		  'tickets.type_of_ticket_id = type_of_ticket.id');

		 // Joim - Ticket Kategorie
		 $this->
		 db->
		 join('ticket_category',
					'type_of_ticket.ticket_category_id = ticket_category.id');

		 // Query ausführen
		 $query =  $this->
							 db->
							 get();

		 // Prüfen ob kein Fehler passiert ist, damit in diesem Falle nicht von nicht
		 // vorhandenen Daten ausgegangen wird
		 // allfällige Fehlermeldungen zwischenspecihern
		 $a_error =  $this->
								 db->
								 error();

		 // Kein Fehler entdeckt
		 if($a_error['code'] == 0 )
		 {
			 // Rückgabe der gefeundenen Datensätze
			 return $query->
							result();

		 // Aktion war fehlerhaft
		 } else {

			 return false;
		 }

	 }



		/**
		 * name:        getTickets
		 *
		 * Alle Tickets mit detaailinformation üver fremdbeziehungen. Diese detailinformationen
		 * werden für den Warenkorb gebraucht. Mittels where können die Tickets eingegrenzt
		 * werden
		 *
		 * @param 	array  	 	a_daten   		update daten (array of objekt)
		 *
		 * @return array  $a_return            Indexe:
		 *                                     ['i_status']:   Fehlermeldungen. Jeweils nur einen Error in Form
		 *                                                     eines Integers (Es könnten aber auch mehrere aktiv sein)
		 *                                                     0:  Kein Fehler
		 *                                                     1:  Fehler beim update tickets
		 *                                                     2:	Fehler beim update tYPE_OF_TICKETS*
		 *																											3:	Fehler lesen tabellen für validierung
		 *																											4:	Validerung ist fehlgeschlagen - Update ungültig
		 *
		 *                                     ['b_success']:  Wenn alles erfolgreich war, dann True
		 *
		 * @author      Bruno Kirchmeier
		 * @date        20181201
		 *
		 **/
	 public function bookTicket($a_daten)
	 {
		 // Hilfsvariablen;
		 $a_return['i_status'] = 0;


		 // Transaction starten
		 // 1.	update der tickets im warenkorb (NUR KTIVER USER) auf starus 2
		 // 2.	tabelle type of tickets booked tickets um diese anzahl erhähen
		 // 3.	tabellen  type_of_tickets auslesen und anzahl kontrollieren ob noch korrekt
		 //			tabelle tickets nach status bookeed auslesen
		 // 		tabell tickets nach status rerviert auslesen
		 // 4.	berechnen ob nicht zu viele verkauft wurden
		 // 		Anzahl reserviert (alle user) + Anazhl verkauft <= maximal anzahl
		 $this->
		 db->
		 trans_start();

		 // Alle Tickettypen lesen
		 $a_types = $this->
								MY_Model->
								get_distinct('tickets',
														 'type_of_ticket_id',
														 array('ticket_state'	=> 1,
																	 'users_id' 		=> $this->
																										 data['i_active_user_id'] ) );

		  // Für jeden Tickettyp diesen Prozess wiederholen
			// Gesamte AKtion nur abschliesen, wenn alle Loop gut sind
		 	foreach ($a_types as $key => $value)
		 	{

				$i_tickettyp_id = $value->type_of_ticket_id;

				 // alle Tickets des angemeldeten Users auf Status 2 updaten
				 $i_return = $this->
										 MY_Model->
										 update_where('tickets',
																	$a_daten,
																	array('ticket_state'			=> 1,
																				'type_of_ticket_id'	=> $i_tickettyp_id,
																				'users_id' 					=> $this->
																													 		 data['i_active_user_id'] ));

				 // Aktion war erfolgreich
				 // 2.	tABELLE tYPE_OF_TICKETS um booked anzahl um anzahl updated rows erhöhen
				 if($i_return <> FALSE)
				 {
					 // Update war erfolgreich
					 // 3.	tabellen  type_of_tickets auslesen und anzahl kontrollieren ob noch korrekt
					 //			tabelle tickets nach status bookeed auslesen
					 // 		tabell tickets nach status rerviert auslesen

					 $a_type_of_ticket = 		$this->
																	MY_Model->
																	get_where('type_of_ticket',
																						array('id' => $i_tickettyp_id) );

					 // Anzahl Tickets mit Status booked lesen
					 $a_state_booked 		= 	$this->
																	MY_Model->
																	get_where('tickets',
																						array('type_of_ticket_id' => $i_tickettyp_id,
																									'ticket_state'			=> 0) );

					 // Anzahl Tickets mit Status Reserviert lesen
					 $a_state_reserviert = 	$this->
																	MY_Model->
																	get_where('tickets',
																						array('type_of_ticket_id' => $i_tickettyp_id,
																									'ticket_state'			=> 1) );


					 // Prüfen ob alle drei Abfragen ohne Fehler statgefunden haben
				 	 // Wenn ja dann die überprfüung mit der Ticketmenge durchführen
				 	 if(is_array($a_type_of_ticket) &&
				 			is_array($a_state_booked) &&
				 			is_array($a_state_reserviert) &&
				 			isset($a_type_of_ticket[0]) &&
				 			count($a_type_of_ticket) == 1 &&
				 			is_object($a_type_of_ticket[0]) &&
				 			property_exists($a_type_of_ticket[0],
				 											'max_number_of_tickets') )
				 	 {
						 // 4.	berechnen ob nicht zu viele verkauft wurden
						 // 		Anzahl reserviert (alle user) + Anazhl verkauft <= maximal anzahl

				 		 $i_freie_tickets	= $a_type_of_ticket[0]->
				 												max_number_of_tickets -
				 												count($a_state_booked) -
				 												count($a_state_reserviert);

							// 4.	berechnen ob nicht zu viele verkauft wurden
				 		 // 		Anzahl reserviert (alle user) + Anazhl verkauft <= maximal anzahl
				 		 if($i_freie_tickets < 0)
				 		 {
				 			 $a_return['i_status'] = 4;
				 		 }

				 	 // Mindestens eine der beiden Abfragen war Fehlerhaft. Aktion abbrechen
				 	 } else {

				 		 $a_return['i_status'] = 3;
				 	 }

				 // Fehler Datenbank
				 } else {

					 $a_return['i_status'] = 1;
				 }

			 // Ende Foreach
			 }

			 // Wenn Status ungleich 0 ist, dann alles rückgängig machen
			 if($a_return['i_status'] <> 0)
			 {
				 $this->
				 db->
				 trans_rollback();
			 }

			 // Transaction beendet
			 // sofern keine Rollback gemacht wurden, werden nun die Tabellen Erstellt
			 // Ansonsten keine
			 $this->
			 db->
			 trans_complete();

			 return $a_return;
	 }




}

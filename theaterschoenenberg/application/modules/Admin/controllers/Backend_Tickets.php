<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 *
 */
class Backend_Tickets extends Backend_Controller
{

	/**
	* Javascript und Css Files Controller spezifisch laden
	*
	*  @var array mSeitenJs   Array mit den zu ladenden JavaScript Files
	* */
	public $mSeitenJs      	= array('backend');



	/**
	* Konstruktor
	*
	* */
	public function __construct()
	{
		parent::__construct();

		/** Models laden */
		$this->load->model('Backend_Tickets_model');

		/** Type of tickets:
		 * Alle erstellen Type of tickets aus der Datenbank laden */
		$a_type_of_tickets = 	$this->
													Backend_Tickets_model->
													getTypeOfTicket();

		/** Type of tickets:
		 * Dropdown abfüllen:
		 * Views Daten für Dropdown aufbereiten als Objekt */
		if (isset($a_type_of_tickets['a_daten']) )
		{
			foreach ($a_type_of_tickets['a_daten'] as $key => $value) {

				/** Objektelement einfügen */
				$this->
				data['o_dropwown_tickets'][$value->id] = $value->name;
			}
		}

		/** Wenn Array leer ist, dann Index setzen */
		if(empty($this->
						 data['o_dropwown_tickets']) )
		{
			$this->
			data['o_dropwown_tickets'] = '';
		}


		/** Alle erstellen Ticket Kategorien aus der Datenbank laden */
		$a_ticket_category = 	$this->
													MY_Model->
													get('ticket_category',
															'',
															'');

		/** Ticket Kategorien:
		 * Dropdown abfüllen:
		 * Views Daten für Dropdown aufbereiten als Objekt */
		 if (isset($a_type_of_tickets ) )
		 {
			foreach ($a_ticket_category as $key => $value) {

				/** Objektelement einfügen */
				$this->
				data['o_dropwown_category'][$value->id] = $value->name;
			}
		}

		/** Wenn Array leer ist, dann Index setzen */
		if(empty($this->
						 data['o_dropwown_category']) )
		{
			$this->
			data['o_dropwown_category'] = '';
		}
	}



	/**
	 * Mit Index wird die View Login aufgerufen
	 */
	public function index()
	{

		/** Status Message laden */
		$this->
		data['s_message'] = $this->
												session->
												flashdata('message');


		/** View laden */
		$this->
		renderView('adminTickets_uebersicht',
							 $this->
							 data);
	}



	/**
   * name:        createTypeOfTicket
   *
   * Eine neue Ticketart erstellen. Validierung der Formulardaten mit
	 * Form vvalidation Libary
   *
   * @author      Bruno Kirchmeier
   * @date        20181201
   *
   **/
	public function createTypeOfTicket()
	{
		/** Benutzereingaben Inputfelder Validierung
		 * Regeln setzen
		 *
		 * Name */
		$this->
		form_validation->
		set_rules('name',
						  'Anzeigename',
						  'trim|required|is_unique[type_of_ticket.name]');
		/** event_date
		 * übergabe von Zusatzargumenten für Form Valiadation callback Funktion */
		$json_arg = json_encode(array($this->input->post('end_sale_date'),
																	'Enddatum Billetverkauf')
																);
		$this->
		form_validation->
		set_rules('event_date',
							'Veranstaltungsdatum',
							'trim|required|valid_date');
		/** event_time*/
		$this->
		form_validation->
		set_rules('event_time',
						  'Veranstaltungszeit',
						  'trim|required|valid_time');
		/** Kategorie */
		$this->
		form_validation->
		set_rules('category_id',
							'Kategorie',
							'trim|required|search_key[ticket_category.id]');
		/** max_number_of_tickets*/
		$this->
		form_validation->
		set_rules('max_number_of_tickets',
						  'Maximale Tickets für den Verkauf:',
						  'trim|required|integer' );
		/** start_sale_date
		 * übergabe von Zusatzargumenten für Form Valiadation callback Funktion */
		$json_arg = json_encode(array($this->input->post('end_sale_date'),
																	'Enddatum Billetverkauf')
																);
		$this->
		form_validation->
		set_rules('start_sale_date',
							'Startdatum Billetverkauf:',
							'trim|required|callback_is_date_2_greater['. $json_arg . ']' );
		/** start_sale_time */
		$this->
		form_validation->
		set_rules('start_sale_time',
							'Startzeit Billetverkauf',
							'trim|required|valid_time');
		/** end_sale_date
		* übergabe von Zusatzargumenten für Form Valiadation callback Funktion */
		$json_arg = json_encode(array($this->input->post('event_date'),
																	'Veranstaltungsdatum')
																);
		$this->
		form_validation->
		set_rules('end_sale_date',
							'Enddatum Billetverkauf',
							'trim|required|callback_is_date_2_greater['. $json_arg . ']' );
		/** event_date */
		$this->
		form_validation->
		set_rules('end_sale_time',
							'Endzeit Billetverkauf',
							'trim|required|valid_time');


		/** Benutzereingaben Inputfelder Validierung
		 *
		 * Validierung starten
		*/
		if ($this->
 				form_validation->
 				run($this) === TRUE )
		{
			/** Daten für Datenbank aufbereiten */
			$a_daten = array(
				'max_number_of_tickets' 			=> 	$this->
																					input->
																					post('max_number_of_tickets', true),
				'booked_number_of_tickets' 		=>	0,
				'start_sale_date' 						=>	$this->
																					input->
																					post('start_sale_date', true),
				'end_sale_date' 							=>	$this->
																					input->
																					post('end_sale_date', true),
				'start_sale_time' 						=>	$this->
																					input->
																					post('start_sale_time', true),
				'end_sale_time' 							=>	$this->
																					input->
																					post('end_sale_time', true),
				'name' 												=>	$this->
																			 		input->
																			 		post('name', true),
				'description' 								=>	$this->
																			 		input->
																			 		post('description', true),
				'event_date' 									=>	$this->
																			 		input->
																			 		post('event_date', true),
				'event_time' 									=>	$this->
																			 		input->
																			 		post('event_time', true),
				'ticket_category_id' 					=>	$this->
																			 		input->
																			 		post('category_id', true),
				'last_chaned_by' 							=>  $this->
        																	data['i_active_user_id']
			);

			/** Daten in Datenbank abspeichern */
			$a_return = $this->
							 	  Backend_Tickets_model->
									createTypeOfTicket($a_daten);


		  /**  */
			// Wenn Rückmeldung Datenbank gut ist, dann zuerst einen Redirect und ansonsten
			// die Forlmularseite erneut laden
			if($a_return['i_status'] == 0) {

				/** Messsages in Session schreiben für Redirect */
				$this->
				session->
				set_flashdata('message',
											'Ticket wurde erstellt');

				/** Redirect. damit Formular nich zweimal geschickt wird */
				redirect(site_url() . 'Admin/Backend_Tickets');

			// Aktion war fehlerhaft - Kein redirect
			} else {

				/** Messsages in Session schreiben für Redirect */
				$this->
				session->
				set_flashdata('message',
											'Erstellen fehlgeschlagen');

				/** Redirect. damit Formular nich zweimal geschickt wird */
				redirect(site_url() . 'Admin/Backend_Tickets');
			}

		/** Validerung ist fehlgeschlagen */
		} else {

			/** Status Message laden */
			$this->
			data['s_message'] = $this->
													session->
													flashdata('message');

			/** Formularmaske aufrufen  */
			$this->
			renderView('adminTickets_ticketErstellen',
								 $this->
								 data);
		}

	}



	/**
   * name:        updateTypeOfTicket
   *
   * Einen bestehenden Tickettyp aktualisieren
   *
   * @param int  		$i_id   			id des zu updatenden Datensatzes
   *
   * @author      Bruno Kirchmeier
   * @date        20181201
   *
   **/
	public function updateTypeOfTicket($i_id)
	{
		/** Type of tickets:
		 * Daten des upzudateten ticket typen laden */
		$a_type_of_tickets = 	$this->
													Backend_Tickets_model->
													getTypeOfTicket($i_id);

		/** View Daten aufbereiten */
		$this->
		data['i_id'] = $i_id;
		$this->
		data['s_name'] =
		$a_type_of_tickets['a_daten']
											[0]->name;
		$this->
		data['s_event_date'] =
		$a_type_of_tickets['a_daten']
											[0]->event_date;
		$this->
		data['s_event_time'] =
		$a_type_of_tickets['a_daten']
											[0]->event_time;
		$this->
		data['s_description'] =
		$a_type_of_tickets['a_daten']
											[0]->description;
		$this->
		data['i_ticket_category_id'] =
		$a_type_of_tickets['a_daten']
											[0]->ticket_category_id;
		$this->
		data['i_max_number_of_tickets'] =
		$a_type_of_tickets['a_daten']
											[0]->max_number_of_tickets;
		$this->
		data['s_start_sale_date'] =
		$a_type_of_tickets['a_daten']
											[0]->start_sale_date;
		$this->
		data['s_start_sale_time'] =
		$a_type_of_tickets['a_daten']
											[0]->start_sale_time;
		$this->
		data['s_end_sale_date'] =
		$a_type_of_tickets['a_daten']
											[0]->end_sale_date;
		$this->
		data['s_end_sale_time'] =
		$a_type_of_tickets['a_daten']
											[0]->end_sale_time;
		$this->
		data['i_booked_number_of_tickets'] =
		$a_type_of_tickets['a_daten']
											[0]->booked_number_of_tickets;


		/** Benutzereingaben Inputfelder Validierung
		 * Regeln setzen
		 *
		 * Name ist unique - jedoch nur wenn er neu gesetzt wird. Wenn name aus Post
		 * und Datenbank gleich ist, dann keine überprüfung, da sonst der eigene
		 * Datensatz gefunden wird */
		if($this->
			 input->
			 post('name', true) != $a_type_of_tickets['a_daten']
																							 [0]->name)
		{
				$this->
				form_validation->
				set_rules('name',
									'Anzeigename',
									'trim|required|is_unique[type_of_ticket.name]');
		}

		/** event_date */
		$json_arg = json_encode(array($this->input->post('end_sale_date'),
																	'Enddatum Billetverkauf')
																);
		$this->
		form_validation->
		set_rules('event_date',
							'Veranstaltungsdatum',
							'trim|required|valid_date');
		/** event_time*/
		$this->
		form_validation->
		set_rules('event_time',
							'Veranstaltungszeit',
							'trim|required|valid_time');
		/** Kategorie */
		$this->
		form_validation->
		set_rules('category_id',
							'Kategorie',
							'trim|required|search_key[ticket_category.id]');
		/** max_number_of_tickets*/
		$this->
		form_validation->
		set_rules('max_number_of_tickets',
							'Maximale Tickets für den Verkauf:',
							'trim|required|integer' );
		/** booked_number_of_tickets*/
		$this->
		form_validation->
		set_rules('booked_number_of_tickets',
							'Verkaufte Anzahl Tickets:',
							'trim|required|integer' );
		/** start_sale_date */
		$json_arg = json_encode(array($this->input->post('end_sale_date'),
																	'Enddatum Billetverkauf')
																);
		$this->
		form_validation->
		set_rules('start_sale_date',
							'Startdatum Billetverkauf:',
							'trim|required|callback_is_date_2_greater['. $json_arg . ']' );
		/** start_sale_time */
		$this->
		form_validation->
		set_rules('start_sale_time',
							'Startzeit Billetverkauf',
							'trim|required|valid_time');
		/** end_sale_date */
		$json_arg = json_encode(array($this->input->post('event_date'),
																	'Veranstaltungsdatum')
																);
		$this->
		form_validation->
		set_rules('end_sale_date',
							'Enddatum Billetverkauf',
							'trim|required|callback_is_date_2_greater['. $json_arg . ']' );
		/** event_date */
		$this->
		form_validation->
		set_rules('end_sale_time',
							'Endzeit Billetverkauf',
							'trim|required|valid_time');



		/** Benutzereingaben Inputfelder Validierung
		 *
		 * Validierung starten
		*/
		if ($this->
				form_validation->
				run($this) === TRUE )
		{
			/** Daten für Datenbank aufbereiten */
			$a_daten = array(
				'max_number_of_tickets' 			=> 	$this->
																					input->
																					post('max_number_of_tickets', true),
				'booked_number_of_tickets' 		=>	0,
				'start_sale_date' 						=>	$this->
																					input->
																					post('start_sale_date', true),
				'end_sale_date' 							=>	$this->
																					input->
																					post('end_sale_date', true),
				'start_sale_time' 						=>	$this->
																					input->
																					post('start_sale_time', true),
				'end_sale_time' 							=>	$this->
																					input->
																					post('end_sale_time', true),
				'name' 												=>	$this->
																			 		input->
																			 		post('name', true),
				'description' 								=>	$this->
																			 		input->
																			 		post('description', true),
				'event_date' 									=>	$this->
																			 		input->
																			 		post('event_date', true),
				'event_time' 									=>	$this->
																			 		input->
																			 		post('event_time', true),
				'ticket_category_id' 					=>	$this->
																			 		input->
																			 		post('category_id', true),
				'last_chaned_by' 							=>  $this->
        																	data['i_active_user_id']
			);


			/** Daten in Datenbank abspeichern */
			$a_return = $this->
							 	  Backend_Tickets_model->
									updateTypeOfTicket($i_id,
								 										 $a_daten);

			// Wenn Rückmeldung Datenbank gut ist, dann zuerst einen Redirect und ansonsten
			// die Forlmularseite erneut laden
			if($a_return['i_status'] == 0) {

				/** Messsages in Session schreiben für Redirect */
				$this->
				session->
				set_flashdata('message',
											'Update erfolgreich');

				/** Redirect. damit Formular nich zweimal geschickt wird */
				redirect(site_url() . 'Admin/Backend_Tickets');

			// Aktion war fehlerhaft - Kein redirect
			} else {

				/** Messsages in Session schreiben für Redirect */
				$this->
				session->
				set_flashdata('message',
											'Update fehlgeschlagen');

				/** Redirect. damit Formular nich zweimal geschickt wird */
				redirect(site_url() . 'Admin/Backend_Tickets');
			}

		/** Validerung ist fehlgeschlagen */
		} else {

			/** Status Message laden */
			$this->
			data['s_message'] = $this->
													session->
													flashdata('message');

			/** Formularmaske aufrufen  */
			$this->
			renderView('adminTickets_ticketUpdate',
								 $this->
								 data);
		}

	}



	/**
   * name:        deleteTypeOfTicket
   *
   * Einen bestehenden Tickettyp löschen
   *
   * @param int  		$i_id   			id des zu updatenden Datensatzes
   *
   * @author      Bruno Kirchmeier
   * @date        20181201
   *
   **/
	public function deleteTypeOfTicket($i_id)
	{
		/** Hilfsvariable */
		$a_hilfsarray = array();
		$a_return 		= array();
		$s_message 		= '';

		/** Daten aus Datenbank löschen */
		$a_return = $this->
								Backend_Tickets_model->
								deleteTypeOfTicket($i_id);

		/** Rückmeldung des Status
		*
		* Aktion war erfolgreich*/
		if($a_return['i_status'] == 0)
		{
			$s_message = "L&ouml;schen erfolgreich";

		/** Datenbankfehler */
		} else {
			$s_message = "Datenbankfehler. L&ouml;schen fehlerhaft";
		}

		/** Messsages in Session schreiben für Redirect */
		$this->
		session->
		set_flashdata('message',
									$s_message);

		/** Redirect. damit Formular nich zweimal geschickt wird */
		redirect(site_url() . 'Admin/Backend_Tickets');
	}



	/**
	 * name:        createTicketCategory
	 *
	 * Eine neue Ticketkategorie erstellen. Ticketkategorie ist ein bestandteil
	 * eines Tickettypes
	 *
	 * @author      Bruno Kirchmeier
	 * @date        20181201
	 *
	 **/
	 public function createTicketCategory()
	 {
			// Hilfsvariablen
			$s_message = '';

			/** Benutzereingaben Inputfelder Validierung
			 * Regeln setzen
			 *
			 * Name */
			 $this->
			 form_validation->
			 set_rules('name',
								 'Anzeigename',
								 'trim|required|is_unique[ticket_category.name]');
			 /** price_children*/
			 $this->
			 form_validation->
			 set_rules('price_children',
								 'Preis für Kinder:',
								 'trim|required|integer' );
			 /** price_adult*/
			 $this->
			 form_validation->
			 set_rules('price_adult',
								 'Preis für Erwachsene:',
								 'trim|required|integer' );


			 /** Benutzereingaben Inputfelder Validierung
	 		 *
	 		 * Validierung starten
	 		*/
	 		if ($this->
	  				form_validation->
	  				run($this) === TRUE )
	 		{
	 			/** Daten für Datenbank aufbereiten */
	 			$a_daten = array(
	 				'name' 						=> 	$this->
	 															input->
	 															post('name', true),
	 				'description' 		=>	$this->
	 															input->
	 															post('description', true),
	 				'price_children'	=>	$this->
	 															input->
	 															post('price_children', true),
	 				'price_adult' 		=>	$this->
	 															input->
	 															post('price_adult', true),
	 				'last_chaned_by' 	=>  $this->
	         											data['i_active_user_id']
	 			);


	 			/** Daten in Datenbank abspeichern */
				$a_return = $this->
										MY_Model->
										insert_with_validation('ticket_category',
																						$a_daten);

				/** Rückmeldung des Status
				*
				* Aktion war erfolgreich*/
				if($a_return['i_status'] == 0)
				{
					$s_message = "Kategorie wurde erstellt";

				/** Datenbankfehler */
				} else {
					$s_message = "Datenbankfehler - Kategorie konnte nicht erstellt werden";
				}

				/** Messsages in Session schreiben für Redirect */
				$this->
				session->
				set_flashdata('message',
											$s_message);

				/** Redirect. damit Formular nich zweimal geschickt wird */
				redirect(site_url() . 'Admin/Backend_Tickets');

	 		/** Form Validerung ist fehlgeschlagen */
	 		} else {

				/** Status Message laden */
				$this->
				data['s_message'] = "";

				/** Formularmaske aufrufen  */
				$this->
				renderView('adminTickets_categoryErstellen',
									 $this->
									 data);
			}

		}


		/**
	   * name:        updateTicketCategory
	   *
	   * Einen bestehenden Kategorietyp löschen
	   *
	   * @param int  		$i_id   			id des zu updatenden Datensatzes
	   *
	   * @author      Bruno Kirchmeier
	   * @date        20181201
	   *
	   **/
	 	public function updateTicketCategory($i_id)
	 	{
			// Hilfsvariablen
			$s_message = '';

			/** Type of tickets:
			 * Daten des upzudateten ticket typen laden */
			$a_ticket_category = 	$this->
														MY_Model->
														get('ticket_category',
																$i_id);

			/** View Daten aufbereiten */
			$this->
			data['i_id'] = $i_id;
			$this->
			data['s_name'] =
			$a_ticket_category[0]->name;
			$this->
			data['s_description'] =
			$a_ticket_category[0]->description;
			$this->
			data['i_price_children'] =
			$a_ticket_category[0]->price_children;
			$this->
			data['i_price_adult'] =
			$a_ticket_category[0]->price_adult;


			/** Benutzereingaben Inputfelder Validierung
			 * Regeln setzen
			 *
			 * Name ist unique - jedoch nur wenn er neu gesetzt wird. Wenn name aus Post
			 * und Datenbank gleich ist, dann keine überprüfung, da sonst der eigene
			 * Datensatz gefunden wird */
			if($this->
				 input->
				 post('name', true) != $a_ticket_category[0]->name)
			{
				$this->
				form_validation->
				set_rules('name',
									'Anzeigename',
									'trim|required|is_unique[ticket_category.name]');
			}

			/** price_children*/
			$this->
			form_validation->
			set_rules('price_children',
								'Preis für Kinder:',
								'trim|required|integer' );
			/** price_adult*/
			$this->
			form_validation->
			set_rules('price_adult',
								'Preis für Erwachsene:',
								'trim|required|integer' );


			/** Benutzereingaben Inputfelder Validierung
			 *
			 * Validierung starten
			*/
			if ($this->
					form_validation->
					run($this) === TRUE )
			{
				/** Daten für Datenbank aufbereiten */
				$a_daten = array(
					'name' 												=>	$this->
																				 		input->
																				 		post('name', true),
					'description' 								=>	$this->
																				 		input->
																				 		post('description', true),
					'price_children' 							=>	$this->
																				 		input->
																				 		post('price_children', true),
					'price_adult' 								=>	$this->
																				 		input->
																				 		post('price_adult', true),
					'last_chaned_by' 							=>  $this->
	        																	data['i_active_user_id']
				);

				/** Daten in Datenbank abspeichern */
				if($this->
					 MY_Model->
					 update('ticket_category',
									$i_id,
									$a_daten) )
				{
					$s_message = "Update erfolgreich";

				// Datenbankzugriff war fehlerhaft
				} else {

					$s_message = "Update Fehlerhaft";
				}

				/** Messsages in Session schreiben für Redirect */
				$this->
				session->
				set_flashdata('message',
											$s_message);

					/** Redirect. damit Formular nich zweimal geschickt wird */
					redirect(site_url() . 'Admin/Backend_Tickets');

		/** Validerung ist fehlgeschlagen */
		} else {

			/** Status Message laden */
			$this->
			data['s_message'] = $this->
													session->
													flashdata('message');

			/** Formularmaske aufrufen  */
			$this->
			renderView('adminTickets_categoryUpdate',
								 $this->
								 data);
    }

	}



	/**
	 * name:        deleteTicketCategory
	 *
	 * Einen bestehenden Kategorietyp löschen
	 *
	 * @param int  		$i_id   			id des zu updatenden Datensatzes
	 *
	 * @author      Bruno Kirchmeier
	 * @date        20181201
	 *
	 **/
	public function deleteTicketCategory($i_id)
	{
		/** Hilfsvariable */
		$a_return 		= array();
		$s_message 		= '';

		/** Daten aus Datenbank löschen */
		$a_return = $this->
								MY_Model->
								delete('ticket_category',
											 $i_id);

		/** Rückmeldung des Status
		*
		* Aktion war erfolgreich*/
		if($a_return['i_status'] == 0)
		{
			$s_message = "L&ouml;schen erfolgreich";

		/** Fremdschüsselbeziehung vorhanden */
		} else if ($a_return['i_status'] == 3) {

			$s_message = "Der Datensatz konnte nicht ge&ouml;scht werden, da er noch in Ticketypen verknüpft ist";

		/** Datenbankfehler */
		} else {
			$s_message = "Datenbankfehler. L&ouml;schen fehlerhaft";
		}

		/** Messsages in Session schreiben für Redirect */
		$this->
		session->
		set_flashdata('message',
									$s_message);

		/** Redirect. damit Formular nich zweimal geschickt wird */
		redirect(site_url() . 'Admin/Backend_Tickets');
	}




	/**
	 * name:        is_date_2_greater
	 *
	 * CAllback Funktion für Form Validation
	 * Mit dieser Funktion wird ein Input Feld überprüft ob es ein valides DAtum
	 * ist. Ebenso wird überprüft, ob das DAtum kleiner als jenes mit dem
	 * Zusatzargument ($json_arg) vorgegeben wird
	 *
	 * @param string  		$s_feld_value   	Datum 1 aus dem Impufeld gelesen, auf
	 *																			welches, die validation rule aufruft
	 * @param string  		$json_arg   			Ein statischer string, welcher innerhalb
	 *																			der Funktion gesplittet wird. Dieser string
	 *																			repräsentiert den Namen des Inoutfeldes
	 *																			mit dem Datum 2
	 *
	 * @author      Bruno Kirchmeier
	 * @date        20181201
	 *
	 **/
	public function is_date_2_greater($s_feld_value,
																		$json_arg)
	{
		// Funktionsargumente verarbeiten
		$a_arg 					= json_decode($json_arg);
		$s_date_1				= $s_feld_value;
		$s_date_2 			= $a_arg[0];
		$s_name_feld_2 	= $a_arg[1];

		// Umwandlung String zu Date Objekt
		$timestamp_1 = strtotime($s_date_1);
	  $timestamp_2 = strtotime($s_date_2);

		if ($timestamp_1 == false ||
				$timestamp_2 == false )
		{
			// Keine gültigen Datumsformate
			$this->
			form_validation->
			set_message('is_date_2_greater',
									'Das Datum im Feld {field} ist kein gültiges Datum');

			return false;
		}

		// Berechnung Zeitdifferenz in Skeunden
		$interval = $timestamp_2 - $timestamp_1;


		if($interval >= 0)
		{
			return true;

		// Datum 2 ist kleiner
		} else {

			// DAtum 2 ist kleiner
			$this->
			form_validation->
			set_message('is_date_2_greater',
									'Das Datum im Feld ' . $s_name_feld_2 .
									' muss grösser sein als jenes im Feld {field}');

			return false;
		}
	}







}

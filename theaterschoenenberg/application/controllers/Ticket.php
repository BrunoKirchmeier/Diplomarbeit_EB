<?php
class Ticket extends MY_Controller
{

	/** Javascript und Css Files Controller spezifisch laden
	*
	*  @var array mSeitenJs   Array mit den zu ladenden JavaScript Files
	* */
	public $mSeitenJs      = array('ticket');


	/**
	* Konstruktor
	*
	* */
	public function __construct()
	{
		parent::__construct();

		/** Models laden */
		$this->load->model('Ticket_model');

		/** Type of tickets:
		 * Alle erstellen Type of tickets aus der Datenbank laden */
		$a_type_of_tickets = 	$this->
													Ticket_model->
													getTypeOfTicketForSale();

		/** Type of tickets:
		 * Dropdown abfüllen:
		 * Views Daten für Dropdown aufbereiten als Objekt
		 *  */
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

		/** initial die DAten des ersten Dropdown Elementes mitgeben  */
		$this->
		data['a_tickets'] = isset($a_type_of_tickets['a_daten'][0])
											 ? $a_type_of_tickets['a_daten'][0]
											 : array();
	}



	/** Initial Funktion Anheige der Tickets die zum verkauf shtenen. Ebenso können hier
	* die Tickets in den Warenkorb gelegt werden
	*
	* */
	public function index()
	{
		/** Status Message laden */
		$this->
		data['s_message'] = $this->
												session->
												flashdata('message');

		/** View laden */
    $this->renderView('ticket_uebersicht',
											$this->
											data);
	}



	/**
	 * Funktion wird per Ajax abgefragt
	 *
	 * Detailinformation eines einzelnen Tickettypen abfragen zum einpflegen  im Client
	 *
	 * Die id des Tickets wird per Post eingelesen und als json ausgegeben
	 *
	 * Json Return Value:
	 * $a_return	Json Objekt mit folgenden Properties:
	 *						Datensatz aus Tabelle type_of_ticket
	 *
	 *
	 * @author      	Bruno Kirchmeier
	 * @date        	20181201
	 *
	 */
	public function getTicketTyp()
	{
		// Hilfsvariablen
		$a_return = array();

		/** Ajax Daten auslesen  */
		$i_id = $this->
						input->
						post('id', true);

		/** Einzelnes Ticket laden für Anzeige der Informationen */
	 	$a_type_of_tickets = 	$this->
												 	Ticket_model->
												 	getTypeOfTicketForSale($i_id);

		/** Ticket Detail als Ajax zurückgeben */
		exit(json_encode($a_type_of_tickets['a_daten'][0],
										 JSON_NUMERIC_CHECK) );
	}



	/**
	 * Funktion wird per Ajax abgefragt
	 *
	 * Mit dieser Funktion werden Tickets in den Warenkorb gelegt. Alle erstellten
	 * Tickets werden als Tickets in der tabelle tickets angelegt. Mittels des Feldes
	 * ticket_state wird bestimmt, ob das Ticket nur reserviert/blockiert ist oder
	 * bereits bestellt
	 *
	 * User muss eingeloogt sein, sonst kein Zugriff
	 *
	 * Steuern mittels Postvariablen von Ajax:
	 * i_type_of_ticket_id:				Tickettyp
	 * i_anz_child:								Anzahl Tickets mit Preis Kinder
	 * i_anz_adult:								Anzahl Tickets mit Preis Erwachsene
	 *
	 * Json Return Value:
	 * $a_return	Json Objekt mit folgenden Properties:
	 *						i_freie_tickets:		Rückmeldung wieveile Tickets noch frei sins
	 *						i_status						Statusmeldungen als Integerwerte. Definition
	 *																siehe sub Funktion
	 *																Ausnahme:	Wert 100 Auserhalb der Funktion - keine Berechtigung
	 *						b_success 					0:	Erwünschte Reaktion der Funktion ist eingetroffen
	 *																1:	Nicht erwartungsgemässes Ausführungsergebniss
	 *
	 * @author      	Bruno Kirchmeier
	 * @date        	20181201
	 *
	 */
	public function putInWarenkorb()
	{
		/** Ajax Daten auslesen */
		$i_tickettyp_id = $this->
											input->
											post('i_type_of_ticket_id', true);
		// Kinder Tickets
		$i_anz_child 		= $this->
											input->
											post('i_anz_child', true);
		// ERwachsesen Tickets
		$i_anz_adult 		= $this->
											input->
											post('i_anz_adult', true);

		// Aktion nur mit eingeloggtem USer möglich
		if($this->
			 data['i_active_user_id'] > -1)
		{
			// Kinder
			// Tickets blockieren
			$a_return = 	$this->
										Ticket_model->
										addReservierteTickets($i_tickettyp_id,
							 														$i_anz_child,
																					$i_anz_adult);

			/** Ticket Detail als Ajax zurückgeben */
			exit(json_encode($a_return,
											 JSON_NUMERIC_CHECK) );

		// Rückmeldung an Client
		} else {

			// Status aanpassen
			$a_return['i_freie_tickets']	= 0;
			$a_return['i_status']					= 100;
			$a_return['b_success'] 				= 0;

			/** Ticket Detail als Ajax zurückgeben */
			exit(json_encode($a_return,
											 JSON_NUMERIC_CHECK) );
		}

	}



	/**
	 * Mit dieser Funktion wird der gesamte Warenkorb des angemeldeten User geladen
	 * und der view: ticket_warenkorb mitübergeben
	 *
	 * User muss eingeloogt sein, sonst kein Zugriff
	 *
	 * Im Warenkorb sind alle Tickets mit dem Status 1 im Datenbankfeld ticket_state
	 *
	 *
	 * @author      	Bruno Kirchmeier
	 * @date        	20181201
	 *
	 */
	public function showWarenkorb()
	{
		// Aktion nur mit eingeloggtem USer möglich
		if($this->
			 data['i_active_user_id'] > -1)
		{
			// alle Tickets mit Satus reserviert laden und anzeigen
			$a_tickets = 	$this->
										Ticket_model->
										getTickets(array('ticket_state' 	=> 1,
																		 'users_id' 			=> $this->
																		 									 	 data['i_active_user_id'] ) );

			// Daten aufbereiten
			$this->
			data['a_tickets'] = $a_tickets;

			/** View laden */
			$this->renderView('ticket_warenkorb',
												$this->
												data);
		}

	}



	/**
	 * Funktion wird per Ajax abgefragt
	 *
	 * Mit dieser Funktion werden einzelne Tickets aus den Warenkorb gelöscht
	 *
	 * User muss eingeloogt sein, sonst kein Zugriff
	 *
	 * Steuern mittels Postvariablen von Ajax:
	 * id:				ticket
	 *
	 * Json Return Value:
	 * $a_return	Json Objekt mit folgenden Properties:
	 *						i_id:								Id des gelöschten DAtensatzes wieder zurückgben
	 *																damit asinchron im javascript das Element aus dem
	 *																Dom gelöscht werden kann
	 *						i_status						Statusmeldungen als Integerwerte. Definition
	 *																siehe sub Funktion
	 *																Ausnahme:	Wert 100 Auserhalb der Funktion - keine Berechtigung
	 *						b_success 					0:	Erwünschte Reaktion der Funktion ist eingetroffen
	 *																1:	Nicht erwartungsgemässes Ausführungsergebniss
	 *
	 * @author      	Bruno Kirchmeier
	 * @date        	20181201
	 *
	 */
	public function deleteTicket()
	{
		// Hilfsvariablen
		$a_return = array();

		/** Ajax Daten auslesen */
		$i_id = $this->
						input->
						post('id', true);

		// Aktion nur mit eingeloggtem USer möglich
		if($this->
			 data['i_active_user_id'] > -1)
		{
			// alle Tickets mit Satus reserviert laden und anzeigen
			$a_return = 	$this->
										MY_Model->
										delete('tickets',
													 $i_id);

		// Kein angemeldeter USer
		} else {

			$a_return['i_status']	  = 100;
		  $a_return['b_success']  = 0;
		}

		// Id wieder zurück geben, damit der Datensatz aus dem Dom gelöscht werden kann
		$a_return['i_id'] = $i_id;

		/** Ticket Detail als Ajax zurückgeben */
		exit(json_encode($a_return,
										 JSON_NUMERIC_CHECK) );
	}



	/**
	 * Mit dieser Funktion werden die Reservierten Tickets eines Users in den status
	 * definitiv gebucht  versetzt (SQL Feld: ticket_state auf Status = 0 setzen )
	 *
	 * User muss eingeloogt sein, sonst kein Zugriff
	 *
	 * Danach folgt ein redirect auf die Seite Tickets mit der MEssage des Status
	 *
	 * @author      	Bruno Kirchmeier
	 * @date        	20181201
	 *
	 */
	public function bookTicket()
	{
		// Bestellnummer erstellen - muss unoques sein
		$timestamp = strtotime(date('Y-m-d H:i:s'));

		// Hilfsvariablen
		$a_return 	= array();
		$s_message 	= '';
		$a_daten		= array('ticket_state' => 0,
												'order_number' => $timestamp);

		// Aktion nur mit eingeloggtem USer möglich
		if($this->
			 data['i_active_user_id'] > -1)
		{
			// alle Tickets des angemeldeten Users auf Status 2 updaten
			$a_return = $this->
									Ticket_model->
									bookTicket($a_daten);

			// Validierung der Message
			// Aktion war erfogreich
			if ($a_return['i_status'] == 0)
			{
				$s_message = "Tickets wurden gebucht";

			// Zu Viele Tickets vorhanden - überbuchung
			} else if($a_return['i_status'] == 4) {

				$s_message = "Keine Freien Tickets mehr, versuchen Sie es später nocheinamal";

			} else {

				$s_message = "Unbekannter Fehler";
			}

		// Kein User angemeldet - Keine BErechtigung
		} else {
			$s_message = 'Sie müssen angemeldet sein für diese Aktion';
		}

		// Daten aufbereiten
		$this->
		session->
		set_flashdata('message',
									$s_message);

		// Redirect. damit Formular nich zweimal geschickt wird
	 	redirect(site_url() . 'Ticket');
	}





}

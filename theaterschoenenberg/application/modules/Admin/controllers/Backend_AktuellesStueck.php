<?php
class Backend_AktuellesStueck extends Backend_Controller
{

	/** Javascript und Css Files Controller spezifisch laden
	*
	*  @var array mSeitenJs   Array mit den zu ladenden JavaScript Files
	* */
	public $mSeitenJs      = array('backend');


	/**
	* Konstruktor
	*
	* */
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Backend_AktuellesStueck_model');

	}




	/**
   * name:        index
   *
   * Backend zu Frontenseite: aktuelles Stück
	 * Mittels TinyMce werden Inhalte genneriert, welche im Frontend angezeigt werden
	 * Mit der Indexfunktion wird die MAske mit dem TinyMce Editor geladen
	 * anstehend sind
	 * Ebenso werden die Dropdowndaten für die aktuell vorhandenen Vorlage Views
	 * erstellt
   *
   * @author      Bruno Kirchmeier
   * @date        20181201
   *
   **/
	public function index()
	{
		/** Alle erstellen View Views aus der Datenbank laden */
		$a_Views = $this->
							 Backend_AktuellesStueck_model->
							 getView('AktuellesStueck');

		/** Dropdown abfüllen:
		 * Views Daten für Dropdown aufbereiten als Objekt */
		foreach ($a_Views['a_daten'] as $key => $value) {

			/** Objektelement einfügen */
			$this->
			data['o_dropwown'][$value->id] = $value->name;
		}

		/** Wenn Array leer ist, dann Index setzen */
		if(empty($this->
						 data['o_dropwown']) )
		{
			$this->
			data['o_dropwown'] = '';
		}

		/** Wenn diese Variable gestezt ist, dann View in Editor laden */
		$i_id = $this->
						input->
						post('ListAllViews');

		if ($i_id <> '') {

			$a_view_in_editor = $this->
													Backend_AktuellesStueck_model->
													getView('AktuellesStueck',
																	$i_id );

			if (isset($a_view_in_editor['a_daten']) )	{

				$this->
				data['s_html_content'] = $a_view_in_editor['a_daten']
																									[0]->
																									html_content;
			} else {

				$this->
				data['s_html_content'] = '';
			}

		} else {

			$this->
			data['s_html_content'] = '';
		}


		/** Status Message laden */
		$this->
		data['s_message'] = $this->
												session->
												flashdata('message');

		/** View laden  */
		$this->
		renderView('adminAktuellesStueck_uebersicht',
							 $this->
							 data);
	}




	/**
   * name:        saveTinymce
   *
   * Mit dieser Funktiion wird eine erstellt Vorlage in die DAtenbank abgespeichert
	 * Rückmeldung für die view über das Funktionsergebnis wird in einer
	 * flash Session Variablen zwischengespeichert und nach einem
   * redirectder Seite ausgegeben.
	 * Die Daten werden über Post von Input Elementen geladen
   *
   * @author      Bruno Kirchmeier
   * @date        20181201
   *
	 **/
	public function saveTinymce()
	{
		/** Tamplate in DAtenbank abspeichern */
		$a_return = $this->
								Backend_AktuellesStueck_model->
								insertView($this->
													 input->
												 	 post('tinymceContent'),
													 $this->
													 input->
												 	 post('tinymceViewName', true),
													 'AktuellesStueck-tinymce',
													 'AktuellesStueck');

		/** Wenn Name bereits vergeben wurde, so kann keine Neue Vorlage mit dem
		 * gleichen NAmen erstellt werden */
    if($a_return['i_status'] == 0)
		{
			$s_message = 'Vorlage wurde gespeichert';

		/** Wenn Name bereits vergeben wurde, so kann keine Neue Vorlage mit dem
			* gleichen NAmen erstellt werden */
		} else if($a_return['i_status'] == 1) {

			$s_message = 'Felder Vorlagename darf nicht leer sein. Abspeichern Fehlgeschlagen';

		/** Wenn Name bereits vergeben wurde, so kann keine Neue Vorlage mit dem
			* gleichen NAmen erstellt werden */
		} else if($a_return['i_status'] == 2) {

			$s_message = 'Der Vorlagename ist bereits vergeben. Abspeichern Fehlgeschlagen';

		/** Wenn Name bereits vergeben wurde, so kann keine Neue Vorlage mit dem
			* gleichen NAmen erstellt werden */
		} else if($a_return['i_status'] == 3) {

			$s_message = 'Unbekannter Fehler. Abspeichern Fehlgeschlagen';
		}

		/** Aktueller Meldung speichern */
		$this->
		session->
		set_flashdata('message',
									$s_message);

		/** Auf Index Seite umleiten, damit View neu geladen wird */
		redirect(site_url(). 'Admin/Backend_AktuellesStueck');
	}



	/**
   * name:        activateView
   *
	 * Funktion wird per Ajax abgefragt
   * Mit deser View wird eine andere Vorlage fürs Frontend aktivert. Damit sicher
	 * immer nur eine Vorlage aktiv ist, werden zuerst alle Vorlagen auf inaktiv
	 * gesetzt. Danach wird jende mit der per Ajax gesendeten id aktiviert
   *
	 * Die id des Tickets wird per Post eingelesen und als json ausgegeben
	 *
	 * Json Return Value:
	 * $a_return	Json Objekt mit folgenden Properties:
	 *						message als string
   *
   * @author      Bruno Kirchmeier
   * @date        20181201
   *
   **/
	public function activateView()
	{
			/** Formulardaten laden **/
			$a_postdaten = $this->
										 input->
										 post();

			/** Tamplate in Datenbank abspeichern */
			$a_return = $this->
									Backend_AktuellesStueck_model->
									activateView('AktuellesStueck',
															 $this->
															 input->
															 post('id', true) );

		 /** Wenn Name bereits vergeben wurde, so kann keine Neue Vorlage mit dem
			 * gleichen NAmen erstellt werden */
	   if($a_return['i_status'] == 0)
		 {
				$s_message = 'Vorlage wurde aktiviert';

		 } else {

			 $s_message = 'Unbekannter Fehler ist aufgetreten';
		 }

		 echo $s_message;

	}





}

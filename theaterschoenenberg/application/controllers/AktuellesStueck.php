<?php
class AktuellesStueck extends MY_Controller
{

	/** Javascript und Css Files Controller spezifisch laden
	*
	*  @var array mSeitenJs   Array mit den zu ladenden JavaScript Files
	* */
	public $mSeitenJs      = array();


	/**
	* Konstruktor
	*
	* */
	public function __construct()
	{
		parent::__construct();

		$this->load->model('AktuellesStueck_model');

	}



	/** Initial Funktion - aktive View aus DAtenbank laden und im Fronend anzeigen
	*
	* */
	public function index()
	{
		/** Alle erstellen Template Views aus der Datenbank laden */
		$a_view = $this->
							AktuellesStueck_model->
							getActiveTemplate();

    /** html Inhalt */
 		$this->
		data['s_aktuelles_stueck'] = $a_view['a_daten']
																				[0]->
																				html_content;

		/** View laden */
    $this->renderView('aktuellesStueck_uebersicht',
											$this->
											data);
	}


}

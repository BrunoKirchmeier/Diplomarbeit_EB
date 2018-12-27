<?php
class Welcome extends MY_Controller
{

	/** Javascript und Css Files Controller spezifisch laden
	*
	*  @var array mSeitenJs   Array mit den zu ladenden JavaScript Files
	* */
	public $mSeitenJs      = array('welcome');


	/** Initial Funktion - Ansicht nach Serverrequest
	*
	* */
	public function index()
	{
    $this->renderView('welcome_logo');
	}



}

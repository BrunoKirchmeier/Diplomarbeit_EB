<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 *
 */
class Backend_Auth extends Backend_Controller
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

		/** View laden */
		$this->
		renderView('Backend_auth_login',
							 $this->
							 data);
	}



	/**
	 * Login Funktion
	 *
	 * Der User welcher per Post übergeben wurde wird versucht einzuloggen
	 * Mit dieser Funktion wird auf Ion Auth Grungfunktionen zurückgegriffen
	 *
	 * Wenn Login fehlerhaft ist, so wird über den Konstruktor auf die Loginseite
	 * zurückgesprungen. Minimalberechtigung fürs BAckend is derzeit admin
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
			/** Login war erfolgreich  */
			 redirect(site_url() . 'Admin/Backend_AktuellesStueck');


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
			renderView('Backend_auth_login',
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

		redirect(site_url() . 'Admin/Backend_Auth');
	}




}

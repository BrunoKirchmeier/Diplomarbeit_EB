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
class AktuellesStueck_model extends CI_Model
{


	/**
   * name:        getActiveTemplate
   *
   * Für die Fronent Lasche aktuelles Stück die passende eingebettete view laden
	 *
	 * Viwes wurden durch Tynimce eerzeugt. In der Datenbank tabelle hat es mehrere
	 * Views, jedoch nur eine davon hat den Status aktiv.
	 * Diese aktive View wird geladen
   *
   * @param  string  $s_category_name    Filter, welcher beim erstellen im Tinymce statisch hinterlegt wird
	 *																		 Mittels diesem Filter können die Vorlagen für das
	 *																		 Dropdown im BAckend den einzelnen Frontend Laschen zugeortnet werden.
	 *																		 und es werden geladen, welche für die entsprechende Lasche gedacht sind
	 *																		 Das heisst: wird im BAckend unter aktuelles Stück eine Vorlage erstellt,
	 *																		 so wird Sie auch nur in diesem Dropdown geladen (später wird der gleiche mechanismus)
	 *																		 auch für die anderen LAschen eingesetzt werden
	 *
   * @return array  $a_return            Indexe:
   *                                     ['i_status']:   Fehlermeldungen. Jeweils nur einen Error in Form
   *                                                     eines Integers (Es könnten aber auch mehrere aktiv sein)
   *                                                     0:  Kein Fehler
   *                                                     1:  Variablenfehler
   *                                                     2:  Fremkey Fehler: Keine oder zuviele gefunden
   *
   *                                     ['b_success']:  Wenn alles erfolgreich war, dann True
   *                                     ['a_daten']:   html Content für View
   *
   * @author      Bruno Kirchmeier
   * @date        20181201
   *
   **/
	 public function getActiveTemplate($s_category_name = 'AktuellesStueck')
	 {
		 /** Hilfsvariablen */
		 $i_id_kategorie 				= -1;
		 $a_resultat						= array();
		 $a_return['a_daten']		= array();
		 $a_return['i_status']	= 0;
		 $a_return['b_success']	= false;


		 /** Zuerst bestimmen, welche id, die gesuchte Kategorie hat  */
	 	 $query = $this->
	 						db->
	 						get_where('sort_categories',
	 								  		array('name' => $s_category_name) );

	 	 /** Resulat von Query lesen */
	 	 $a_resultat = $query->result();

	 	 /** Prüfen ob Antwort ein Array ist */
	 	 if(is_array($a_resultat) )
	 	 {
	 	 	/** Prüfen on Anzahl genau 1 ist */
	 		 if(count($a_resultat) == 1)
	 		 {
	 			 $i_id_kategorie = $a_resultat[0]->
	 			 									 id;
	 		 }
	 	 }


		/** Aktives Templates mit dieser Kategorie suchen */
		if($i_id_kategorie <> -1)
		{
			/** Query - nur jene der gesuchten KAtegorie
       *          und mit Status "viewIsActive" */
      $this->
      db->
      where(array('sort_categories_id' => $i_id_kategorie,
                  'view_is_active'      => true ) );

      /** Query ausführen */
			$query = $this->
							 db->
							 get('embedded_view' );

			/** Resulat von Query lesen */
      $a_resultat = $query->result();

      /** Prüfen ob Antwort ein Array ist */
      if(is_array($a_resultat) )
      {
       /** Prüfen on Anzahl genau 1 ist */
        if(count($a_resultat) == 1)
        {
          $a_return['b_success']	= true;
          $a_return['a_daten']    = $a_resultat;
        }
      }
    }

	 /** Return Verarbeitung
	  * Keine gültige Kategorie gefunden*/
 	 if($i_id_kategorie == -1)
 	 {
 		 $a_return['i_status'] = 1;
 	 }

	 /** Return */
	 return $a_return;
	}




}

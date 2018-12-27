<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------------------------
 * Editor   : PhpStorm 2016.3.2
 * Date     : 01/05/2017
 * Time     : 6:29 AM
 * Authors  : Raymond L King Sr.
 * ----------------------------------------------------------------------------
 *
 * Class        MY_Form_validation
 *
 * @project     CI
 * @author      Raymond L King Sr.
 * @link        http://www.procoversfx.com
 * @copyright   Copyright (c) 2009 - 2017 Pro Covers FX, LLC.
 * @license     http://www.procoversfx.com/license
 * ----------------------------------------------------------------------------
 */

/**
 * Class MY_Form_validation
 *
 * Wiredesignz - Modular Extensions HMVC
 *
 * USEAGE:
 *
 * if ($this->form_validation->run($this) == FALSE)
 * {
 *
 * }
 * else
 * {
 *
 * }
 */
class MY_Form_validation extends CI_Form_validation
{

    /**
     * Class properties - public, private, protected and static.
     * ------------------------------------------------------------------------
     */

    // public $CI;

    // ------------------------------------------------------------------------

    /**
     * run ()
     * ---------------------------------------------------------------------------
     *
     * @param   string $module
     * @param   string $group
     * @return  bool
     */

    /**
      * Diese Funktion habe ich kopiert aus dem Internet
      *
      * */
    public function run($module = '', $group = '')
    {
        (is_object($module)) AND $this->CI = &$module;

        return parent::run($group);
    }


    /**
     * Diese Funktion habe ich kopiert aus dem Internet
     * Is Unique
     *
     * Check if the input value doesn't already exist
     * in the specified database field.
     *
     * @param   string  $str
     * @param   string  $field
     * @return  bool
    */
    public function is_unique($str, $field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);
        //return isset($this->CI->db)
        return is_object($this->CI->db)
            ? ($this->CI->db->limit(1)->get_where($table, array($field => $str))->num_rows() === 0)
            : FALSE;
    }



    /**
     * name:  search_key
     *
     * Datenbankfeld wird durchsucht, ob in der Datenbank der key genau einmal vorkommt
     * wenn nicht, dann fehlt fremdkey, wenn mer als 1 mal, dann keine eindeutige fremdkey
     * zuweisung möglich
     *
     * @param   string  $str      Inputfeld wird durch MY_Form_validation Klasse automatisch
     *                            den ersten Argument übergeben
     * @param   string  $field    Beim aufruf kann ein hartcodierter String übergeben werden
     *                            welcher hier gesplitet und verarbeitet werden kann in
     *                            Zusatargumente
     *                  $field = datenbank feld, welches durchsucht werden soll nach foreign key
     * @return  bool
    */
    public function search_key($str, $field)
    {
      // Datenbanktabelle und feld aus Argument extrahieren
      sscanf($field,
             '%[^.].%[^.]',
             $table, $field);

      // Datenbank Abfrage starten
      $o_return = is_object($this->
                            CI->
                            db)
                ? ($this->
                  CI->
                  db->
                  get_where($table,
                            array($field => $str))->
                            num_rows() === 1)
                : FALSE;

      // Auswertung Query
      if(is_object($this->
                   CI->
                   db) )
      {
        return $o_return;

      } else {

        return false;
      }
    }



    /**
     * name:  valid_date
     *
     * Prüfen ob FEld ein valides DAtum ist
     *
     * @param   string  $str      Inputfeld wird durch MY_Form_validation Klasse automatisch
     *                            den ersten Argument übergeben
     * @param   string  $s_format Beim aufruf kann ein hartcodierter String übergeben werden
     *                            welcher hier gesplitet und verarbeitet werden kann in
     *                            Zusatargumente
     *                  $s_format = datumsformat
     * @return  bool
    */
    public function valid_date($s_date,
  														 $s_format = 'Y-m-d')
    {
  		// Umwandlung String zu Date Objekt
  		$timestamp = strtotime($s_date);

  		/** Wenn Resultat flase ist, dann ist es kein gültiges Datum */
  		if ($timestamp == false)
  		{
  			return false;

  		} else {

  			return true;
  		}
  	}



    /**
     * name:  valid_time
     *
     * Prüfen ob FEld eine valides Zeit ist
     *
     * @param   string  $str      Inputfeld wird durch MY_Form_validation Klasse automatisch
     *                            den ersten Argument übergeben
     * @return  bool
    */
  	public function valid_time($str)
  	{
  	 	 //Assume $str SHOULD be entered as HH:MM
  		 $hh = 0;
  		 $mm = 0;
  		 $a_elemente = explode(':', $str);

  		 if(count($a_elemente) > 1)
  		 {
  			 $hh = $a_elemente[0];
  			 $mm = $a_elemente[1];
  		 }

  	 	 if (!is_numeric($hh) || !is_numeric($mm))
  	 	 {
  	 	     return FALSE;
  	 	 }
  	 	 else if ((int) $hh > 24 || (int) $mm > 59)
  	 	 {
  	 	     return FALSE;
  	 	 }
  	 	 else if (mktime((int) $hh, (int) $mm) === FALSE)
  	 	 {
  	 	     return FALSE;
  	 	 }

  	 	 return TRUE;
  	}



    // ------------------------------------------------------------------------

}   // End of MY_Form_validation Class.

/**
 * ----------------------------------------------------------------------------
 * Filename: MY_Form_validation.php
 * Location: ./application/libraries/MY_Form_validation.php
 * ----------------------------------------------------------------------------
 */

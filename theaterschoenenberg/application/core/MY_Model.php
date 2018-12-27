<?php
!defined('BASEPATH') and exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * CI_Mpdelerweitern
 * ----------------------------------------------------------
 *
 * @author		  Bruno Kirhmeier
 * @copyright	  Frei einsetzbar mit (c)-Hinweisen
 *			        you are free to use this code as long as you credit the
 *              author - and provide him with your improvements
 * @version		  1.1 | 2018-12-01
 */

class MY_Model extends CI_Model {

   /**
 	 * Veraaltete Funktion, wird bei einem Refresh durch get_where ersetzt
 	 *
 	 * @param int|string|bool $id
 	 *
 	 * @return CI_DB_result
 	 * @author Ben Edmunds
 	 */
 	public function get($s_table,
                      $value = '',
                      $colum = 'id')
 	{
    // Model updaten
    $this->db->from($s_table);

    // Nur wenn Value gesetzt die Auswahl einscgränken
    if($value <> '')
    {
      $this->db->where($colum,
                       $value);
    }

    $query = $this->db->get();

    // Rückgabe Anzahl gefeundene Datensätze
    return $query->result();
 	}



  /**
   * name:        get_where
   *
   * Modifizierte get Funktion der system Libary
   * Datenbankabfrage mit where eingrenzungen
   * Mittel error auslesen prüfen, ob nicht ein leeres Resultat aufgrund eines Error
   * zurückgegeben wird und das Ergebnis tatsächlich leer ist
   *
   * @param string          $s_table        Tabellennamet
   * @param array           $a_array        array of objekt mit where abfragen
   * @return array/boolean                  Wenn kein Error, rückgabe array of objekte
   *                                        ansonsten false
   *
   * @author      Bruno Kirchmeier
   * @date        20181201
   *
   *  */
 public function get_where($s_table,
                           $a_array = array())
 {
   // Model updaten
   $this->
   db->
   from($s_table);

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
 * name:        get_distinct
 *
 * Modifizierte get Funktion der system Libary
 * Datenbankabfrage mit distinct und where eingrenzungen
 * Mittel error auslesen prüfen, ob nicht ein leeres Resultat aufgrund eines Error
 * zurückgegeben wird und das Ergebnis tatsächlich leer ist
 *
 * @param string          $s_table        Tabellenname
 * @param string          $s_colum        Spalte, welche mit distinct eingegrenzt
 *                                        werden soll
 * @param array           $a_array        array of objekt mit where abfragen
 * @return array/boolean                  Wenn kein Error, rückgabe array of objekte
 *                                        ansonsten false
 *
 * @author      Bruno Kirchmeier
 * @date        20181201
 *
 *  */
public function get_distinct($s_table,
                             $s_colum,
                             $a_query = array())
{
  $this->
  db->
  distinct();

  $this->
  db->
  select($s_colum);

  $this->
  db->
  from($s_table);

  // Nur wenn Value gesetzt die Auswahl einscgränken
  if(! empty($a_query))
  {
    foreach ($a_query as $key => $value)
    {
      // Where Statments
      $this->
      db->
      where($key,
            $value);
    }
  }

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
 * name:        update_where
 *
 * Modifizierte update Funktion der system Libary
 * Datenbankupdate mit where eingrenzungen
 * Ale Returnwert wird die anzahl updateter row geliefert oder false, sofern
 * mittel error auslesen ein fehler erkannt wurde
 *
 * @param string          $s_table        Tabellenname
 * @param array           $a_daten        array of objekt mit Update daten
 * @param array           $a_array        array of objekt mit where abfragen
 * @return array/boolean                  Wenn kein Error, rückgabe array of objekte
 *                                        ansonsten false
 *
 * @author      Bruno Kirchmeier
 * @date        20181201
 *
 *  */
public function update_where($s_table,
                             $a_daten = array(),
                             $a_query = array())
{

 // Nur wenn Value gesetzt die Auswahl einscgränken
 if(! empty($a_query))
 {
   foreach ($a_query as $key => $value)
   {
     // Where Statments
     $this->
     db->
     where($key,
           $value);
   }
 }

 // Query ausführen
 $query =  $this->
           db->
           update($s_table,
                  $a_daten);

 // Prüfen ob kein Fehler passiert ist, damit in diesem Falle nicht von nicht
 // vorhandenen Daten ausgegangen wird
 // allfällige Fehlermeldungen zwischenspecihern
 $a_error =  $this->
             db->
             error();

// print_r($this->db->last_query() ); exit();
 // Kein Fehler entdeckt
 if($a_error['code'] == 0 &&
    $query)
 {
   // Rückgabe Anzahl veränderte Datensätze
   return   $this->
           db->
           affected_rows();

 // Aktion war fehlerhaft
 } else {

   return false;
 }

}



 /**
  * Veraaltete Funktion, wird bei einem Refresh durch update_where ersetzt
  *
  * @param int|string|bool $id
  *
  * @return CI_DB_result
  * @author Ben Edmunds
  */
 public function update($s_table,
                        $_id,
                        $a_data)
 {
   // Model updaten
   $this->db->where('id',
                    $_id);

   if($this->
      db->
      update($s_table,
             $a_data) )
   {
     return true;

   } else {

     return false;
   }

 }



/**
 * name:        update_where
 *
 * Modifizierte update Funktion der system Libary
 * Datenbankupdate mit where eingrenzungen
 * Ale Returnwert wird die anzahl updateter row geliefert oder false, sofern
 * mittel error auslesen ein fehler erkannt wurde
 *
 * @param string          $s_table     Tabellenname
 * @param integer         $i_id        Id des zu löschenden Datensatzes
 *
 * @return array  $a_return            Indexe:
 *                                     ['i_status']:   Fehlermeldungen. Jeweils nur einen Error in Form
 *                                                     eines Integers (Es könnten aber auch mehrere aktiv sein)
 *                                                     0:  Kein Fehler
 *                                                     1:  Datenbankfehler
 *                                                     2:  Kein zu löschender Datensatz gefunden
 *                                                     3:  Fremdschlüsselbeziehung verhindert löschen
 *
 *                                     ['b_success']:  Wenn alles erfolgreich war, dann True
 *
 * @author      Bruno Kirchmeier
 * @date        20181201
 *
 * */
public function delete($s_table,
                       $i_id)
{
  // Hilfsvariablen
  $a_error                = array();
  $a_return['i_status']	  = 0;
  $a_return['b_success']  = 0;

  // Model updaten
  $this->
  db->
  where('id',
        $i_id);

  // SQL AKtion ausführen und status speichern
  $this->
  db->
  delete($s_table);

  // allfällige Fehlermeldungen zwischenspecihern
  $a_error =  $this->
              db->
              error();

  // Delete bring auch true zurück, wenn eine nicht vorhandene id gelöscht werden
  // soll, sofern kein SQL Fehler entsanden ist. Daher zusatzkontrolle ob auch ein
  // Eintrag gelöscht wurde

  // Datenbank Fehlermeldung
  if ($a_error['code'] == 1451 )
  {
    $a_return['i_status'] = 3;

  // Kein zu löschender Datensatz gefunden
  } else if ($a_error['message'] <> '' ) {

    $a_return['i_status'] = 1;

  // Kein zu löschender Datensatz gefunden
  } else if ($this->
             db->
             affected_rows() <> 1)
  {
    $a_return['i_status'] = 2;

  // Aktion war erfolgreich
  } else {
    $a_return['b_success'] = 1;
  }

  // Return
  return $a_return;
}



 /**
  * name:        insert_with_validation
  *
  * Datenbank eintrag eines neuen Datensatzes. Die Funkton bietet mit
  * zusätzlichen Argumenten die Möglichkeit, Fremdschlüssel auf deren
  * existenz zu prüfen, sowie Einträge auf unique
  *
  * @param  string  $s_insert_table     Datenbanktabelle wo die Daten eingefügt werden
  * @param  array   $a_insert_data      Einzufügende Daten
  * @param  array   $a_foreign_keys     Multidimensionales Indexiertes Array
  *                                     Pro zu prüfender Schlüssel ist ein indexiertes Array
  *                                     mit drei Informationen einzutragen:
  *                                     [0]:  Tabelle, wo die Schlüssel gesucht werden sollen
  *                                     [1]:  Spaltenname auf der Datenbank, wo gescht werden muss
  *                                     [2]:  Wert, welcher mit den Insertdaten eingetragen werden
  *                                           möchte. Nur wen dieser Wert dort gefunden wird, darf
  *                                           der Eintarg erstellt werden
  * @param  array   $a_is_unique        Multidimensionales Indexiertes Array
  *                                     Pro zu prüfender Wert aus den Insertdaten jeweils
  *                                     ein indexiertes Array mit drei Informationen einzutragen:
  *                                     [0]:  Tabelle, welche nach unique durchsucht werden soll
  *                                     [1]:  Wert, welcher mit den Insertdaten eingetragen werden
  *                                           möchte. Nur wen dieser Wert dort gefunden wird, darf
  *                                           der Eintarg erstellt werden
  * @return array  $a_return            Indexe:
  *                                     ['i_status']:   Fehlermeldungen. Jeweils nur einen Error in Form
  *                                                     eines Integers (Es könnten aber auch mehrere aktiv sein)
  *                                                     0:  Kein Fehler
  *                                                     1:  Variablenfehler
  *                                                     2:  Fremkey Fehler: Keine oder zuviele gefunden
  *
  *                                     ['b_success']:  Wenn alles erfolgreich war, dann True
  *                                     ['i_id']:       Id des Primärschlüssel. Wenn kein erfolg, dann -1
  *
  * @author      Bruno Kirchmeier
  * @date        20181201
  *
  **/
 public function insert_with_validation($s_insert_table,
                                        $a_insert_data,
                                        $a_foreign_keys           = array(),
                                        $a_is_unique              = array())
 {
   /** Hilfsvariablen */
   $a_return['i_status']	= 0;
   $a_return['b_success'] = 0;
   $a_return['i_id']	    = -1;

   /** Prüfen ob Fremdschlüssel geprüfet werden sollen */
   if(is_array($a_foreign_keys) &&
      !empty($a_foreign_keys) )
   {
     // Prüfeungen werden verlangt
     foreach ($a_foreign_keys as $key) {
       // Variablen initialisieren
       $a_resultat = null;

       // Pro Array Element müssten drei Indexe gesetzt sein. Prüfen, damit keine
       // Fehler entstehen
       if(is_array($key) &&
          count($key) == 3)
       {
         // Das Array ist korrekt aufgebaut. Schlüssel überprüfen
         // Query aufbauen
         $query = $this->
                  db->
                  get_where($key[0],
                            array($key[1] => $key[2]) );

         // Prüfen ob Query nicht leer ist um FEhler zu vermeiden
         // Ebenso sollte nur genau ein Resultat gefunden werden, ansonsten
         // ist auf der Datenbank ein Durcheinander
         if($query == FALSE ||
            $query->num_rows() <> 1)
         {
           // Status 2: Fremkey Fehler
           $a_return['i_status'] = 2;
           return $a_return;
         }

       // Funktionsargument korrupt
       } else {
         // Status 1: Variablenfehler
         $a_return['i_status'] = 1;
         return $a_return;
       }
     }
   }

   /** allfällige überprüfung auf Unuique Felder */
   if(is_array($a_is_unique) &&
      !empty($a_is_unique) )
   {
     // Prüfeungen werden verlangt
     foreach ($a_is_unique as $key) {
       // Variablen initialisieren
       $a_resultat = null;

       // Pro Array Element müssten drei Indexe gesetzt sein. Prüfen, damit keine
       // Fehler entstehen
       if(is_array($key) &&
          count($key) == 2)
       {
         // Das Array ist korrekt aufgebaut. Schlüssel überprüfen
         // Query aufbauen
         $query = $this->
                  db->
                  get_where($s_insert_table,
                            array($key[0] => $key[1]) );

         // Prüfen ob Query nicht leer ist um FEhler zu vermeiden
         // Ebenso sollte kein Resultat gefunden worden sein. Ansonsten ist
         // der Wert nich unique
         if($query != FALSE ||
            $query->num_rows() > 0)
         {
           // Status 3: Wert ist nicht unique
           $a_return['i_status'] = 2;
           return $a_return;
         }

       // Funktionsargument korrupt
       } else {
         // Status 1: Variablenfehler
         $a_return['i_status'] = 1;
         return $a_return;
       }
     }
   }

   /**  Eintarg in Datenbank erstellen */
   $a_return['b_success'] = $this->
                            db->
                            insert($s_insert_table,
                                   $a_insert_data);

  /** Aktion war erfolgreich */
  if($a_return['b_success'])
  {
    /** Erstellte Id Prüfen */
    $a_return['i_id'] = $this->
                        db->
                        insert_id();

  /** Aktion ist hehlgeschlagen */
  }  else {

    // Status 3: Datenbankfehler
    $a_return['i_status'] = 3;
    return $a_return;
  }
    // Regulräe Verarbeitung beendet
    return $a_return;
  }



}

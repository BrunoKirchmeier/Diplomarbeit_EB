<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<!--  Bootstrap Container
      Jeweils für Desptop Ansicht links und REchts eine Spalte ohne Inhalt um
      den Content Kompakt einzumitten -->
<div class="ticket container">

  <h1 class="ticket-seitentitel">Warenkorb</h1>


  <?php
  foreach ($a_tickets as $i_index => $o_datensatz)
  {
    // Hilfsvariablen
    $s_adult_child  = $o_datensatz->is_child == 1
                    ? 'Kind'
                    : 'Erwachsener';

    $i_preis        = $o_datensatz->is_child == 1
                    ? $o_datensatz->price_children
                    : $o_datensatz->price_adult;

    // Erste Reihe Titel - Spalte 1:   Ticket Details
    // DAtum und Zeit Event
    echo '<div class="ticketWarenkorbContainer_' . $o_datensatz->id . ' row justify-content-center">';
    echo '<div class="col-sm-8 col-8"><strong>';
    echo '<p>';


    echo date('l, j F Y',
              strtotime($o_datensatz->
                        ticket_type_event_datetime));
    echo '<br>';
    echo date('G:i',
              strtotime($o_datensatz->
                        ticket_type_event_datetime)) . ' Uhr';
    echo '</strong><br>';

    // Kategoriename und Erwachsener oder Kind
    echo 'Kategorie: ' . $o_datensatz->category_name . ' ' . $s_adult_child;

    // Preis
    echo ' Preis: ' . $i_preis . ' Fr. <br>';

    // Description
    echo $o_datensatz->ticket_type_description;

    echo '</p></div>';


    // Erste Reihe Titel - Spalte 2:   Button löschen
    echo '<div class="col-sm-4 col-4">';

    // Button - View aktivieren
    $a_attributes = array('name'  				  => 'buttonDeleteFromWarenkorb',
                          'id'    				  => 'buttonDeleteFromWarenkorb_' . $o_datensatz->id,
                          'content' 	      => 'Löschen',
                          'class'				    => 'button button-link buttonDeleteFromWarenkorb',
                          'data-id'         => $o_datensatz->id
    );
    echo form_button($a_attributes);

    echo '</div></div>';
  }


  ?>


  <!--Bootstrap
      Reihe 6 / 1 Spalten:
      Button reservieren (nur sichtbar, wenn User angemeldet ist) -->
  <div class="row justify-content-center">

    <!-- Bootstrap Spalte 1 - Infos -->
    <div class="col-sm-8 col-12">
      <?php

      // Button zurück
      $url = '<a href="' .
              site_url() .
             'Ticket' .
              '"' .
             'class="button-link">zurück</a>';

      echo $url;

      // Button Tickets Reservieren
      $url = '<a href="' .
              site_url() .
             'Ticket/bookTicket' .
              '"' .
             'class="button-link">Tickets Reservieren</a>';

      echo $url;

      ?>
    </div>

  </div>

  <!--Bootstrap
      Reihe 6 / 1 Spalten:
      Button reservieren (nur sichtbar, wenn User angemeldet ist) -->
  <div class="ticket message
              row justify-content-center">

    <!-- Bootstrap Spalte 1 - Infos -->
    <div class="ticket ticketRes-message col-sm-8 col-12"
         id="message">

    </div>

  </div>


</div>

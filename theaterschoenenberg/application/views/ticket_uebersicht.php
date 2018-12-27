<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<!--  Bootstrap Container
      Jeweils für Desptop Ansicht links und REchts eine Spalte ohne Inhalt um
      den Content Kompakt einzumitten -->
<div class="ticket container">

  <h1 class="ticket-seitentitel">Tickets</h1>

  <!--Bootstrap
      Reihe 1 / 1 Spalten:
      Abschnitts Titel - Bestehende Reservationen -->
  <div class="ticket ticketRes
              row justify-content-center">

    <!-- Bootstrap Spalte 1 - Infos -->
    <div class="ticket ticketRes-titel col-sm-8 col-12">
      <h4>Ihre Reservationen</h4>
    </div>

  </div>


  <!--Bootstrap
      Reihe 2 / 2 Spalten:
      Aktive Reservationen auf Kundenlogin -->
  <div class="ticket ticketRes
              row justify-content-center">

    <?php
    ?>


    <!-- Bootstrap Spalte 1 - Infos -->
    <div class="ticket ticketRes-info col-sm-4 col-8">
    </div>

    <!-- Bootstrap Spalte 2 - Buttons -->
    <div class="ticket ticketRes-button col-sm-4 col-4">
    </div>

  </div>


  <!--Bootstrap
      Reihe 3 / 1 Spalten:
      Abschnitts Titel - Neue Reservationen -->
  <div class="ticket ticketRes
              row justify-content-center">

    <!-- Bootstrap Spalte 1 - Infos -->
    <div class="ticket ticketRes-titel col-sm-8 col-12">
      <h4>Zusätzliche Reservationen</h4>
    </div>

  </div>


  <!--Bootstrap
      Reihe 4 / 2 Spalten:
      Dropdowns mit Detailangaben -->
  <div class="ticket ticketRes
              row justify-content-center">

    <!-- Bootstrap Spalte 1 - Dropdowns -->
    <div class="ticket ticketRes-drop col-sm-3 col-12">
      <?php
      // Dropdown - Alle aktuellen Vies anzeigen
      echo form_dropdown('ticketRes-drop-list', $o_dropwown_tickets, '', 'id="ticketRes-drop-list"');
      ?>
    </div>

    <!-- Bootstrap Spalte 2 - Buttons -->
    <div class="ticket ticketRes-dropDetails col-sm-5 col-12">

      <p class="ticket ticketRes-dropDetails"
         id="ticketResDropEventDate">
         <?php

         if(is_object($a_tickets) )
         {
           echo date('l, j F Y',
                     strtotime($a_tickets->
                               event_datetime));

           echo '<br>' . date('G:i',
                              strtotime($a_tickets->
                                        event_datetime)) . ' Uhr';
         }
         ?>
      </p>

      <p class="ticket ticketRes-dropDetails"
         id="ticketResDropDescription">
         <?php

         if(is_object($a_tickets) )
         {
           echo $a_tickets->
                description;
         }
         ?>
      </p>

    </div>

  </div>

  <br><br>
  <!--Bootstrap
      Reihe 5 / 2 Spalten:
      Kategorien mit Preisen für diesen Tickettyp anzeigen
      HEader für folfende Foreach Schlaufe -->
  <div class="ticket ticketRes
              row justify-content-center">

    <!-- Bootstrap Spalte 1 - Kategorien -->
    <div class="ticket ticketRes-kat col-sm-4 col-5"
         id="ticketResKatId- <?= $a_tickets->ticket_category_id ?>">

      <h6>Preiskategorie</h6>
      <?php
      echo '<p class="ticket ticketRes-kat">Erwachsene</p>';
      echo '<p class="ticket ticketRes-kat">Kinder</p>';
      ?>
    </div>

    <!-- Bootstrap Spalte 2 - Preis pro Ticket -->
    <div class="ticket ticketRes-anz col-sm-2 col-4">

      <h6>Ticketpreis</h6>
      <?php
      echo '<p  class="ticket ticketRes-kat-priceAdult"
                id="ticketResKatPriceAdult"
                data-price="' . $a_tickets->ticket_category_price_adult . '">' .
                $a_tickets->ticket_category_price_adult . ' Fr.
            </p>';

      echo '<p  class="ticket ticketRes-kat-priceChild"
                id="ticketResKatPriceChild"
                data-price="' . $a_tickets->ticket_category_price_children . '">' .
                $a_tickets->ticket_category_price_children . ' Fr.
            </p>';
      ?>
    </div>


    <!-- Bootstrap Spalte 3 - Anzahl Tickets -->
    <div class="ticket ticketRes-anz col-sm-2 col-3">
      <h6>Anzahl</h6>
      <?php

      $a_attributes = array('type'  			=> 'number',
                            'name'  			=> 'ticketsResAdultAnz',
                            'id'    			=> 'ticketsResAdultAnz',
                            'class'				=> 'form-element',
                            'placeholder' => '***',
                            'min'         => 0,
                            'value'       => set_value('ticketsResAdultAnz',
                                                       0,
                                                       true ) );
      echo '<p>' . form_input($a_attributes) . '</p>';

      $a_attributes = array('type'  			=> 'number',
                            'name'  			=> 'ticketsResChildAnz',
                            'id'    			=> 'ticketsResChildAnz',
                            'class'				=> 'form-element',
                            'placeholder' => '***',
                            'min'         => 0,
                            'value'       => set_value('ticketsResChildAnz',
                                                       0,
                                                       true ) );
      echo '<p>' . form_input($a_attributes) . '</p>';

        ?>
    </div>

  </div>


  <!--Bootstrap
      Reihe 6 / 2 Spalten:
       -->
  <div class="ticket ticketRes
              row justify-content-center">

    <!-- Bootstrap Spalte 1 - Kategorien -->
    <div class="ticket ticketRes-total col-sm-4 col-5">Total:</div>

    <!-- Bootstrap Spalte 2 - Preis pro Ticket -->
    <div class="ticket ticketRes-total-price col-sm-2 col-4"
         style="border-top:solid"
         id="ticketResTotalPrice"></div>

    <!-- Bootstrap Spalte 3 - Preis pro Ticket -->
    <div class="ticket ticketRes-total-anz col-sm-2 col-3"
         style="border-top:solid"
         id="ticketResTotalAnz"></div>

  </div>





  <!--Bootstrap
      Reihe 6 / 1 Spalten:
      Button reservieren (nur sichtbar, wenn User angemeldet ist) -->
  <div class="ticket ticketRes
              row justify-content-center">

    <!-- Bootstrap Spalte 1 - Infos -->
    <div class="ticket ticketRes-button col-sm-8 col-12">
      <?php
      // Button - View aktivieren
      $a_attributes = array('name'  				  => 'ticketResButtonInWarenkorb',
                            'id'    				  => 'ticketResButtonInWarenkorb',
                            'content' 	      => 'In Warenkorb legen',
                            'class'				    => 'button button-link ticketRes-reservieren'
      );
      echo form_button($a_attributes);

      // Button zurück
      $url = '<a href="' .
              site_url() .
             'Ticket/showWarenkorb' .
              '"' .
             'class="button-link">Zum Warenkorb gehen</a>';

      if($i_active_user_id <> -1)
      {
        echo $url;
      }
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
         id="message"> <?= $s_message; ?>
    </div>

  </div>


</div>

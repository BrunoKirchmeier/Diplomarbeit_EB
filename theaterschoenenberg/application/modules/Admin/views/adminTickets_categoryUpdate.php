<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Gesamte Tickets Seite -->
<div>

  <!-- Bootstrap Container
       Buttons für alle Ansichten gleich anordnen-->
  <div class="container">

    <h2>Update Tickettyp</h2>

    <!-- Reihe: Anzeigename -->
    <div class="row">
      <!-- Spalte 1:  Label -->
      <div class="col-md-4">
        <?php
        // Formular für Template in editor laden öffnen
        $a_attributes = array('class' => 'form');
        echo form_open( base_url() . 'Admin/Backend_Tickets/updateTicketCategory/' . $i_id,
                       $a_attributes);

        echo form_label('Anzeigename: ', 'name');
        ?>
      </div>

      <!-- Spalte 2:  Eingabefeld -->
      <div class="col-md-8">
        <?php
        $a_attributes = array('type'  			=> 'text',
                              'name'  			=> 'name',
                              'id'    			=> 'name',
                              'placeholder' => '***',
                              'class'				=> 'form-element',
                              'value'       => set_value('name',
                                                         $s_name,
                                                         true ) );
        echo form_input($a_attributes);
        ?>
      </div>
    </div>


    <!-- Reihe: Beschreibung -->
    <div class="row">
      <!-- Spalte 1:  Label -->
      <div class="col-md-4">
        <?php
        echo form_label('Beschreibung: ', 'description');
        ?>
      </div>

      <!-- Spalte 2:  Eingabefeld -->
      <div class="col-md-8">
        <?php
        $a_attributes = array('type'  			=> 'text',
                              'name'  			=> 'description',
                              'id'    			=> 'description',
                              'class'				=> 'form-element',
                              'placeholder' => '***',
                              'value'       => set_value('description',
                                                         $s_description,
                                                         true ) );
        echo form_input($a_attributes);
        ?>
      </div>
    </div>


    <!-- Reihe: Preis für Kinder -->
    <div class="row">
      <!-- Spalte 1:  Label -->
      <div class="col-md-4">
        <?php
        echo form_label('Preis für Kinder: ', 'price_children');
        ?>
      </div>

      <!-- Spalte 2:  Eingabefeld -->
      <div class="col-md-8">
        <?php
        $a_attributes = array('type'  			=> 'number',
                              'name'  			=> 'price_children',
                              'id'    			=> 'price_children',
                              'class'				=> 'form-element',
                              'placeholder' => '***',
                              'value'       => set_value('price_children',
                                                         $i_price_children,
                                                         true ) );
        echo form_input($a_attributes);
        ?>
      </div>
    </div>

    <!-- Reihe: Max Anzahl Tickets für verkauf -->
    <div class="row">
      <!-- Spalte 1:  Label -->
      <div class="col-md-4">
        <?php
        echo form_label('Preis für Erwachsene: ', 'price_adult');
        ?>
      </div>

      <!-- Spalte 2:  Eingabefeld -->
      <div class="col-md-8">
        <?php
        $a_attributes = array('type'  			=> 'number',
                              'name'  			=> 'price_adult',
                              'id'    			=> 'price_adult',
                              'class'				=> 'form-element',
                              'placeholder' => '***',
                              'value'       => set_value('price_adult',
                                                         $i_price_adult,
                                                         true ) );
        echo form_input($a_attributes);
        ?>
      </div>
    </div>


    <!-- Reihe: Startzeit Bilettverkauf -->
    <div class="row">
      <!-- Spalte 1:  Label -->
      <div class="col-md-4">
        <?php
        // Formular absenden
        echo form_submit('ticket_category_update',
                         'Speichern',
                         'id="ticket_category_update"
                         class="button button-link"');

        // Button zurück
        $url = '<a href="' .
     			     site_url() .
               'Admin/Backend_Tickets' .
     			     '"' .
               'class="button-link ticket_category_update-zurueck">Zurück</a>';
        echo $url;

        // Formular schliessen
        echo form_close();
        ?>
      </div>

      <!-- Spalte 2:  Eingabefeld -->
      <div class="col-md-8">
        <?php
        echo validation_errors();
        ?>
        <p><?= $s_message; ?></p>

      </div>
    </div>

  </div>

</div>





<?php


?>

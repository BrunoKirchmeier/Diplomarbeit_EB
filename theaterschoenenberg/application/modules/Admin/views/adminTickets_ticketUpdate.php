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
        echo form_open( base_url() . 'Admin/Backend_Tickets/updateTypeOfTicket/' . $i_id,
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


    <!-- Reihe: Veranstalltungsdatum -->
    <div class="row">
      <!-- Spalte 1:  Label -->
      <div class="col-md-4">
        <?php
        echo form_label('Veranstaltungsdatum: ', 'event_date');
        ?>
      </div>

      <!-- Spalte 2:  Eingabefeld -->
      <div class="col-md-8">
        <?php
        $a_attributes = array('type'  			=> 'date',
                              'name'  			=> 'event_date',
                              'id'    			=> 'event_date',
                              'class'				=> 'form-element',
                              'value'       => set_value('event_date',
                                                         $s_event_date,
                                                         true ) );
        echo form_input($a_attributes);
        ?>
      </div>
    </div>


    <!-- Reihe: Veranstalltubgszeit -->
    <div class="row">
      <!-- Spalte 1:  Label -->
      <div class="col-md-4">
        <?php
        echo form_label('Veranstaltungszeit: ', 'event_time');
        ?>
      </div>

      <!-- Spalte 2:  Eingabefeld -->
      <div class="col-md-8">
        <?php
        $a_attributes = array('type'  			=> 'time',
                              'name'  			=> 'event_time',
                              'id'    			=> 'event_time',
                              'class'				=> 'form-element',
                              'value'       => set_value('event_time',
                                                         $s_event_time,
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


    <!-- Reihe: Kategorie zuordnen -->
    <div class="row">
      <!-- Spalte 1:  Label -->
      <div class="col-md-4">
        <?php
        echo form_label('Kategorie zuordnen: ', 'category_id');
        ?>
      </div>

      <!-- Spalte 2:  Eingabefeld -->
      <div class="col-md-8">
        <?php
        echo form_dropdown('category_id',
                           $o_dropwown_category,
                           $i_ticket_category_id,
                           'id="category_id"');
        ?>
      </div>
    </div>

    <!-- Reihe: Max Anzahl Tickets für verkauf -->
    <div class="row">
      <!-- Spalte 1:  Label -->
      <div class="col-md-4">
        <?php
        echo form_label('Maximale Tickets für den Verkauf: ', 'max_number_of_tickets');
        ?>
      </div>

      <!-- Spalte 2:  Eingabefeld -->
      <div class="col-md-8">
        <?php
        $a_attributes = array('type'  			=> 'number',
                              'name'  			=> 'max_number_of_tickets',
                              'id'    			=> 'max_number_of_tickets',
                              'class'				=> 'form-element',
                              'placeholder' => '***',
                              'value'       => set_value('max_number_of_tickets',
                                                         $i_max_number_of_tickets,
                                                         true ) );
        echo form_input($a_attributes);
        ?>
      </div>
    </div>


    <!-- Reihe: Verkaufte Anzhal Tickets-->
    <div class="row">
      <!-- Spalte 1:  Label -->
      <div class="col-md-4">
        <?php
        echo form_label('Verkaufte Anzahl Tickets: ', 'booked_number_of_tickets');
        ?>
      </div>

      <!-- Spalte 2:  Eingabefeld -->
      <div class="col-md-8">
        <?php
        $a_attributes = array('type'  			=> 'number',
                              'name'  			=> 'booked_number_of_tickets',
                              'id'    			=> 'booked_number_of_tickets',
                              'class'				=> 'form-element',
                              'placeholder' => '***',
                              'value'       => set_value('booked_number_of_tickets',
                                                         $i_booked_number_of_tickets,
                                                         true ) );
        echo form_input($a_attributes);
        ?>
      </div>
    </div>


    <!-- Reihe: Startdatum Bilettverkauf -->
    <div class="row">
      <!-- Spalte 1:  Label -->
      <div class="col-md-4">
        <?php
        echo form_label('Startdatum Billetverkauf: ', 'start_sale_date');
        ?>
      </div>

      <!-- Spalte 2:  Eingabefeld -->
      <div class="col-md-8">
        <?php
        $a_attributes = array('type'  			=> 'date',
                              'name'  			=> 'start_sale_date',
                              'id'    			=> 'start_sale_date',
                              'class'				=> 'form-element',
                              'value'       => set_value('start_sale_date',
                                                         $s_start_sale_date,
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
        echo form_label('Startzeit Billetverkauf: ', 'start_sale_time');
        ?>
      </div>

      <!-- Spalte 2:  Eingabefeld -->
      <div class="col-md-8">
        <?php
        $a_attributes = array('type'  			=> 'time',
                              'name'  			=> 'start_sale_time',
                              'id'    			=> 'start_sale_time',
                              'class'				=> 'form-element',
                              'value'       => set_value('start_sale_time',
                                                         $s_start_sale_time,
                                                         true ) );
        echo form_input($a_attributes);
        ?>
      </div>
    </div>


    <!-- Reihe: Enddatum Bilettverkauf -->
    <div class="row">
      <!-- Spalte 1:  Label -->
      <div class="col-md-4">
        <?php
        echo form_label('Enddatum Billetverkauf: ', 'end_sale_date');
        ?>
      </div>

      <!-- Spalte 2:  Eingabefeld -->
      <div class="col-md-8">
        <?php
        $a_attributes = array('type'  			=> 'date',
                              'name'  			=> 'end_sale_date',
                              'id'    			=> 'end_sale_date',
                              'class'				=> 'form-element',
                              'value'       => set_value('end_sale_date',
                                                         $s_end_sale_date,
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
        echo form_label('Endzeit Billetverkauf: ', 'end_sale_time');
        ?>
      </div>

      <!-- Spalte 2:  Eingabefeld -->
      <div class="col-md-8">
        <?php
        $a_attributes = array('type'  			=> 'time',
                              'name'  			=> 'end_sale_time',
                              'id'    			=> 'end_sale_time',
                              'class'				=> 'form-element',
                              'value'       => set_value('end_sale_time',
                                                         $s_end_sale_time,
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
        echo form_submit('ticket_typ_update',
                         'Speichern',
                         'id="ticket_typ_update"
                         class="button button-link"');

       // Button zurück
       $url = '<a href="' .
    			     site_url() .
              'Admin/Backend_Tickets' .
    			     '"' .
              'class="button-link editUser-zurueck">Zurück</a>';
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

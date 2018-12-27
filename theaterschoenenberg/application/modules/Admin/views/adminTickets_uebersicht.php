<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Gesamte Tickets Seite -->
<div class="tickets">

  <!-- Bootstrap Container
       Buttons für alle Ansichten gleich anordnen
       REIHE 1: Tickettypen-->
  <div class="tickets-block1 container">

    <h2>Tickets verwalten</h2>

    <!-- 3 Spaten erstellen -->
    <div class="tickets-block1 row">

      <!-- Spalte 1:  Dropdown -->
      <div class="tickets-block1 col-md-3">
        <?php
        // Dropdown - Alle aktuellen Vies anzeigen
        echo form_dropdown('list_type_of_tickets', $o_dropwown_tickets, '', 'id="list_type_of_tickets"');
        ?>
      </div>

      <!-- Spalte 2:  Buttons Update/Neu -->
      <div class="tickets-block1 col-md-3">
        <?php
        // Button Neu
        $url = '<a href="' .
               site_url() .
               'Admin/Backend_Tickets/createTypeOfTicket' .
               '"' .
               'class="button-link ticketType-neu">Neu</a>';
        echo $url;

        echo '<br>';
        if (is_array($o_dropwown_tickets))
        {
          // Button Update
          $a_attributes = array('name'  				  => 'ticketTypeUpdate',
                                'id'    				  => 'ticketTypeUpdate',
                                'content' 	      => 'Update',
                                'class'				    => 'button button-link'
          );
          echo form_button($a_attributes);
        }
        ?>
      </div>


      <!-- Spalte 3:  Button löschen-->
      <div class="tickets-block1 col-md-6">

        <?php
        if (is_array($o_dropwown_tickets))
        {
          // Button Löschen
          $a_attributes = array('name'  				  => 'ticketTypeDelete',
                                'id'    				  => 'ticketTypeDelete',
                                'content' 	      => 'Löschen',
                                'class'				    => 'button button-link'
          );
          echo form_button($a_attributes);
        }
        ?>
      </div>
    </div>
    <!-- Ende Reihe 1 -->
  </div>


  <!-- Bootstrap Container
       Buttons für alle Ansichten gleich anordnen
       REIHE 2: Ticket Kategorien-->
  <div class="tickets-block2 container">

    <h2>Ticket Kategorien verwalten</h2>

    <!-- 3 Spaten erstellen -->
    <div class="tickets-block2 row">


      <!-- Spalte 1:  Dropdown -->
      <div class="tickets-block2 col-md-3">
        <?php
        // Dropdown - Alle aktuellen Vies anzeigen
        echo form_dropdown('list_ticket_category', $o_dropwown_category, '', 'id="list_ticket_category"');
        ?>
      </div>

      <!-- Spalte 2:  Buttons Update/Neu -->
      <div class="tickets-block2 col-md-3">
        <?php
        // Button Neu
        $url = '<a href="' .
               site_url() .
               'Admin/Backend_Tickets/createTicketCategory' .
               '"' .
               'class="button-link ticketCategory-neu">Neu</a>';
        echo $url;

        echo '<br>';
        if (is_array($o_dropwown_category))
        {
          // Button Update
          $a_attributes = array('name'  				  => 'ticketCategoryUpdate',
                                'id'    				  => 'ticketCategoryUpdate',
                                'content' 	      => 'Update',
                                'class'				    => 'button button-link'
          );
          echo form_button($a_attributes);
        }
        ?>
      </div>


      <!-- Spalte 3:  Button löschen-->
      <div class="tickets-block2 col-md-6">

        <?php
        if (is_array($o_dropwown_category))
        {
          // Button Löschen
          $a_attributes = array('name'  				  => 'ticketCategoryDelete',
                                'id'    				  => 'ticketCategoryDelete',
                                'content' 	      => 'Löschen',
                                'class'				    => 'button button-link'
          );
          echo form_button($a_attributes);
        }
        ?>
      </div>
    </div>
  <!-- Ende Reihe 2 -->
  </div>


  <!-- Bootstrap Container
       Buttons für alle Ansichten gleich anordnen
       REIHE 3: Messages und Allgemeine Buttons-->
  <div class="tickets-block3 container">

    <!-- 1 Spate erstellen -->
    <div class="tickets-block3 row">

      <!-- Spalte 1:  Messages -->
      <div class="tickets-block3 col-md-12">
        <p id="messageBox"><?= $s_message; ?></p>
      </div>

    </div>
  </div>



</div>

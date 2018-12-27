<div class="auth">

  <!-- Bootstrap Container
       Mobile links / restliche Ansichten zentriert-->
  <div class="auth-createUser container">

    <div class="auth-createUser row">

      <!-- Bootstrap Spalte 1 -->
      <div class="auth-createUser col-md-2"></div>

      <!-- Bootstrap Spalte 2 (immer sichtbar) -->
      <div class="auth-createUser col-md-8 col-xs-12">

        <h1>Neuen Account Erstellen</h1>

      	<?php
      	// Hilfsvariablen
      	$a_form_attributes 	= array();
      	$a_hidden_fields		= array();

        // Formular öffnen
        $a_attributes = array('class' => 'form');
        echo form_open('Auth/createKunde',
                       $a_attributes);


        // Input Feld - Benutzername
        echo form_label('Benutzername: ', 'benutzername');
        $a_attributes = array('type'  			=> 'text',
                              'name'  			=> 'benutzername',
                              'id'    			=> 'benutzername',
                              'placeholder' => '***',
                              'class'				=> 'form-element'
        );
        $a_attributes['value'] 	= set_value('benutzername', $s_benutzername, true); // True mit escaping
        $a_attributes['class'] 	.= form_error('benutzername') != ''
                                ? ' fehler'
                                : '';
        echo form_input($a_attributes);


      	// Input Feld - Nachname
      	echo form_label('Nachname: ', 'nachname');
      	$a_attributes = array('type'  			=> 'text',
      	        							'name'  			=> 'nachname',
      	        							'id'    			=> 'nachname',
      												'placeholder' => '***',
      												'class'				=> 'form-element'
      	);
      	$a_attributes['value'] 	= set_value('nachname', $s_nachname, true); // True mit escaping
      	$a_attributes['class'] 	.= form_error('nachname') != ''
      													? ' fehler'
      													: '';
        echo form_input($a_attributes);

        // Input Feld - Vorname
        echo form_label('Vorname: ', 'vorname');
        $a_attributes = array('type'  				=> 'text',
      											  'name'  				=> 'vorname',
      											  'id'    				=> 'vorname',
      											  'placeholder' 	=> '***',
      											  'class'				  => 'form-element'
        );
        $a_attributes['value'] 	= set_value('vorname', $s_vorname, true); // True mit escaping
        $a_attributes['class'] 	.= form_error('vorname') != ''
      												  ? ' fehler'
      												  : '';
        echo form_input($a_attributes);

        // Input Feld - Email
        echo form_label('Email: ', 'email');
        $a_attributes = array('type'  				=> 'text',
      											  'name'  				=> 'email',
      											  'id'    				=> 'email',
      											  'placeholder' 	=> '***',
      											  'class'					=> 'form-element'
        );
        $a_attributes['value'] 	= set_value('email', $s_email, true); // True mit escaping
        $a_attributes['class'] 	.= form_error('email') != ''
      												  ? ' fehler'
      												  : '';
        echo form_input($a_attributes);

        // Input Feld - Passwort
        echo form_label('Passwort: ', 'password');
        $a_attributes = array('type'  				=> 'password',
      											  'name'  				=> 'password',
      											  'id'    				=> 'password',
      											  'placeholder' 	=> '***',
      											  'class'					=> 'form-element'
        );
        $a_attributes['value'] 	= set_value('password', '', true); // True mit escaping
        $a_attributes['class'] 	.= form_error('password') != ''
      												  ? ' fehler'
      												  : '';
        echo form_input($a_attributes);

        // Input Feld - Passwort Bestätigung
        echo form_label('Passwort Bestätigung: ', 'passwortConfirm');
        $a_attributes = array('type'  				=> 'password',
      											  'name'  				=> 'passwortConfirm',
      											  'id'    				=> 'passwortConfirm',
      											  'placeholder' 	=> '***',
      											  'class'					=> 'form-element'
        );
        $a_attributes['value'] 	= set_value('passwortConfirm', '', true); // True mit escaping
        $a_attributes['class'] 	.= form_error('passwortConfirm') != ''
      												 ? ' fehler'
      												  : '';
        echo form_input($a_attributes);

        // Button zurück
        $url = '<a href="' .
      			   site_url() .
               'Auth/login' .
      			   '"' .
               'class="button-link neuerAccount-zurueck">Zurück</a>';
        echo $url;


        // Formular öffnen
        echo form_submit('accountSpeichern', 'Account Speichern', 'id="kundenaccountErstellen" class="button button-link"');

        //Formular schliessen
        echo form_close();
        ?>

        <p><?= $message; ?></p>

      </div>

    <!-- Bootstrap Spalte 3 -->
    <div class="auth-createUser col-md-2"></div>
  </div>
</div>

<!-- Bootstrap Container
     Mobile links / restliche Ansichten zentriert-->
<div class="account-login container">

  <div class="account-login row">

    <!-- Bootstrap Spalte 1 -->
    <div class="account-login col-md-2"></div>

    <!-- Bootstrap Spalte 2 (immer sichtbar) -->
    <div class="account-login col-md-8 col-12">

      <h1>Login</h1>
      <br>

    	<?php
    	// Hilfsvariablen
    	$a_form_attributes 	= array();

      // Button zurück
    	$url = '<a href="' .
    				 site_url() .
             $this->
       			 session->
       			 userdata('s_letzte_url_relativ') . '"' .
             ' class="button-link login-zurueck">Zurück</a>';
      echo $url;

      // Button Accountdaten ändern
      if($i_active_user_id > -1) {
      	$url = '<a href="' .
      				 site_url() .
               'Auth/editKunde/' .
               $i_active_user_id . '"' .
               ' class="button-link login-accountAendern">Accountdaten ändern</a>';
        echo $url;
      }

      // Button Neuen accoount erstellen
    	$url = '<a href="' .
    				 site_url() .
    				 'Auth/createKunde' . '"' .
             ' class="button-link login-accountNeu">Neuen Account Erstellen</a>';
      echo $url;

      echo '<br><br>';

    	// Formular öffnen
      $a_attributes = array('class' => 'form');
    	echo form_open('Auth/login',
                     $a_attributes);

    	// Input Feld - Benutzername
    	echo form_label('Benutzername ', 'benutzername');
    	$a_attributes = array('type'  				=> 'text',
    												'name'  				=> 'benutzername',
    												'id'    				=> 'benutzername',
    												'placeholder' 	=> '***',
    												'class'					=> 'form-element'
    	);
      $a_attributes['value'] 	= set_value('benutzername', $s_benutzername, true); // True mit escaping
    	$a_attributes['class'] 	.= form_error('benutzername') != ''
    													? ' fehler'
    													: '';
    	echo form_input($a_attributes);

    	// Input Feld - Passwort
    	echo form_label('Passwort ', 'password');
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

    	// Formuladaten validieren und speichern in Db
    	echo form_submit('anmelden', 'Anmelden', 'id="anmelden" class="button button-link"');

      // Button passwort vergessen
      /*
      $url = '<a href="' .
             site_url() .
             'Auth/passwortVergessen' . '"' .
             ' class="button-link login-passwortVergessen">Passwort vergessen</a>';
      echo $url;
      */

    	//Formular schliessen
    	echo form_close();
    	?>

      <p><?= $message; ?></p>



    </div>

    <!-- Bootstrap Spalte 3 -->
    <div class="auth-login col-md-2"></div>

  </div>
</div>

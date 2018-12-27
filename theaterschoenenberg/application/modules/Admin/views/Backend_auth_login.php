<!-- Bootstrap Container
     Mobile links / restliche Ansichten zentriert-->
<div class="account-login container">

  <!-- Kein Responsiv Design -->
  <div class="account-login">

    <h1>Login</h1>
    <br>

  	<?php
  	// Hilfsvariablen
  	$a_form_attributes 	= array();


  	// Formular öffnen
    $a_attributes = array('class' => 'form');
  	echo form_open('Admin/Backend_Auth/login',
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


  	//Formular schliessen
  	echo form_close();
  	?>

    <p><?= $message; ?></p>

  </div>

</div>

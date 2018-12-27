<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>

tinymce.init({
  selector: "textarea#tinymceContent",
  width: "100%",
  height: 350,
  // content_css: "<?= base_url() ?>css/tiny.css",
  menubar: false,
  relative_urls: false,
  entity_encoding : "raw",
  statusbar:true,
  language:"de",
  plugins: [ "code"],
  block_formats: 'Paragraph=p;Header 1=h1;Header 2=h2;Header 3=h3',
  font_formats: 'Arial=arial,helvetica,sans-serif;Courier New=courier new,courier,monospace;AkrutiKndPadmini=Akpdmi-n',
  toolbar: "bold italic | bullist outdent indent | link unlink image hr | pastetext | undo redo | fontselect | fontsizeselect | code"
 });

</script>



<div class="admin-aktuellesStueck container">

  <!-- Reihe 1 -->
  <div class="admin-aktuellesStueck row">

    <!-- Reihe 1: Spalte 1 -->
    <div class="auth-createUser col-md-4">

      <?php
      echo '<br>';
      // Formular für Template in editor laden öffnen
      $a_attributes = array('class' => 'form');
      echo form_open( base_url() . 'Admin/Backend_AktuellesStueck/index',
                     $a_attributes);

       // Formular absenden
       echo form_submit('viewLaden',
                        'Seite in Editor laden',
                        'id="viewInEditorLaden"
                        class="button button-link"');

      // Button - View aktivieren
      $a_attributes = array('name'  				  => 'viewAktivieren',
                            'id'    				  => 'viewAktivieren',
                            'content' 	      => 'Als aktive Seite aktivieren',
                            'class'				    => 'button button-link'
      );
      echo form_button($a_attributes);
      ?>

    </div>

    <!-- Reihe 1: Spalte 2 -->
    <div class="auth-createUser col-md-6">

      <?php
      echo '<br>';

      // Dropdown - Alle aktuellen Views anzeigen
      echo form_dropdown('ListAllViews',
                         $o_dropwown,
                         '',
                         'id="ListAllViews"',
                         'class=""');

      // Formular schliessen
      echo form_close();

      echo '<br><br>';
      echo '<p id="aktuellesStueck-meldung">' . $s_message .  '</p>';
      ?>

    </div>


  <!-- End Row: Reihe 1 -->
  </div>



  <!-- Reihe 2-->
  <div class="admin-aktuellesStueck row">

    <!-- Reihe 2: Nur eine Spakte -->
    <div class="auth-createUser col-md-12">

      <?php
      // Formular öffnen
      $a_attributes = array('class' => 'form');
      echo form_open(site_url() . 'Admin/Backend_AktuellesStueck/saveTinymce',
                     $a_attributes);

       // Textartea für tinymce
      $a_attributes = array('name'  				=> 'tinymceContent',
      											'id'    				=> 'tinymceContent',
      											'class'					=> ''
      	);
      $a_attributes['value'] 	= set_value('tinymceContent',
                                          $s_html_content,
                                          false);
      echo form_textarea($a_attributes);

      ?>

    </div>

  <!-- End Row: Reihe 2 -->
  </div>


  <!-- Reihe 3 -->
  <div class="admin-aktuellesStueck row">

    <!-- Reihe 3: Spalte 1 -->
    <div class="auth-createUser col-md-2">

      <?php
      echo '<br>';
      // Input Feld - Vorlagename
      echo form_label('Vorlage Name', 'tinymceViewName');
      ?>
    </div>

    <!-- Reihe 3: Spalte 2 -->
    <div class="auth-createUser col-md-4">

      <?php
      echo '<br>';
      // Input Feld - Vorlagename
      $a_attributes = array('type'  				=> 'text',
                            'name'  				=> 'tinymceViewName',
                            'id'    				=> 'tinymceViewName',
                            'placeholder' 	=> '',
                            'class'				=> 'formElement'
      );
      $a_attributes['value'] 	= set_value('tinymceViewName', '', true); // True mit escaping
      $a_attributes['class'] 	.= form_error('tinymceViewName') != ''
                              ? ' fehler'
                              : '';
      echo form_input($a_attributes);
      ?>
    </div>

    <!-- Reihe 3: Spalte 3 -->
    <div class="auth-createUser col-md-6">

      <?php
      echo '<br>';
      // Formuladaten validieren und speichern in Db
      echo form_submit('speichern', 'Neu erstellen', 'id="speichern" class="button button-link"');

      //Formular schliessen
      echo form_close();

/*
      // Button update
      $url = '<a href="' .
             site_url() .
             'Admin/Backend_AktuellesStueck/update' .
             '"' .
             'class="button-link ticket_category_update-zurueck">Zurück</a>';
      echo $url;
*/
      ?>

    </div>


  <!-- End Row: Reihe 3 -->
  </div>

</div>

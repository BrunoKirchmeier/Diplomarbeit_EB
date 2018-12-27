$(document).ready(function(){

  /** Variablen Deklarationen */
  var s_aside_a_aktiv   = 'rgb(255, 255, 0)';
  var s_aside_a_inaktiv = 'white';

  /** Im Menü den aktiven Link gelb markieren */
  $('.aside a').css('color', s_aside_a_inaktiv);


  if(current_controller != '') {

    $('#' + current_controller).css('color', s_aside_a_aktiv);

  /** Defaultwert wenn kein Controller ist Welcmoe */
  } else {
      $('#Welcome').css('color', s_aside_a_aktiv);
  }


  // $('.asideAdmin-link a').first().css('color', s_aside_a_aktiv);
  // $('.asideAdmin').find('*').fadeIn(500).css('display', 'block');


  /** Menu Aside aufund zugklappen mittels Hamburger */
  $('.hamburger').click(function(){

    /** Klasse wechsel zwischen offen und geschlossen */
    $('.hamburger').toggleClass("open");

      /** Klasse oben nicht vorhanden
       * Prüfen ob Aside die Klasse open bereits besitz */
      if( $('.hamburger').hasClass('open')) {

        /* Dem Aside die Klasse open vergeben, damit dieser in eingeblendete
         * Stellung geht */
        $('.aside').addClass("open");

        /* Mittels Jquery Fade Funktionen folgende Aktionen durchführen:
         * 1. Die Aufklappgeschwindigkeit des Aside ist fix in der css klasse hinterlegt
         * 2. Das Sichtbar werden aller Elemente innerhalb des Aside über opacity
              und display steuern (Fade() Funktionen Jquery)
         * 3. Das Gesamte Main mittels opacity transparent gestalten */
        $('.aside').find('*').fadeIn(500).css('display', 'block');
        $('.main').find('*').fadeTo(500,  0.4);

        /* Hamburger - in X verwandelt */
        $('.hamburger').find('*').addClass("open");

        /** Klasse oben vorhanden
         * Prüfen ob Aside die Klasse open bereits besitz */
      } else {

        /* Dem Aside die Klasse open entfernen, damit dieser in ausgeblendete
         * Stellung geht */
        $('.aside').removeClass("open");

        /* Mittels Jquery Fade Funktionen folgende Aktionen durchführen:
         * 1. Die Zuklappgeschwindigkeit des Aside ist ohne transition
         * 2. Das Unsichtbar werden aller Elemente innerhalb des Aside über opacity
              und display steuern (Fade() Funktionen Jquery)
         * 3. Das Gesamte Main ohne transition opacity auf 1.0 */
        $('.aside').find('*').fadeOut(10);
        $('.main').find('*').css('opacity', '1.0');

        /* Hamburger - in balken zurück verwandelt */
        $('.hamburger').find('*').removeClass("open");
      }
  });




  /** Menu Aside aufund zugklappen wenn ein Link angeglickt wurde */
  $('.aside a').click(function(event){

    /** Aktivierten Link farblich markieren */
    // $('.aside a').css('color', s_aside_a_inaktiv);
    // $(this).css('color', s_aside_a_aktiv);


    /* Wenn ein Link angeklickt wurde, das Aside Menue schliessen */
    $('.aside').removeClass("open");

    /* Mittels Jquery Fade Funktionen folgende Aktionen durchführen:
     * 1. Die Zuklappgeschwindigkeit des Aside ist ohne transition
     * 2. Das Unsichtbar werden aller Elemente innerhalb des Aside über opacity
          und display steuern (Fade() Funktionen Jquery)
     * 3. Das Gesamte Main ohne transition opacity auf 1.0 */
    $('.aside').find('*').fadeOut(1);
    $('.main').find('*').css('opacity', '1.0');

    /* Hamburger - in balken zurück verwandelt */
    $('.hamburger').find('*').removeClass("open");

  });


  /** Spezialheader für Adminbereich ohne Responsivdesing und ohne Variablen
  * Aside */
  $('.asideAdmin a').click(function(){

    /** Aktivierten Link farblich markieren */
    $('.asideAdmin a').css('color', s_aside_a_inaktiv);
    $(this).css('color', s_aside_a_aktiv);

  });




});

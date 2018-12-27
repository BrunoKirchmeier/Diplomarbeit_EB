$(document).ready(function(){

  /** Variablen Deklarationen */
  var s_aside_a_aktiv   = 'rgb(255, 255, 0)';
  var s_aside_a_inaktiv = 'white';

  var i_screen_initial_width    = screen.width; // screen.width;    window.innerWidth;
  var i_screen_initial_height   = screen.height; // screen.height;  window.innerHeight;

  /** Auslesen ob der Browser safari ist. In diesem Fall verhalten sich beim
   * orientation landscape die height nicht gleich wie bei anderen browsern
   * chrome:  width ist immer in der horizontalen, egal ob portrait oder landscape.
   *          Das heisst width wird zu height
   * safari:  width ist immer der gleiche Wert (portrait breite),egal welche
   *          Landscape
   * */
  var userAgent = window.navigator.userAgent.toLowerCase();

  /** Aus der Variablen userAgent prüfen ob der RegexAusdruck für safari vorkommt */
  // var iphone = /iphone/i( userAgent );



  // alert(userAgent);
  // alert("INIT_breite: " + i_screen_initial_width + "   INIT_Höhe: " + i_screen_initial_height);






  /** Inital Container Bildrahmen setzen */
  $('.welcome-logo').css('height', i_screen_initial_height);


  /** Verändern des Bildschirmes wird dedektiert http://pioul.fr/cross-device-cross-browser-portrait-landscape-detection */
  $( window ).resize(function() {
    // alert("breite: " + i_screen_initial_width + "   Höhe: " + i_screen_initial_height);


  });



  /** Inital Container Bildrahmen setzen */
  // $('.welcome-logo').css('height', i_screen_initial_height);
});

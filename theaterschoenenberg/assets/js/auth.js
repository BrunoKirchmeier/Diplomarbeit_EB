$(document).ready(function(){

  /** Prüfen ob der Button Accountdaten ändern freigegeben werden darf
   * Dieser ist nur Aktiv, wenn ein Benutzer aktiv eingeloggt ist */
   // Steuerungvariable API PHP Server beschreiben für Ajax Call
   /*
   dataToSend = {
       tabelle: "admin",
       funktion: "lesen",
       parameter: "alle",
       spaltenName: "",
       spaltenWert: "",
       idZeile: -1,
       updateSpalte: [],
       updateWert: []
   }; */

   // Start Ajax Call
   $.ajax({
       url: site_url + 'auth/',
       type: 'POST',
       cache: false
       // dataType: 'json', // json
       // data: JSON.stringify(dataToSend),
       /*
       xhrFields: {
           withCredentials: true
       }, */
       // Anmeldedaten, damit ein Zugriff uaf den PHP Server erlaubt ist
       // headers: {'Authorization': 'Basic ' + btoa('admin:diplomarbeit')
       }
   })
   // Aufgabe war erfolgreich
   .done(function(data) {
       // Speichern in local Storage
       // Alle Admindaten vom Server laden und abspeichern, damit geschaut werden kann, ob der username bereits vergeben ist
       // localStorage.setItem("ajaxDatenAntwort", JSON.stringify(data) );
   })
   // Aufgabe war nicht erfolgreich
   .fail(function(){
       alert("Bitte versuchen Sie es erneut");
   });




  /**  */
  $('.login-accountAendern').click(function(){



  });



});

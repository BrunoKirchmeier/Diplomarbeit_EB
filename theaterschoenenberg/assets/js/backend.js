$(document).ready(function(){

  /** Variablen Deklarationen */

  /** Wenn Buttom gedrückt wird, so wird in der Datenbank bei allen anderen View mit der
   * gleichen KAtegorie das Flag aud inaktiv gesetzt und diese View auf aktiv */
  $('#viewAktivieren').click(function(){

     /** id und KAtegorie der zu aktiv setzenden view mitgeben */
     dataToSend = {
         id:        $('#ListAllViews').find(":selected").val()
     };

     // Start Ajax Call
     $.ajax({
         url: site_url + 'Admin/Backend_AktuellesStueck/activateView',
         type: 'POST',
         data: dataToSend,
     })
     // Aufgabe war erfolgreich
     .done(function(data) {

       $('#aktuellesStueck-meldung').html(data);
     })
     // Aufgabe war nicht erfolgreich
     .fail(function(error){

          console.log(error);
     });

  });

  // Wenn Buttom gedrückt wird, so wird in der Datenbank bei allen anderen View mit der
  // gleichen KAtegorie das Flag aud inaktiv gesetzt und diese View auf aktiv
  $('#contentToTinymceEditor').click(function(){

     //id und KAtegorie der zu aktiv setzenden view mitgeben
     dataToSend = {
         id:        $('#ListAllViews').find(":selected").val()
     };

     // Start Ajax Call
     $.ajax({
         url: site_url + 'Admin/Backend_AktuellesStueck/loadTemplateToTinymce',
         type: 'POST',
         data: dataToSend,
     })
     // Aufgabe war erfolgreich
     .done(function(data) {

       // Content in Editor ladenden
       $('#tinymceContent').html(data);

     })
     // Aufgabe war nicht erfolgreich
     .fail(function(error){

          console.log(error);
     });

  });



  // Wenn Buttom gedrückt wird, so wird von Codigniter die update Funktion mit der
  // id des aktiven Dropdown Elementes aufgerufen
  // wenn die updateview einmal geladen wurde mit den DAten, dann wird mit der
  // php fotmvaliadtion gearbeitet
  $('#ticketTypeUpdate').click(function(){

     // id der zu aktiv setzenden view mitgeben
     var id = $('#list_type_of_tickets').find(":selected").val();

     // Seite neu laden mit Funktion update und der entsprechenden id
     window.location = site_url +
                       'Admin/Backend_Tickets/updateTypeOfTicket/' +
                       id;
  });

  // Wenn Buttom gedrückt wird, so wird von Codigniter die delete Funktion mit der
  // id des aktiven Dropdown Elementes aufgerufen über Ajax
  // Per JAvascript wird die Resonse in die MEssage geschrieben
  $('#ticketTypeDelete').click(function(){

    // id der zu aktiv setzenden view mitgeben
    var id = $('#list_type_of_tickets').find(":selected").val();

    // Seite neu laden mit Funktion update und der entsprechenden id
    window.location = site_url +
                      'Admin/Backend_Tickets/deleteTypeOfTicket/' +
                      id;
  });




  // Wenn Buttom gedrückt wird, so wird von Codigniter die update Funktion mit der
  // id des aktiven Dropdown Elementes aufgerufen
  // wenn die updateview einmal geladen wurde mit den DAten, dann wird mit der
  // php fotmvaliadtion gearbeitet
  $('#ticketCategoryUpdate').click(function(){

     // id der zu aktiv setzenden view mitgeben
     var id = $('#list_ticket_category').find(":selected").val();

     // Seite neu laden mit Funktion update und der entsprechenden id
     window.location = site_url +
                       'Admin/Backend_Tickets/updateTicketCategory/' +
                       id;
  });

  // Wenn Buttom gedrückt wird, so wird von Codigniter die delete Funktion mit der
  // id des aktiven Dropdown Elementes aufgerufen über Ajax
  // Per JAvascript wird die Resonse in die MEssage geschrieben
  $('#ticketCategoryDelete').click(function(){

    // id der zu aktiv setzenden view mitgeben
    var id = $('#list_ticket_category').find(":selected").val();

    // Seite neu laden mit Funktion update und der entsprechenden id
    window.location = site_url +
                      'Admin/Backend_Tickets/deleteTicketCategory/' +
                      id;
  });








});

$(document).ready(function(){

  /** Variablen Deklarationen */
  $('#ticketResButtonInWarenkorb').attr('disabled','disabled');
  $('#ticketResButtonInWarenkorb').css('opacity', '0.5');

   const a_weekdays = ["Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag"];
   const a_months = ["Januar", "Februar", "März", "April", "Mai", "Juni",
                     "July", "August", "September", "Oktober", "November", "Dezember"];


  // Wenn Im Dropbdwon eine andere Vorstellung gewählt wird, so wird der folgeinhalt aktualisert
  $('#ticketRes-drop-list').change(function(){

    // id und KAtegorie der zu aktiv setzenden view mitgeben
    dataToSend = {
        id:        $('#ticketRes-drop-list').find(":selected").val()
    };

    // Start Ajax Call
    $.ajax({
        url: site_url + 'Ticket/getTicketTyp',
        type: 'POST',
        data: dataToSend,
    })
    // Aufgabe war erfolgreich
    .done(function(data) {

      // Daten von json string in ein Objekt umwandel
      var o_daten = JSON.parse(data);

      // HTML Feld Datum - Daten aufbereiten
      var o_date = new Date(o_daten.event_datetime);
      var s_datum = a_weekdays[o_date.getDay()] + ", " +
                    o_date.getDate() + " " +
                    a_months[o_date.getMonth()] + " "  +
                    o_date.getFullYear();

      var s_event_time = o_daten.event_time;
      var s_time = s_event_time.substring(s_event_time,
                                          s_event_time.length - 3) + " Uhr";

      // HTML Felder befüllen
      $('#ticketResDropEventDate').html(s_datum + '<br>' + s_time);
      $('#ticketResDropDescription').html(o_daten.description);

    })
    // Aufgabe war nicht erfolgreich
    .fail(function(error){

         console.log(error);
    });

  });



  // Wenn Im Dropbdwon eine andere Vorstellung gewählt wird, so wird der folgeinhalt aktualisert
  $('#ticketRes-drop-list, #ticketsResAdultAnz, #ticketsResChildAnz').change(function(){

    // id und KAtegorie der zu aktiv setzenden view mitgeben
    dataToSend = {
        id:        $('#ticketRes-drop-list').find(":selected").val()
    };

    // Start Ajax Call
    $.ajax({
        url: site_url + 'Ticket/getTicketTyp',
        type: 'POST',
        data: dataToSend,
    })
    // Aufgabe war erfolgreich
    .done(function(data) {

      // Daten von json string in ein Objekt umwandel
      var o_daten = JSON.parse(data);

      // HTML Felder befüllen
      $('#ticketRes-kat-id').html(o_daten.ticket_category_id);
      $('#ticketResKatPriceAdult').html(o_daten.ticket_category_price_adult + ' Fr.');
      $('#ticketResKatPriceChild').html(o_daten.ticket_category_price_children + ' Fr.');

      // HTml Elemente explizit in integer Umwandeln, damit keine berechnungsfejler passieren
      var i_anz_adult   = parseInt($('#ticketsResAdultAnz').val());
      var i_anz_child   = parseInt($('#ticketsResChildAnz').val());
      var i_price_adult = o_daten.ticket_category_price_adult;
      var i_price_child = o_daten.ticket_category_price_children ;

      var i_price_total = i_anz_adult * i_price_adult + i_anz_child * i_price_child;
      var i_anz_total = i_anz_adult + i_anz_child;

      $('#ticketResTotalPrice').html(i_price_total + ' Fr.');
      $('#ticketResTotalAnz').html(i_anz_total);


      // Wenn summe Anzahl grösser 0 ist, so wird reservatonsbuton aktiv
      if (i_anz_total > 0 )
      {
        $('#ticketResButtonInWarenkorb').removeAttr("disabled");
        $('#ticketResButtonInWarenkorb').css('opacity', '1.0');

      } else {

        $('#ticketResButtonInWarenkorb').attr('disabled','disabled');
        $('#ticketResButtonInWarenkorb').css('opacity', '0.5');
      }

    })
    // Aufgabe war nicht erfolgreich
    .fail(function(error){

         console.log(error);
    });

  });



  // Wenn Im Dropbdwon eine andere Vorstellung gewählt wird, so wird der folgeinhalt aktualisert
  $('#ticketRes-drop-list').change(function(){ // #ticketsResAdultAnz, #ticketsResChildAnz,

    // HTml Elemente explizit in integer Umwandeln, damit keine berechnungsfejler passieren
    var i_anz_adult   = parseInt($('#ticketsResAdultAnz').val());
    var i_anz_child   = parseInt($('#ticketsResChildAnz').val());
    var i_price_adult = parseInt( $('#ticketResKatPriceAdult').data('price') );
    var i_price_child = parseInt( $('#ticketResKatPriceChild').data('price') );

    var i_price_total = i_anz_adult * i_price_adult + i_anz_child * i_price_child;
    var i_anz_total = i_anz_adult + i_anz_child;

    $('#ticketResTotalPrice').html(i_price_total + ' Fr.');
    $('#ticketResTotalAnz').html(i_anz_total);


    // Wenn summe Anzahl grösser 0 ist, so wird reservatonsbuton aktiv
    if (i_anz_total > 0)
    {
      $('#ticketResButtonInWarenkorb').attr("disabled", false);

    } else {

      $('#ticketResButtonInWarenkorb').attr("disabled", true);
    }

  });



  // Wenn Im Dropbdwon eine andere Vorstellung gewählt wird, so wird der folgeinhalt aktualisert
  $('#ticketResButtonInWarenkorb').click(function(){

    // id und KAtegorie der zu aktiv setzenden view mitgeben
    dataToSend = {
        id:        $('#ticketRes-drop-list').find(":selected").val()
    };

    // Start Ajax Call
    // Ticketsinfos lesen, damit Fremdkey für Tickettyp Administration bekannt ist
    $.ajax({
        url: site_url + 'Ticket/getTicketTyp',
        type: 'POST',
        data: dataToSend,
    })
    // Aufgabe war erfolgreich
    .done(function(data) {

      // Daten von json string in ein Objekt umwandel
      var o_daten = JSON.parse(data);

      // id und KAtegorie der zu aktiv setzenden view mitgeben
      dataToSend = {
          i_type_of_ticket_id:  o_daten.id,
          i_anz_adult:          parseInt($('#ticketsResAdultAnz').val()),
          i_anz_child:          parseInt($('#ticketsResChildAnz').val())
      };


      // Start Ajax Call
      // Abfrage update WArenkorb
      $.ajax({
          url: site_url + 'Ticket/putInWarenkorb',
          type: 'POST',
          data: dataToSend,
      })
      // Aufgabe war erfolgreich
      .done(function(data) {

        // Daten von json string in ein Objekt umwandel
        var o_daten2 = JSON.parse(data);

        // Message zusammenstellen
        // Status:  Erfolgreich
        if(o_daten2.i_status == 0 ) {
          $('#message').html("Tickets wurden in den Warenkorb gelegt");

        // Status:  Nicht soviele Ticket mehr verfügabr
        } else if(o_daten2.i_status == 3) {
            $('#message').html("Tickets können nicht in den Warenkorb werden, da nur noch " + o_daten2.i_freie_tickets + " Tickets verfügbar sind");

        // Status:  Nicht soviele Ticket mehr verfügabr
      } else if(o_daten2.i_status == 100) {
            $('#message').html("Sie müssen sich zuerst anmelden bevor Sie Tickets reservieren können");

        //  Status:  Datenabnkfehler
        } else {
          $('#message').html("Unbekannter Fehler. Tickets könnten nicht in den Warenkorb gelegt werden");
        }

      })
      // Aufgabe war nicht erfolgreich
      .fail(function(error){

           console.log(error);
      });

   })
   // Aufgabe war nicht erfolgreich
   .fail(function(error){

        console.log(error);
   });

 });


  // Wenn Im Dropbdwon eine andere Vorstellung gewählt wird, so wird der folgeinhalt aktualisert
  $("button.buttonDeleteFromWarenkorb").click(function(){

    // Id des zu löschenden Datensatzes auslesen
    dataToSend = {
        id:        $(this).data("id")
    };

    // Ajax Call an server - datensat löschen
    $.ajax({
        url: site_url + 'Ticket/deleteTicket',
        type: 'POST',
        data: dataToSend,
    })
    // Aufgabe war erfolgreich
    .done(function(data) {

      // Daten von json string in ein Objekt umwandel
      var o_daten = JSON.parse(data);

      // Message zusammenstellen
      // Status:  Erfolgreich
      if(o_daten.i_status == 0 ) {

        // Message auf Bildschrirm
        $('#message').html("Ticket aus warenkorb entfernt");
        // Element im Client aus deoom entfernen
        $('.ticketWarenkorbContainer_' + o_daten.i_id).empty();

      // Status:  Nicht soviele Ticket mehr verfügabr
      } else if(o_daten.i_status == 100) {
          $('#message').html("Sie müssen sich zuerst anmelden bevor Sie Tickets reservieren können");

      //  Status:  Datenabnkfehler
      } else {
        $('#message').html("Unbekannter Fehler. Tickets könnten nicht in den Warenkorb gelegt werden");
      }

    })
    // Aufgabe war nicht erfolgreich
    .fail(function(error){

         console.log(error);
    });

  });











});

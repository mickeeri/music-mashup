$(document).ready(function () {

    // Shows spinner when loading list.
    $('.list-link').click(function () {
        $(this).parent().parent().append('<img class="spinner" src="images/spinner.gif" />');
    });

    // Removes error message if empty.
    var errorDiv = $('.error-div');
    if ($(errorDiv).text() === "") {
        $(errorDiv).remove();
    }

    if ($('.success-div').text() === "") {
        $('.success-div').remove();
    }

    $('.close-message-icon').click(function () {
        $('.success-div').fadeOut();
    });

    // Makes ajax request every 10 seconds to see if user is online or not.
    // Shows error message if offline.
    setInterval(function () {
        $.ajax({
              type: "HEAD",
              url: document.location.pathname + "?param=" + new Date(),
              error: function() {
                  $('.error-div').remove();
                  console.log("Error");
                  $('main').prepend('<div class="error error-div">Det verkar som att du har tappat din uppkoppling.</div>');
                  // Keep user from trying to send created list to server.
                  if ($("#save-list-button") != "") {
                      $("#save-list-button").attr("disabled", true);
                  }

              },
              success: function() {
                  $('.error-div').remove();
                  if ($("#save-list-button") != "") {
                      $("#save-list-button").attr("disabled", false);
                  }
              }
           });
    }, 10000);
});

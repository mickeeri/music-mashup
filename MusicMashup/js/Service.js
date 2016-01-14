$(document).ready(function () {

    $('.list-link').click(function () {

        $(this).parent().parent().append('<img class="spinner" src="images/spinner.gif" />');
    });

    // Removes error message if empty.
    var errorDiv = $('.error-div');
    if ($(errorDiv).text() === "") {
        $(errorDiv).remove();
    }


    // Makes ajax request every 10 seconds to see if user is online or not.
    setInterval(function () {
        $.ajax({
              type: "HEAD",
              url: document.location.pathname + "?param=" + new Date(),
              error: function() {

                  $('.error-div').remove();
                  console.log("Error");
                  //var messageDiv = $('#main-message');
                  //var messageDiv = '<div class="error error-div">Det verkar som att du har tappat din uppkoppling</div>';
                  $('main').prepend('<div class="error error-div">Det verkar som att du har tappat din uppkoppling.</div>');
                  //var messageDiv = $('.error-div');
                  //messageDiv.empty();
                  //$(messageDiv).append('<p>Det verkar som att du har tappat din uppkoppling.</p>');
                  // TODO disable everything.
              },
              success: function() {
                  $('.error-div').remove();
                  console.log("Success");
                  // Do nothing.
              }
           });
    }, 10000);

});

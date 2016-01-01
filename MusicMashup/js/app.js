"use strict";

$(function() {

    // Get the form.
    var form = $('#album-form');

    // Get the messages div.
    var formMessages = $('#form-messages');

    // Set up an event listener for the contact form.
    $(form).submit(function(e) {
        // Stop the browser from submitting the form.
        e.preventDefault();

        // Serialize the form data.
        var artist = $("#artist").val();

        //console.log(artist);

        var url = "http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist="+artist+"&" +
            "lang=sv&api_key=c3ec843b6b80acb1bf180a874a95cf59&format=json";

        var albumurl = "http://ws.audioscrobbler.com/2.0/?method=album.search&album="+artist+"&limit=6" +
            "&api_key=c3ec843b6b80acb1bf180a874a95cf59&format=json";

        $.getJSON(albumurl, function(data){

            var albumMatches = data.results.albummatches.album;

            $("#results").empty();

            albumMatches.forEach(function(album){

                var imageSrc = album.image[2]["#text"];


                $("#results").append("<a href='"+album.url+"' target='_blank'><li>"+album.name+" <b>by</b> "+album.artist+"</a></li>");
                $("#results").append("<img src='"+imageSrc+"' />");
            });

        });

    });
});

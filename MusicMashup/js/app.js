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

        var albumurl = "http://ws.audioscrobbler.com/2.0/?method=album.search&album="+artist+"&limit=3" +
            "&api_key=c3ec843b6b80acb1bf180a874a95cf59&format=json";

        $.getJSON(albumurl, function(data){

            var albumMatches = data.results.albummatches.album;

            var resultsDiv = $("#results");

            resultsDiv.empty();

            if (albumMatches.length === 0) {
                resultsDiv.append("" +
                    "<h4>Search results</h4>" +
                    "<p>No albums found</p>");
            } else {
                resultsDiv.append("" +
                    "<div class='col m6'>" +
                        "<h4>Search results</h4>" +
                        "<ul id='result-list' class='collection'></ul>" +
                    "</div>");

                albumMatches.forEach(function(album){
                    var imageSrc = album.image[2]["#text"];

                    $("#result-list").append(
                        "<li class='collection-item avatar'>" +
                        "<img src='"+imageSrc+"' alt='Album cover art' class='circle'>" +
                        "<span class='title'>"+album.name+"</span>" +
                        "<p>"+album.artist+"</p>" +
                        "</li>"
                    );
                });

                if ($(".album-list").length === 0) {

                    $(".content").append("<div class='album-list'>" +
                        "<h4>Top albums</h4>" +
                        "<ul id='top-albums' class='collection'></ul>" +
                        "</div>");
                }

                $(".collection-item").click(function(){

                    var albumListItem = this;


                    $("#top-albums").append(albumListItem);

                    console.log($("#top-albums").children().length);
                });
            }
        });

    });
});

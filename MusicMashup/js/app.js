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

                    // Presentation of search result.
                    $("#result-list").append(
                        "<li class='collection-item avatar'>" +
                        "<img src='"+imageSrc+"' alt='Album cover art' class='circle'>" +
                        "<span class='title'>"+album.name+"</span>" +
                        "<p class='artist'>"+album.artist+"</p>" +
                        "</li>"
                    );
                });

                if ($(".album-list").length === 0) {

                    $(".content").append("<div class='album-list'>" +
                        "<h4>Top albums</h4>" +
                        "<ul id='top-albums' class='collection'></ul>" +
                        "</div>");
                }

                var albums = [];

                $(".collection-item").click(function(){

                    var listLength = $("#top-albums").children().length;
                    var albumNumber = 3 - listLength;
                    var albumListItem = this;

                    var albumName = $(albumListItem).children(".title").text();
                    var artist = $(albumListItem).children(".artist").text();

                    //console.log(artist);

                    var listItemClone = $(albumListItem).clone();

                    listItemClone.appendTo("#top-albums");

                    $(listItemClone).append("<span class='album-order-number'>"+albumNumber+"</span>");

                    var album = {
                        name: albumName
                    };

                    //var json = JSON.stringify(album);

                    //$.post("models/test.php", { json: JSON.stringify(album) }, function(response){
                    //    console.log(response);
                    //});

                    $.ajax({
                        type: 'POST',
                        url: 'index.php',
                        data: {json: JSON.stringify(album)},
                        dataType: 'json'
                    }).done(function(response){
                        console.log("done");
                        console.log(response);
                    }).fail(function(data){
                        console.log("fail");
                        console.log(data);
                    });


                    //$(".content").append("" +
                    //    "<form id='top-list-form' method='post' action='models/test.php'>" +
                    //        "<input type='text' id='name' name='name' value='"+album.name+"'>" +
                    //        "<button type='submit'>Send</button>" +
                    //    "</form>");
                    //
                    //
                    //
                    //$("#top-list-form").submit(function(e){
                    //    e.preventDefault();
                    //
                    //    var formData = $("#top-list-form").serialize();
                    //
                    //    console.log(formData);
                    //
                    //    $.ajax({
                    //        type: 'POST',
                    //        url: $("#top-list-form").attr("action"),
                    //        data: formData
                    //    }).done(function(response){
                    //        console.log(response);
                    //    });
                    //});
                });
            }
        });

    });
});

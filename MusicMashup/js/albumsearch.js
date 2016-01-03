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
                        "<li class='collection-item avatar top-album'>" +
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

                //var albums = [];

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


                    // TODO: Egen funktion.
                    if (albumNumber === 3) {

                        var albums = $("#top-albums").children();
                        $(".album-list").append("<button id='save-list-button'>Save</button>");

                        $("#save-list-button").click(function () {

                            console.log(albums);

                            $(albums).each(function () {

                                //console.log($(this).find(".title").text());

                                var name = $(this).find(".title").text();
                                var artist = $(this).find(".artist").text();
                                var order = $(this).find(".album-order-number").text();



                                var album = {
                                    name: name,
                                    artist: artist,
                                    order: order
                                };

                                console.log(album);

                                $.post("models/AjaxHandler.php", album).done(function(data){
                                    console.log("Done");
                                    console.log(data);
                                }).fail(function(data){
                                    console.log("fail");
                                    console.log(data);
                                });
                            });
                        });
                    }
                });
            }
        }).done(function(response){
            console.log("Done: " + response)
        }).fail(function (response) {
            console.log("Fail: " + response);
        });

    });


});



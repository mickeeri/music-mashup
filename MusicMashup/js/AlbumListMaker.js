"use strict";

function init(){
    AlbumListMaker = new AlbumListMaker();
}

var AlbumListMaker = function(){

    // TODO: Gör så att markören hamnar i input-fältet. t.ex. när man lagt in år och sånt.

    var createListForm = $("#create-list-form");
    var albumSearchForm = $("#album-form");

    this.source = "";
    this.year = "";
    this.link = "";
    this.topAlbums = [];

    // How many albums that is to be in top-list.
    this.numberOfAlbums = 3;

    $(document).ready(function() {
        $('select').material_select();
    });

    // Disable search field until source and year is set.
    $("#album-search-field").attr("disabled", true);
    $("#album-search-button").attr("disabled", true);

    var that = this;

    // Event listener for first input fields.
    $(createListForm).submit(function (e) {
        // Prevent reloading of page.
        e.preventDefault();
        that.createList();
    });

    // Event listener for search field.
    $(albumSearchForm).submit(function (e) {
        e.preventDefault();
        that.findAlbums();
    });
};

AlbumListMaker.prototype.createList = function(){

    var sourceInput = $("#source");
    var yearInput = $("#year");
    var linkInput = $("#link");

    // Assign submitted values.
    if (sourceInput.val() !== "" || yearInput.val() !== "") {

        //TODO: mer felhantering.

        this.source = sourceInput.val();
        this.year = yearInput.val();
        this.link = linkInput.val();

        // Disable inputs after submit;
        sourceInput.attr("disabled", true);
        yearInput.attr("disabled", true);
        linkInput.attr("disabled", true);
        $("#createListButton").attr("disabled", true);

        // Active search form.
        $("#album-search-field").attr("disabled", false);
        $("#album-search-button").attr("disabled", false);

        $("#album-li-"+this.numberOfAlbums).addClass("active-li");

    } else {
        var messageDiv = $("#create-list-message");

        // TODO: kanske ha en metod för felmeddelande.

        $(messageDiv).removeClass("success");
        $(messageDiv).addClass("error");
        $(messageDiv).text("You have to enter a value.");

        $(messageDiv).show("fast");
        $(messageDiv).click(function () {
            $(this).hide("fast");
        });
    }
};

AlbumListMaker.prototype.findAlbums = function () {



    var searchQuery = $("#album-search-field").val();

    var that = this;

    // TODO: dont display api key here.

    var apiURL = "http://ws.audioscrobbler.com/2.0/?method=album.search&album="+searchQuery+"&limit=3" +
        "&api_key=c3ec843b6b80acb1bf180a874a95cf59&format=json";
    
    $.getJSON(apiURL, function (data) {

        // TODO escape response.
        var albumMatches = data.results.albummatches.album;



        var resultsDiv = $("#results");

        resultsDiv.empty();

        if (albumMatches.length === 0) {
            resultsDiv.append(
                "<h4>Search results</h4>" +
                "<p>No albums found</p>"
            );
        } else {
            displayResults();
        }

        function displayResults(){

            // TODO: highlight li that is about to be selected.
            resultsDiv.append(
                "<div class='col s12 m6 offset-m3'>" +
                    "<h4>Search results</h4>" +
                    "<p>Click to select album for place "+that.numberOfAlbums+".</p>" +
                    "<ul id='result-list' class='collection'></ul>" +
                "</div>"
            );

            // Render search result.
            albumMatches.forEach(function(album){
                var imageSrc = album.image[2]["#text"];
                // Presentation of search result.
                $("#result-list").append(
                    "<li class='collection-item avatar search-result-li'>" +
                        "<img src='"+imageSrc+"' alt='Album cover art' class='circle'>" +
                        "<span class='title'>"+album.name+"</span>" +
                        "<p class='artist'>"+album.artist+"</p>" +
                    "</li>"
                );
            });

            createTopAlbumsList();
        }

        function createTopAlbumsList(){

            //if ($(".album-list").length === 0) {
            //
            //    $("#results").append(
            //        "<div class='row'>" +
            //            "<div class='album-list col s12 m12'>" +
            //                "<h4>Top albums for "+that.source+" ("+that.year+")</h4>" +
            //                "<div id='save-message' style='display: none;'></div>" +
            //                "<ul id='top-albums' class='collection'></ul>" +
            //            "</div>" +
            //        "</div>"
            //    );
            //}

            //console.log(that.numberOfAlbums);




            // Event handler for album select.
            $(".search-result-li").click(function () {

                addAlbumToList(this);

                // TODO: make search results go away after choosing one album.

                $("#album-li-"+that.numberOfAlbums).removeClass("active-li");
                that.numberOfAlbums--;
                $("#album-li-"+that.numberOfAlbums).addClass("active-li");
            });

        }

        function addAlbumToList(selectedAlbum){

            var albumNumber = that.numberOfAlbums;

            var albumName = $(selectedAlbum).children(".title").clone();
            var artist = $(selectedAlbum).children(".artist").clone();
            var cover = $(selectedAlbum).children("img").clone();

            var listItem = $("#album-li-"+albumNumber);

            listItem.append(cover);
            listItem.append(albumName);
            listItem.append(artist);

            console.log(albumNumber);

            // If the list is finished.
            if (albumNumber === 1) {

                var messageDiv = $("#save-message");

                // TODO: egen metod för meddelande.
                $(messageDiv).removeClass("error");
                $(messageDiv).addClass("success");
                $(messageDiv).text("All albums added. Press 'Save my list' to submit.");
                // Displays feedback message.
                $(messageDiv).show("fast");
                $(messageDiv).click(function () {
                    $(this).hide("fast");
                });

                // Disable search fields.
                $("#album-search-field").attr("disabled", true);
                $("#album-search-button").attr("disabled", true);
                $("#results").empty("slow");

                // Array of selected albums as list items.
                var selectedAlbums = $("#top-albums").children().toArray();
                $("#album-list-div").append("<button id='save-list-button' class='waves-effect waves-light btn right'>Save my list</button>");

                // When user is done with list and presses save.
                $("#save-list-button").click(function () {
                    // TODO: Rensa

                    that.getTopAlbums(selectedAlbums);
                });
            }
        }
    }).fail(function (response) {

        var messageDiv = $("#search-form-message");

        $(messageDiv).removeClass("success");
        $(messageDiv).addClass("error");
        $(messageDiv).text("Something went wrong when trying to contact album search api.");

        $(messageDiv).show("fast");
        $(messageDiv).click(function () {
            $(this).hide("fast");
        });
    });
};

AlbumListMaker.prototype.getTopAlbums = function (selectedAlbums) {

    // The albums in the list are extracted and saved as objects in array.
    this.topAlbums = [];
    var that = this;

    // Iterates to list of top albums builds object and adds to array.
    $(selectedAlbums).each(function () {

        var name = $(this).find(".title").text();
        var artist = $(this).find(".artist").text();
        var position = $(this).find(".album-position-number").text();

        var album = {
            name: name,
            artist: artist,
            position: position
        };

        that.topAlbums.push(album);

    });

    this.saveList();
};

// Saves list with jQuery post.
AlbumListMaker.prototype.saveList = function () {

    // Displays error or success messages.
    var messageDiv = $("#save-message");

    var albumOfTheYearList = {
        year: this.year,
        source: this.source,
        link: this.link,
        albums: this.topAlbums
    };

    $.post("AjaxHandler.php", albumOfTheYearList).done(function(response){

        $(messageDiv).removeClass("error");
        $(messageDiv).addClass("success");
        $(messageDiv).text("List saved without problem!");
        $("#save-list-button").attr("disabled", true);

        // TODO: clear list.

    }).fail(function(response){

        $(messageDiv).removeClass("success");
        $(messageDiv).addClass("error");

        if (response.responseText !== "") {
            $(messageDiv).text("The following error occurred: " + response.responseText);
        } else {
            $(messageDiv).text("An error occurred, please try again.");
        }

    }).always(function (response) {

        // Displays feedback message.
        $(messageDiv).show("fast");
        $(messageDiv).click(function () {
            $(this).hide("fast");
        });

        if (response.responseText !== "") {
            $(messageDiv).text(response.responseText);
        }
    });
};

window.onload = init();


// URL for album info.
//var url = "http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist="+albumName+"&" +
//    "lang=sv&api_key=c3ec843b6b80acb1bf180a874a95cf59&format=json";
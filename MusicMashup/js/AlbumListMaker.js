"use strict";

function init(){
    AlbumListMaker = new AlbumListMaker();
}

var AlbumListMaker = function(){

    var createListForm = $("#create-list-form");
    var albumSearchForm = $("#album-form");

    this.source = "";
    this.year = "";
    this.topAlbums = [];

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

    // Assign submitted values.
    if (sourceInput.val() !== "" || yearInput.val() !== "") {

        //TODO: mer felhantering.

        this.source = sourceInput.val();
        this.year = yearInput.val();

        // Disable inputs after submit;
        sourceInput.attr("disabled", true);
        yearInput.attr("disabled", true);
        $("#createListButton").attr("disabled", true);

        // Active search form.
        $("#album-search-field").attr("disabled", false);
        $("#album-search-button").attr("disabled", false);

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
            resultsDiv.append("" +
                "<h4>Search results</h4>" +
                "<p>No albums found</p>");
        } else {
            displayResults();
        }

        function displayResults(){

            resultsDiv.append("" +
                "<div class='col m6'>" +
                    "<h4>Search results</h4>" +
                    "<ul id='result-list' class='collection'></ul>" +
                "</div>"
            );

            // Render search result.
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

            createTopAlbumsList();
        }

        function createTopAlbumsList(){

            if ($(".album-list").length === 0) {

                $(".content").append("<div class='album-list'>" +
                    "<h4>Top albums for "+that.source+" ("+that.year+")</h4>" +
                        "<div id='save-message' style='display: none;'></div>" +
                        "<ul id='top-albums' class='collection'></ul>" +
                    "</div>"
                );
            }

            // Event handler for album select.
            $(".collection-item").click(function () {
                addAlbumToList(this);
            });

        }

        function addAlbumToList(selectedAlbum){

            var listLength = $("#top-albums").children().length;
            var albumNumber = 3 - listLength;

            var albumName = $(selectedAlbum).children(".title").text();
            var artist = $(selectedAlbum).children(".artist").text();

            // Clones list item and adds to list of selected albums.
            var listItemClone = $(selectedAlbum).clone();
            listItemClone.appendTo("#top-albums");
            $(listItemClone).append("<span class='album-order-number'>"+albumNumber+"</span>");

            // If the list is finished.
            if (albumNumber === 1) {

                // Disable search fields.
                $("#album-search-field").attr("disabled", true);
                $("#album-search-button").attr("disabled", true);
                $("#results").empty("slow");

                // Array of selected albums as list items.
                var selectedAlbums = $("#top-albums").children().toArray();
                $(".album-list").append("<button id='save-list-button' class='waves-effect waves-light btn'>Save my list</button>");

                // When user is done with list and presses save.
                $("#save-list-button").click(function () {
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
        var order = $(this).find(".album-order-number").text();

        var album = {
            name: name,
            artist: artist,
            order: order
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
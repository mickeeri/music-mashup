"use strict";

function init(){
    AlbumListMaker = new AlbumListMaker();
}

var AlbumListMaker = function(){

    var that = this;

    var createListForm = $("#create-list-form");
    var albumSearchForm = $("#album-form");

    this.list;
    this.topAlbums = [];

    this.messageTypeSuccess = "success";
    this.messageTypeError = "error";

    // How many albums that is to be in the top list.
    this.numberOfAlbumsInList = 5;
    this.albumNumber = this.numberOfAlbumsInList;

    // Disable search field until source and year is set.
    $("#album-search-field").attr("disabled", true);
    $("#album-search-button").attr("disabled", true);

    $("#album-search-field").focus(function() {
        $(this).select();
    });


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

// Create list with source, link and year.
AlbumListMaker.prototype.createList = function(){

    var errorMessages = [];
    var sourceInput = $("#source");
    var yearInput = $("#year");
    var linkInput = $("#link");

    // Creating new list.
    var list =  new AlbumList(sourceInput.val(), linkInput.val(), yearInput.val());

    // If there are no error messages.
    if (Array.isArray(list) === false) {

        // If no errors assign created list to this.list.
        this.list = list;

        // Clears possible error message container.
        $("#create-list-messages").fadeOut("fast");

        // Disable inputs after submit;
        sourceInput.attr("disabled", true);
        yearInput.attr("disabled", true);
        linkInput.attr("disabled", true);
        $("#createListButton").attr("disabled", true);

        // Make search form active.
        $("#album-search-field").attr("disabled", false);
        $("#album-search-button").attr("disabled", false);

        // Make last element in top list active.
        $("#album-li-"+this.albumNumber).addClass("active-li");
    }
    // One or more errors on submit.
    else {
        this.displayMessage("#create-list-messages", this.messageTypeError, list);
    }
};

// Using search form to find albums.
AlbumListMaker.prototype.findAlbums = function () {

    var searchQuery = $("#album-search-field").val();

    if (searchQuery !== "") {
        this.getSearchResults(searchQuery);
    }
};

// Get search results from web service.
AlbumListMaker.prototype.getSearchResults = function ($query) {

    var that = this;

    // TODO: dont display api key here.
    var apiKey = "c3ec843b6b80acb1bf180a874a95cf59";
    var apiURL = "http://ws.audioscrobbler.com/2.0/?method=album.search&album="+$query+"&limit=3" +
        "&api_key="+apiKey+"&format=json";

    $.getJSON(apiURL, function (data) {

        var albumMatches = data.results.albummatches.album;
        that.displaySearchResults(albumMatches);

    }).fail(function () {
        that.displayMessage($("#search-form-message"), that.messageTypeError, "Kunde inte kontakta webbtjänsten.");
    })
};

// generate search results.
AlbumListMaker.prototype.displaySearchResults = function(albumMatches) {

    var that = this;

    var resultsDiv = $("#results");
    resultsDiv.empty();

    resultsDiv.append(
        "<div class='col s12 m12'>" +
            "<h4>Sökresultat</h4>" +
            "<p>Klicka på plusset för att lägga till album på plats <strong>"+this.albumNumber+"</strong></p>" +
            "<ul id='result-list' class='collection'></ul>" +
        "</div>"
    );

    // Render search result.
    albumMatches.forEach(function(album){

        $(".some-div").text(album.name);


        var imageSrc = album.image[2]["#text"];
        // Presentation of search result.
        $("#result-list").append(
            "<li class='collection-item avatar search-result-li'>" +
                "<input type='hidden' value='"+album.name+"'>"  +
                "<a class='btn-floating btn waves-effect waves-light red right add-album'><i class='material-icons'>add</i></a>" +
                "<img src='"+imageSrc+"' alt='Album cover art' class='circle'>" +
                "<span class='title'>"+album.name+"</span>" +
                "<p class='artist'>"+album.artist+"</p>" +
            "</li>"
        );
    });

    // Show results.
    resultsDiv.fadeIn("slow");

    // Event handler for when user selects on of the results.
    $(".add-album").click(function () {
        that.selectAlbum($(this).parent());
    });
};

AlbumListMaker.prototype.selectAlbum = function(clickedAlbumListItem) {

    console.log(this.albumNumber);

    // Adds album to top list.
    this.addAlbumToList(clickedAlbumListItem);

    // Hides search results.
    $("#results").fadeOut("fast");

    // Remove active class from current list item.
    $("#album-li-"+this.albumNumber).removeClass("active-li");

    // Decrese number of albums left to add.
    this.albumNumber--;

    // Make next list item active.
    $("#album-li-"+this.albumNumber).addClass("active-li");
};

AlbumListMaker.prototype.addAlbumToList = function (selectedAlbum) {

    var that = this;

    var albumNumber = this.albumNumber;

    // Album values.
    var albumName = $(selectedAlbum).children(".title").clone();
    var artist = $(selectedAlbum).children(".artist").clone();
    var cover = $(selectedAlbum).children("img").clone();

    var listItem = $("#album-li-"+albumNumber);

    listItem.append(cover);
    listItem.append(albumName);
    listItem.append(artist);

    // If the album is the last album.
    if (albumNumber === 1) {

        // Hide search results.
        $("#results").fadeOut("fast");

        this.displayMessage($("#save-message"), this.messageTypeSuccess, "Alla albums har lagts till. Klicka på Spara när du är klar.");

        // Disable search fields.
        $("#album-search-field").attr("disabled", true);
        $("#album-search-button").attr("disabled", true);

        // Array of selected albums as list items.
        var selectedAlbums = $("#top-albums").children().toArray();

        // Insert Save button.
        $("#album-list-div").append("<button id='save-list-button' class='waves-effect waves-light btn right'>Spara lista</button>");

        // When user is done with list and presses save.
        $("#save-list-button").click(function () {
            that.getAlbumsFromTopList(selectedAlbums);
        });
    }
};

AlbumListMaker.prototype.getAlbumsFromTopList = function (selectedAlbums) {

    // The albums in the list are extracted and saved as objects in array.
    var that = this;
    var topAlbums = [];

    var errors = [];

    // Iterates to list of top albums builds object and adds to array.
    $(selectedAlbums).each(function () {

        var name = $(this).find(".title").text();
        var artist = $(this).find(".artist").text();
        var position = $(this).find(".album-order-number").text();
        var cover = $(this).find("img").attr('src');

        var album = new Album(name, artist, position, cover);

        // If something wrong Album class returns array with errors instead of object.
        if (Array.isArray(album) === false) {
            topAlbums.push(album);
        } else {
            // Add error messages from Album class to array errors.
            errors = album;
        }
    });

    if (topAlbums.length === this.numberOfAlbumsInList && errors.length === 0) {
        this.saveList(topAlbums);
    } else {
        errors.unshift("Albumen går inte att spara. Fel med albuminformationen: ");
        that.displayMessage($("#save-message"), that.messageTypeError, errors);
    }
};

// Saves list with jQuery post.
AlbumListMaker.prototype.saveList = function (albums) {

    var that = this;

    // Displays error or success messages.
    var messageDiv = $("#save-message");

    // Adding albums to already created list.
    this.list.albums = albums;

    console.log(this.list);

    $.post("AjaxHandler.php", this.list).done(function(response){

        console.log(response);

        // Post went fine. Show success message and disable save-button.
        that.displayMessage(messageDiv, that.messageTypeSuccess, "Listan sparad utan problem!");
        $("#save-list-button").fadeOut();
        $('#album-list-div').append('<a id="reload" href="javascript:window.location.href=window.location.href">Lägg till fler listor</a>');


    }).fail(function(response){

        var errorMessage = "";

        if (response.responseText !== "") {

            errorMessage = response.responseText;
        } else {
            errorMessage = ("Ett fel uppstod när listan skulle sparas.");
        }

        that.displayMessage(messageDiv, that.messageTypeError, errorMessage);

    });
};

// Creates both error and success messages. Handles both single message and array of messages.
AlbumListMaker.prototype.displayMessage = function (messageContainer, messageType, messages) {

    $(messageContainer).empty();
    //$(messageContainer).fadeOut("fast");

    if (messageType === this.messageTypeSuccess) {
        $(messageContainer).removeClass(this.messageTypeError);
        $(messageContainer).addClass(this.messageTypeSuccess);
        //$(messageContainer).text(message);
    }
    else if(messageType === this.messageTypeError) {
        $(messageContainer).removeClass(this.messageTypeSuccess);
        $(messageContainer).addClass(this.messageTypeError);
    }

    var closeIcon = $('<img class="close-message-icon" src="images/close_icon.svg" alt="Stäng meddelande." />');
    $(messageContainer).append(closeIcon);

    if (Array.isArray(messages)) {

        $(messageContainer).append("<ul class='message-ul'></ul>");
        messages.forEach(function(message) {
            $(".message-ul").append("<li>"+message+"</li>");
        });

        messages.length = 0;
    } else {
        $(messageContainer).append('<p>'+messages+'</p>');
    }

    $(messageContainer).fadeIn("slow");
    $(closeIcon).click(function () {
        $(messageContainer).fadeOut("fast");
    });
};


window.onload = init();

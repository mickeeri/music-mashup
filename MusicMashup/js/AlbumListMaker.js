"use strict";

function init(){
    AlbumListMaker = new AlbumListMaker();
}

// Album List object. Retuns array of error messages on failed validation.
var AlbumList = function (source, link, year) {

    $(".some-div").text(source);
    var errorMessages = [];

    if (typeof source === "undefined" || source === null || source === "") {
        errorMessages.push("Du måste ange källa.");
    } else if (source.length > 100) {
        errorMessages.push("Namnet på källan är för långt.");
    }

    if (typeof link === "undefined" || link === null || link === "") {
        errorMessages.push("Du måste ange en länk till källan.");
    } else if (link.length > 150) {
        errorMessages.push("Länken är för lång.");
    }

    if (typeof year === "undefined" || year === null || year === "") {
        errorMessages.push("Du måste välja ett år.");
    } else if (isNaN(year)) {
        errorMessages.push("År måste vara ett nummer.");
    }

    // If error messages is empty assign values.
    if (errorMessages.length === 0) {
        this.source = getEscapedString(source);
        this.link = getEscapedString(link);
        this.year = getEscapedString(year);
    } else {
        return errorMessages;
    }

    // Basic escape to lower risk of xxs.
    function getEscapedString(string){
        $("#create-list-messages").text(string);
        return $("#create-list-messages").text(string).html();
    }
};



var AlbumListMaker = function(){

    var that = this;
    // TODO: Gör så att markören hamnar i input-fältet. t.ex. när man lagt in år och sånt.

    var createListForm = $("#create-list-form");
    var albumSearchForm = $("#album-form");

    this.source = "";
    this.year = "";
    this.link = "";
    this.topAlbums = [];

    this.messageTypeSuccess = "success";
    this.messageTypeError = "error";

    // How many albums that is to be in the top list.
    this.numberOfAlbums = 2;

    // Disable search field until source and year is set.
    $("#album-search-field").attr("disabled", true);
    $("#album-search-button").attr("disabled", true);


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
    var newList = new AlbumList(sourceInput.val(), linkInput.val(), yearInput.val());

    console.log(newList);

    $("#create-list-form").prepend(newList.source);
    $("#create-list-form").prepend(newList.link);

    // If there are no error messages.
    if (errorMessages.length === 0) {

        // Clears possible error message container.
        $("#create-list-messages").fadeOut("fast");

        // Assigning values to variables declared in main function.
        //this.source = sourceInput.val();
        //this.year = yearInput.val();
        //this.link = linkInput.val();

        // Disable inputs after submit;
        sourceInput.attr("disabled", true);
        yearInput.attr("disabled", true);
        linkInput.attr("disabled", true);
        $("#createListButton").attr("disabled", true);

        // Make search form active.
        $("#album-search-field").attr("disabled", false);
        $("#album-search-button").attr("disabled", false);

        // Make last element in top list active.
        $("#album-li-"+this.numberOfAlbums).addClass("active-li");
    }
    // One or more errors on submit.
    else {
        this.displayMessage("#create-list-messages", this.messageTypeError, errorMessages);
    }
};

// Using search form to find albums.
AlbumListMaker.prototype.findAlbums = function () {

    var searchQuery = $("#album-search-field").val();

    if (searchQuery !== "") {
        this.getSearchResults(searchQuery);
    }
};

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

AlbumListMaker.prototype.displaySearchResults = function(albumMatches) {

    var that = this;

    var resultsDiv = $("#results");
    resultsDiv.empty();

    resultsDiv.append(
        "<div class='col s12 m8 offset-m2'>" +
            "<h4>Search results</h4>" +
            "<p>Click to select album for place "+this.numberOfAlbums+".</p>" +
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
                "<img src='"+imageSrc+"' alt='Album cover art' class='circle'>" +
                "<span class='title'>"+album.name+"</span>" +
                "<p class='artist'>"+album.artist+"</p>" +
            "</li>"
        );
    });

    // Show results.
    resultsDiv.fadeIn("slow");

    // Event handler for when user selects on of the results.
    $(".search-result-li").click(function () {
        that.selectAlbum(this);
    });
};

AlbumListMaker.prototype.selectAlbum = function(clickedAlbumListItem) {

    // Adds album to top list.
    this.addAlbumToList(clickedAlbumListItem);

    // Hides search results.
    $("#results").fadeOut("fast");

    // Display feedback message.
    //this.displayMessage($("#save-message"), this.messageTypeSuccess, "Album tillagt!");

    // Remove active class from current list item.
    $("#album-li-"+this.numberOfAlbums).removeClass("active-li");

    // Decrese number of albums left to add.
    this.numberOfAlbums--;

    // Make next list item active.
    $("#album-li-"+this.numberOfAlbums).addClass("active-li");
};

AlbumListMaker.prototype.addAlbumToList = function (selectedAlbum) {

    var that = this;

    var albumNumber = this.numberOfAlbums;

    // Album values.
    var albumName = $(selectedAlbum).children(".title").clone();
    var artist = $(selectedAlbum).children(".artist").clone();
    var cover = $(selectedAlbum).children("img").clone();

    var listItem = $("#album-li-"+albumNumber);

    listItem.append(cover);
    listItem.append(albumName);
    listItem.append(artist);

    console.log(albumNumber);

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
    this.topAlbums = [];
    var that = this;

    // Iterates to list of top albums builds object and adds to array.
    $(selectedAlbums).each(function () {

        var name = $(this).find(".title").text();
        var artist = $(this).find(".artist").text();
        var position = $(this).find(".album-order-number").text();

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

    }).fail(function(response){

        $(messageDiv).removeClass("success");
        $(messageDiv).addClass("error");

        console.log(response);

        if (response.responseText !== "") {
            $(messageDiv).text("The following error occurred: " + response.responseText);
        } else {
            $(messageDiv).text("An error occurred, please try again.");
        }

    }).always(function (response) {

        console.log(response);

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

AlbumListMaker.prototype.displayMessage = function (messageContainer, messageType, messages) {


    console.log(messages);

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


// URL for album info.
//var url = "http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist="+albumName+"&lang=sv&api_key=c3ec843b6b80acb1bf180a874a95cf59&format=json";
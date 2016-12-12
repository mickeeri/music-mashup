"use strict";

// Album List object. Retuns array of error messages on failed validation.
var AlbumList = function (source, link, year) {

    this.source;
    this.link;
    this.year;
    this.albums = []; // Array of album objects

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

    // Basic escape to lower risk of xss.
    function getEscapedString(string){
        $("#create-list-messages").text(string);
        return $("#create-list-messages").text(string).html();
    }
};

"use strict"

var Album = function(name, artist, position, cover){

    this.name;
    this.artist;
    this.position;
    this.cover;

    var errorMessages = [];

    if (typeof name === "undefined" || name === null || name === "") {
        errorMessages.push("Albumnamn saknas");
    } else if (name.length > 100) {
        errorMessages.push("Albumnamn är för långt.");
    }

    if (typeof artist === "undefined" || artist === null || artist === "") {
        errorMessages.push("Artist saknas.");
    } else if (artist.length > 50) {
        errorMessages.push("Albumets artist består av för många tecken.");
    }

    if (typeof position === "undefined" || position === null || position === "") {
        errorMessages.push("Plats saknas.");
    } else if (isNaN(position)) {
        errorMessages.push("Albumets plats måste vara ett nummer.");
    }

    if (typeof cover === "undefined" || cover === null || cover === "") {
        // If cover is missing assign default cover.
        this.cover = "images/default-img.png";
    } else {
        this.cover = cover;
    }

    if (errorMessages.length === 0) {
        this.name = getEscapedString(name);
        this.artist = getEscapedString(artist);
        this.position = getEscapedString(position);
    } else {
        return errorMessages;
    }

    // Basic escape to lower risk of xss.
    function getEscapedString(string){
        $("#create-list-messages").text(string);
        return $("#create-list-messages").text(string).html();
    }
};

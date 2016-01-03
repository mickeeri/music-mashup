<?php

require_once ("AlbumsOfTheYearList.php");
require_once ("Facade.php");
require_once ("Album.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $year = $_POST["year"];
    $source = $_POST["source"];
    $albums = $_POST["albums"];


    // Check that data was sent.
    if (empty($year) OR empty($source) OR empty($albums)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "There was a problem when the list was about to be saved.";
        exit;
    }

    // Converting associative array to php object.
    $albumsAsPHPObjects = array();

    foreach ($albums as $album) {

        array_push($albumsAsPHPObjects, new \models\Album($album["name"], $album["artist"], $album["order"]));

    }

    $yearList = new \models\AlbumsOfTheYearList($year, $source, $albumsAsPHPObjects);

    $facade = new \models\Facade();

    $facade->addList($yearList);

    echo "Your list has been saved";

} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "There was a problem, please try again.";
}






<?php

// TODO: behöver jag alla dessa, behöver jag då dem i index.php?
require_once("models/AlbumsOfTheYearList.php");
require_once("models/Facade.php");
require_once("models/Album.php");
require_once("models/AlbumListDAL.php");
require_once ("Settings.php");

//if (true) {
//    error_reporting(-1);
//    ini_set('display_errors', 'ON');
//}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $year = $_POST["year"];
    $source = $_POST["source"];
    $link = $_POST["link"];
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

    try {
        // Creating php object for each album.
        foreach ($albums as $album) {
            array_push($albumsAsPHPObjects, new \models\Album($album["name"], $album["artist"], $album["position"], "", null));
        }
    } catch (\Exception $e) {
        http_response_code(500);
        echo $e->getMessage();
    }

    try {
        // Creating list and inserting albums.
        $yearList = new \models\AlbumsOfTheYearList($year, $source, $link, $albumsAsPHPObjects);
    } catch (\Exception $e) {
        http_response_code(500);
        echo $e->getMessage();
    }

    // Saving list in database.
    $facade = new \models\Facade();
    $facade->saveList($yearList);
}






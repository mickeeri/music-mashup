<?php

// TODO: behöver jag alla dessa, behöver jag då dem i index.php?
require_once("models/AlbumsOfTheYearList.php");
require_once("models/Facade.php");
require_once("models/Album.php");
require_once("models/AlbumListDAL.php");
require_once ("Settings.php");

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

    foreach ($albums as $album) {
        array_push($albumsAsPHPObjects, new \models\Album($album["name"], $album["artist"], $album["position"], ""));
    }

    $yearList = new \models\AlbumsOfTheYearList($year, $source, $link, $albumsAsPHPObjects);

    //var_dump($yearList);


    try {
        $facade = new \models\Facade();
        $facade->saveList($yearList);
    } catch (\Exception $e) {
        echo $e;
    }


    //echo "Your list has been saved";

}






<?php

require_once ("Settings.php");
require_once ("controllers/MasterController.php");
require_once ("views/NavigationView.php");
require_once ("views/LayoutView.php");

//require_once("AjaxHandler.php");


if ($_SERVER["HTTP_HOST"] === "localhost:8888") {
    require_once("../../kint-master/Kint.class.php");
    error_reporting(-1);
    ini_set('display_errors', 'ON');
}

if (Settings::DISPLAY_ERRORS) {
    error_reporting(-1);
    ini_set('display_errors', 'ON');
}



//$artist = preg_replace('/\s+/', '+', "Skepta");
//$albumName = preg_replace('/\s+/', '+', "Grime 2015");
//
//$url = "http://ws.audioscrobbler.com/2.0/?method=album.getinfo&artist=".$artist."&album=".$albumName."&api_key=c3ec843b6b80acb1bf180a874a95cf59&format=json";
//$urlSearch = "http://ws.audioscrobbler.com/2.0/?method=album.search&album=Born&limit=3&api_key=c3ec843b6b80acb1bf180a874a95cf59&format=json";
//$ch = curl_init();
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_URL, $url);
//$result = curl_exec($ch);
//curl_close($ch);
//
//$result = json_decode($result);
//
////d($result->album->image[2]->{"#text"});
//d($result);
//
//$json = file_get_contents($url);
//$json_data = json_decode($json, true);
//d($json_data);



$nv = new \views\NavigationView();
$mc = new \controllers\MasterController($nv);

$mc->handleInput();

$view = $mc->generateOutput();

$lv = new \views\LayoutView();

//$facade = new \models\Facade();

$testSpotifyAPI = new \models\WebServiceModel();
//$album = $testSpotifyAPI->getAlbumFromSpotifyAPI("Jamie xx", "In Colour");
//ddd($album);


//$lv->renderLayout($nv, $view);

$testSpotifyAPI->requestAuthorizationCode();




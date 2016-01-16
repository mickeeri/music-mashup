<?php

require_once ("Settings.php");
require_once ("controllers/MasterController.php");
require_once ("views/NavigationView.php");
require_once ("views/LayoutView.php");
require_once ("models/Exceptions.php");
require_once ("models/Year.php");


if ($_SERVER["HTTP_HOST"] === "localhost:8888") {
    require_once("../../kint-master/Kint.class.php");
    error_reporting(-1);
    ini_set('display_errors', 'ON');
}

if (\Settings::DISPLAY_ERRORS) {
    error_reporting(-1);
    ini_set('display_errors', 'ON');
}

session_start();

//d($_SERVER['QUERY_STRING']);
//d(isset($_GET['admin/albumlist']));

//header('Cache-Control: max-age=3600, public, must-revalidate');

$nv = new \views\NavigationView();
$mc = new \controllers\MasterController($nv);

$mc->handleInput();

$view = $mc->generateOutput();

$lv = new \views\LayoutView();

//$facade = new \models\Facade();

$testSpotifyAPI = new \models\WebServiceModel();
//$album = $testSpotifyAPI->getAlbumFromSpotifyAPI("Jamie xx", "In Colour");
//ddd($album);


$lv->renderLayout($nv, $view);






<?php

require_once ("Settings.php");
require_once ("controllers/MasterController.php");
require_once ("views/NavigationView.php");
require_once ("views/LayoutView.php");
require_once ("models/Exceptions.php");
require_once ("models/Year.php");


//if ($_SERVER["HTTP_HOST"] === "localhost:8888") {
//    require_once("../../kint-master/Kint.class.php");
//    error_reporting(-1);
//    ini_set('display_errors', 'ON');
//}

if (\Settings::DISPLAY_ERRORS) {
    ini_set('display_errors', 1);
    error_reporting(~0);
}

session_start();

$nv = new \views\NavigationView();
$mc = new \controllers\MasterController($nv);

$mc->handleInput();

$view = $mc->generateOutput();

$lv = new \views\LayoutView();

$testSpotifyAPI = new \models\WebServiceModel();

$lv->renderLayout($nv, $view);






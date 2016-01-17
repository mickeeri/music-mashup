<?php

require_once ("Settings.php");
require_once ("controllers/MasterController.php");
require_once ("views/NavigationView.php");
require_once ("views/LayoutView.php");
require_once ("models/Exceptions.php");
require_once ("models/Year.php");

if (\Settings::DISPLAY_ERRORS) {
    error_reporting(-1);
    ini_set('display_errors', 'ON');
}



if ($_SERVER["HTTP_HOST"] === "localhost:8888") {
    require_once("../../kint-master/Kint.class.php");

}

session_start();

$nv = new \views\NavigationView();
$mc = new \controllers\MasterController($nv);

$mc->handleInput();

$view = $mc->generateOutput();

$lv = new \views\LayoutView();

$testSpotifyAPI = new \models\WebServiceModel();

$lv->renderLayout($nv, $view);






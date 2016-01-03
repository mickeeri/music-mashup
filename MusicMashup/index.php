<?php

require_once ("Settings.php");
require_once ("controllers/MasterController.php");
require_once ("views/NavigationView.php");
require_once ("views/LayoutView.php");
require_once("models/AjaxHandler.php");

$nv = new \views\NavigationView();
$mc = new \controllers\MasterController($nv);

// To show better var-dumps
if ($_SERVER['HTTP_HOST'] === "localhost:63342") {
    require_once("../../kint-master/Kint.class.php");
}

$mc->handleInput();

$view = $mc->generateOutput();

$lv = new \views\LayoutView();

$lv->renderLayout($nv, $view);


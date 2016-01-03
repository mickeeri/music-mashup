<?php

require_once ("Settings.php");
require_once ("controllers/MasterController.php");
require_once ("views/NavigationView.php");
require_once ("views/LayoutView.php");

require_once("models/AjaxHandler.php");
require_once ("models/Facade.php");

require_once("../../kint-master/Kint.class.php");


error_reporting(-1);
ini_set('display_errors', 'ON');

$nv = new \views\NavigationView();
$mc = new \controllers\MasterController($nv);

$mc->handleInput();

$view = $mc->generateOutput();

$lv = new \views\LayoutView();

$lv->renderLayout($nv, $view);


<?php

require_once ("Settings.php");

require_once ("models/AdminCredentials.php");
require_once ("models/LoginModel.php");

if (Settings::DISPLAY_ERRORS) {
    error_reporting(-1);
    ini_set('display_errors', 'ON');
}

session_start();


<?php

namespace views;


class NavigationView
{
    private static $sessionSaveLocation = "\\view\\NavigationView\\message";

    public function getNavigationBar()
    {
        return '
            <ul class="nav">
                <li><a href="?">Home</a></li>
                <li><a href="?">2015</a> </li>
            </ul>
        ';
    }

    public function getHeaderMessage()
    {
        if (isset($_SESSION[self::$sessionSaveLocation])) {

            $message = $_SESSION[self::$sessionSaveLocation];
            unset($_SESSION[self::$sessionSaveLocation]);

            return '
			<div class="alert alert-success" role="alert">
				' . $message . '
			</div>
			';
        }
    }

    public function onAdminPage()
    {
        d($_SERVER["REQUEST_URI"]);

        if (isset($_GET[\Settings::SECRET_ADMIN_URL])) {
            return true;
        } else {
            return false;
        }
    }
}
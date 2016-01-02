<?php

namespace views;


class NavigationView
{
    private static $sessionSaveLocation = "\\view\\NavigationView\\message";

    public function getNavigationBar()
    {
        return '
            <nav>
                <div class="nav-wrapper">
                    <a href="#" class="brand-logo">Albums of the year</a>
                    <ul id="nav-mobile" class="right hide-on-med-and-down">
                        <li><a href="?">Home</a></li>
                        <li><a href="?">2015</a> </li>
                    </ul>
                </div>
            </nav>
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

        if (isset($_GET[\Settings::SECRET_ADMIN_URL])) {
            return true;
        } else {
            return false;
        }
    }
}
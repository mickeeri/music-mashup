<?php

namespace views;


class NavigationView
{
    private static $sessionSaveLocation = "\\view\\NavigationView\\message";
    private static $yearURL = "year";
    public static $adminListsURI = "albumlists";
    private static $listURL = "albumlist";
    public static $removeListURI = "removelist";
    private $years;

    public function getNavigationBar()
    {
        return '
            <nav class="white">
                <div class="nav-wrapper">
                    <a href="#" class="brand-logo">Albums of the year</a>
                    <ul id="nav-mobile" class="right hide-on-med-and-down">
                        <li><a href="?">Home</a></li>
                        '.$this->renderYears().'
                    </ul>
                </div>
            </nav>
        ';
    }

    public function onAdminPage()
    {

        if (isset($_GET[\Settings::SECRET_ADMIN_URL])) {
            return true;
        } else {
            return false;
        }
    }

    public function onAdminAlbumListsPage()
    {
        if (isset($_GET[\Settings::SECRET_ADMIN_URL."/".self::$adminListsURI])) {
            return true;
        } else {
            return false;
        }
    }

    public function getAlbumToDelete()
    {
        if ($this->onDeleteListPage()) {
            return $_GET[\Settings::SECRET_ADMIN_URL."/".self::$adminListsURI."/".self::$removeListURI];
        }
    }

    public function onDeleteListPage()
    {
        if (isset($_GET[\Settings::SECRET_ADMIN_URL."/".self::$adminListsURI."/".self::$removeListURI])) {
            return true;
        }

        return false;
    }

    public function onYearPage()
    {
        return isset($_GET[self::$yearURL]);
    }

    public function onAlbumListPage()
    {
        return isset($_GET[self::$listURL]);
    }

    public function getYearToShow()
    {
        return $_GET[self::$yearURL];
    }

    public function getAlbumsToShow()
    {
        return $_GET[self::$listURL];
    }

    public function setYears($years)
    {
        $this->years = $years;
    }

    private function renderYears()
    {
        $ret = "";

        foreach ($this->years as $year) {
            $ret .= '<li><a href="?'.self::$yearURL.'='.$year.'">'.$year.'</a></li>';
        }

        return $ret;
    }
}
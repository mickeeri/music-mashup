<?php

namespace views;


class AdminView
{
    private static $newListUrl = \Settings::SECRET_ADMIN_URL . "/newtopten";


    public function response ()
    {
        return

            '<h1>Hello admin</h1>
            <h2>Admin menu</h2>';
    }


    public function onNewListPage()
    {
        return isset($_GET[self::$newListUrl]);
    }
}
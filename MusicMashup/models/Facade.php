<?php

namespace models;

class Facade
{

    public function __construct()
    {
//        // Setting up database.
//        try {
//            $db = new PDO(\Settings::DSN, \Settings::USERNAME, \Settings::PASSWORD);
//            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//        } catch (PDOException $e) {
//            var_dump($e);
//        }
    }

    /**
     * @param AlbumsOfTheYearList $list
     */
    public function addList(AlbumsOfTheYearList $list)
    {
        //var_dump($list->getAlbums());
    }

}
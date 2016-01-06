<?php

namespace models;

class Facade
{
    private $dal;
    private $db;

    public function __construct()
    {
        // Setting up database.
        try {
            $this->db = new \PDO(\Settings::DSN, \Settings::USERNAME, \Settings::PASSWORD);
            //$this->db = new \PDO("mysql:dbname=albumsoftheyear;host=localhost;port=8889, root, root");
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            http_response_code(500);
            echo $e->getMessage();
        }

        $this->dal = new \models\AlbumListDAL($this->db);
    }

    /**
     * @param AlbumsOfTheYearList $list
     */
    public function saveList(AlbumsOfTheYearList $list)
    {
       try {

           $this->dal->addList($list);
           //$this->dal->addAlbumsToList($listID, )
           echo "Your list has been saved.";


       } catch (\Exception $e) {
           http_response_code(500);
           echo $e->getMessage();
       }
    }

    /**
     * Returns the years in the db.
     */
    public function getYears()
    {
        return $this->dal->getYears();
    }

    public function getListsForYear($year)
    {
        return $this->dal->getListsForYear($year);
    }

    public function getListByID($listID)
    {
        return $this->dal->getListByID($listID);
    }



}
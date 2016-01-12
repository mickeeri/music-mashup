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
     * Save Album List. Called from AjaxHandler.php.
     * @param AlbumsOfTheYearList $list
     */
    public function saveList(AlbumsOfTheYearList $list)
    {
       try {
           $this->dal->addList($list);
           http_response_code(200);
           echo "Din lista har sparats.";
       } catch (\Exception $e) {
           http_response_code(500);
           echo $e->getMessage();
           exit;
       }
    }

    /**
     * Returns the years in the db.
     */
    public function getYears()
    {
        try {
            return $this->dal->getYears();
        } catch (\Exception $e) {

        }
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
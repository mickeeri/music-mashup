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
           echo "Listan är sparad";
       } catch (\Exception $e) {
           http_response_code(500);
           echo $e->getMessage();
           exit;
       }
    }

    public function getYearsAndLists()
    {
        try {

            $yearArr = array();

            foreach ($this->getYears() as $year) {
                array_push($yearArr, new \models\Year($year, $this->getListsForYear($year)));
            }

            return $yearArr;


        } catch (\PDOException $e) {
            throw new \FetchAlbumListsException();
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

    public function deleteList($listID)
    {
        $this->dal->deleteListByID($listID);
    }
}
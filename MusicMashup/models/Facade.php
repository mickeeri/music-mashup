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
     * @param AlbumList $list
     */
    public function saveList(AlbumList $list)
    {
       try {
           $this->dal->addList($list);
           http_response_code(200);
           echo "Listan är sparad";
       } catch (\ListAlreadyExistsException $e) {
           http_response_code(500);
           echo 'En lista från '.$list->getYear().' av '.$list->getSource().' finns redan.';
           exit;
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
            throw new \GetDataException();
        }
    }

    public function getListsForYear($year)
    {
        try {
            return $this->dal->getListsForYear($year);
        } catch (\Exception $e) {
            throw new \GetDataException();
        }

    }

    public function getListByID($listID)
    {
        try {
            return $this->dal->getListByID($listID);
        } catch (\Exception $e) {
            throw new \GetDataException();
        }
    }

    public function deleteList($listID)
    {
        try {
            $this->dal->deleteListByID($listID);
        } catch (\Exception $e) {
            throw new \DeleteListException();
        }
    }
}
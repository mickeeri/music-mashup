<?php

namespace models;


class AlbumListDAL
{
    private $db;

    /**
     * AlbumListDAL constructor.
     * @param \PDO $db
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function addList(\models\AlbumsOfTheYearList $list)
    {
        // TODO: try catch i varje metod.


        $stmt = $this->db->prepare("INSERT INTO albumlist (year, source, link) values (:year, :source, :link)");
        $stmt->bindParam(":year", $list->getYear());
        $stmt->bindParam(":source", $list->getSource());
        $stmt->bindParam(":link", $list->getLink());
        $stmt->execute();

        $this->addAlbumsToList($this->db->lastInsertId(), $list->getAlbums());

    }

    /**
     * @param int $listID
     * @param array Album $albums
     */
    public function addAlbumsToList($listID, $albums)
    {
        /** @var Album $album */
        foreach ($albums as $album) {
            $stmt = $this->db->prepare("INSERT INTO album (name, artist, position, listID) values (:name, :artist, :position, :listID)");
            $stmt->bindParam(":name", $album->getName());
            $stmt->bindParam(":artist", $album->getArtist());
            $stmt->bindParam(":position", $album->getPosition());
            $stmt->bindParam(":listID", intval($listID));
            $stmt->execute();
        }
    }

    public function getYears(){
        $stmt = $this->db->query('SELECT year FROM albumlist ORDER BY year DESC LIMIT 15');
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);

        $years = array();

        while($row = $stmt->fetch()) {
            if (!in_array($row['year'], $years)) {
                array_push($years, $row['year']);
            }
        }

        return $years;
    }

    public function getListsForYear($year)
    {
        $stmt = $this->db->query('SELECT listID, year, source, link FROM albumlist WHERE year = '.$year);
        //$stmt->bindParam(':year', $year);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);

        $lists = array();

        while($row = $stmt->fetch())
        {
            $listID = $row['listID'];
            $y = $row['year'];
            $source = $row['source'];
            $link = $row['link'];
            $albums = $this->getAlbumsByListID($listID);

            $list = new \models\AlbumsOfTheYearList($y, $source, $link, $albums);
            $list->setListID($listID);

            array_push($lists, $list);

        }

        return $lists;
    }

    public function getListByID($listID)
    {
        $stmt = $this->db->query('SELECT listID, year, source, link FROM albumlist WHERE listID = '.$listID);
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $row = $stmt->fetch();
        $albums = $this->getAlbumsByListID($row->listID);
        $list = new \models\AlbumsOfTheYearList($row->year, $row->source, $row->link, $albums);
        $list->setListID($listID);
        return $list;
    }

    private function getAlbumsByListID($listID)
    {
        $stmt = $this->db->query('SELECT albumID, name, artist, position FROM album WHERE listID = '.$listID.' ORDER BY position ASC');
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);

        $albums = array();

        while ($row = $stmt->fetch()) {
            $albumID = $row['albumID'];
            $name = $row['name'];
            $artist = $row['artist'];
            $position = $row['position'];

            $album = new \models\Album($name, $artist, $position);
            $album->setAlbumID($albumID);

            array_push($albums, $album);
        }

        return $albums;
    }



}
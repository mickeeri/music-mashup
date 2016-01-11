<?php

namespace models;

require_once ("models/WebServiceModel.php");


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
        try {
            $stmt = $this->db->prepare("INSERT INTO albumlist (year, source, link) values (:year, :source, :link)");
            $stmt->bindParam(":year", $list->getYear());
            $stmt->bindParam(":source", $list->getSource());
            $stmt->bindParam(":link", $list->getLink());
            $stmt->execute();
            $this->addAlbumsToList($this->db->lastInsertId(), $list->getAlbums());
        } catch (\Exception $e) {
            echo "Databasfel: ".$e->getMessage();
        }
    }

    /**
     * @param int $listID
     * @param array Album $albums
     */
    public function addAlbumsToList($listID, $albums)
    {

        /** @var Album $album */
        foreach ($albums as $album) {

            try {
                $stmt = $this->db->prepare("INSERT INTO album (name, artist, position, cover,  spotifyURI, listID)
                                      values (:name, :artist, :position, :cover, :spotifyURI, :listID)");
                $stmt->bindParam(":name", $album->getName());
                $stmt->bindParam(":artist", $album->getArtist());
                $stmt->bindParam(":position", $album->getPosition());
                $stmt->bindParam(":cover", $album->getCover());
                $stmt->bindParam(":spotifyURI", $album->getSpotifyURI());
                $stmt->bindParam(":listID", intval($listID));
                $stmt->execute();
            } catch (\Exception $e) {
                echo "Databasfel: ".$e->getMessage();
            }
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
            //$albums = $this->getAlbumsByListID($listID);

            $list = new \models\AlbumsOfTheYearList($y, $source, $link, "");
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
        $stmt = $this->db->query('SELECT albumID, name, artist, position, spotifyURI, cover FROM album WHERE listID = '.$listID.' ORDER BY position ASC');
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);

        $albums = array();

        while ($row = $stmt->fetch()) {
            $albumID = $row['albumID'];
            $name = $row['name'];
            $artist = $row['artist'];
            $position = $row['position'];
            $cover = $row['cover'];
            $spotifyURI = $row['spotifyURI'];

            $album = new \models\Album($name, $artist, $position, $cover, $spotifyURI);
            $album->setAlbumID($albumID);

            array_push($albums, $album);
        }

        return $albums;
    }
//
//    private function insertAlbumCover($albumID, $cover)
//    {
//        $stmt = $this->db->prepare("UPDATE album SET cover = :cover WHERE albumID = :albumID");
//        $stmt->bindParam(":cover", $cover);
//        $stmt->bindParam(":albumID", $albumID);
//        $stmt->execute();
//    }



}
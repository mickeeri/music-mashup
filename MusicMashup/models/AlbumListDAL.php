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
            $stmt->bindParam(":position", $album->getOrder());
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

}
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
        $stmt = $this->db->prepare("INSERT INTO albumlist (year, source) values (:year, :source)");
        $stmt->bindParam(":year", $list->getYear());
        $stmt->bindParam(":source", $list->getSource());
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

}
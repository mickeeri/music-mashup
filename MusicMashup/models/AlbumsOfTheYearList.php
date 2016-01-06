<?php

namespace models;


class AlbumsOfTheYearList
{
    private $listID;
    private $year;
    private $source;
    private $link;
    private $albums = array();

    /**
     * AlbumsOfTheYearList constructor.
     * @param string $year
     * @param string $source
     * @param string $link
     * @param array Album $albums
     */
    public function __construct($year, $source, $link, $albums)
    {
        // TODO: Validera

        $this->year = filter_var($year, FILTER_SANITIZE_STRING);
        $this->source = filter_var($source, FILTER_SANITIZE_STRING);
        $this->link = filter_var($link, FILTER_SANITIZE_STRING);
        $this->albums = $albums;
    }

    public function setListID($listID)
    {
        if ($listID !== null && is_numeric($listID) === false) {
            throw new \Exception("Unvalid ID.");
        }

        $this->listID = $listID;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function getAlbums()
    {
        return $this->albums;
    }

    public function getListID()
    {
        return $this->listID;
    }

}
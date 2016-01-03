<?php

namespace models;


class AlbumsOfTheYearList
{
    private $year;
    private $source;
    private $albums;

    /**
     * AlbumsOfTheYearList constructor.
     * @param string $year
     * @param string $source
     * @param array Album $albums
     */
    public function __construct($year, $source, $albums)
    {
        // TODO: Validera

        $this->year = filter_var($year, FILTER_SANITIZE_STRING);
        $this->source = filter_var($source, FILTER_SANITIZE_STRING);
        $this->albums = $albums;
    }

    public function getYear(){
        return $this->year;
    }

    public function getSource(){
        return $this->source;
    }

    public function getAlbums(){
        return $this->albums;
    }

}
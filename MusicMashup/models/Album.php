<?php

namespace models;


class Album
{
    private $albumID;
    private $name;
    private $artist;
    private $position;

    /**
     * Album constructor.
     * @param string $name
     * @param string $artist
     * @param string $order
     */
    public function __construct($name, $artist, $order)
    {
        // TODO: Validera string-lenght och att att position är en siffra mellan 1 och ...

        $this->name = filter_var($name, FILTER_SANITIZE_STRING);
        $this->artist = filter_var($artist, FILTER_SANITIZE_STRING);
        $this->position = filter_var($order, FILTER_SANITIZE_STRING);
    }

    public function setAlbumID($albumID)
    {
        $this->albumID = $albumID;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getArtist()
    {
        return $this->artist;
    }

    public function getPosition()
    {
        return $this->position;
    }

}
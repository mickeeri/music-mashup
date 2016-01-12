<?php

namespace models;

require_once ("models/WebServiceModel.php");


class Album
{
    private $albumID;
    private $name;
    private $artist;
    private $position;
    private $cover;
    private $spotifyURI;
    private $webService;

    /**
     * Album constructor.
     * @param string $name
     * @param string $artist
     * @param string $position
     * @param $cover
     */
    public function __construct($name, $artist, $position, $cover, $spotifyURI)
    {
        if ($name === "") {
            throw new \Exception("Får inte vara tomt");
        }

        $this->name = filter_var($name, FILTER_SANITIZE_STRING);
        $this->artist = filter_var($artist, FILTER_SANITIZE_STRING);
        $this->position = filter_var($position, FILTER_SANITIZE_STRING);

        $this->webService = new \models\WebServiceModel();

        $this->spotifyURI = $spotifyURI;

        if (!$spotifyURI) {
            $this->spotifyURI = $this->webService->getAlbumSpotifyURI($this->artist, $this->name);
        }

        // If no cover scr is provided, fetch one from api.
        if ($cover === "" || $cover === null) {
            // Using other api method to get more info about album.
            $additionalAlbumInfo = $this->getContentFromWebService();

            // Returns default img if not set.
            if (!isset($additionalAlbumInfo->album)) {
                $this->cover = $additionalAlbumInfo;
            } else {
                $this->cover = filter_var($additionalAlbumInfo->album->image[2]->{"#text"}, FILTER_SANITIZE_STRING);
            }

        } else {
            $this->cover = $cover;
        }
    }

    private function getContentFromWebService()
    {
        $wsm = new \models\WebServiceModel();
        return $wsm->getAlbumInfo($this->artist, $this->name);
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

    public function getCover()
    {
        return $this->cover;
    }

    public function getSpotifyURI()
    {
        return $this->spotifyURI;
    }
}
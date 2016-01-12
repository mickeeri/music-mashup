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
     * @param string $cover
     * @param string $spotifyURI
     * @throws \Exception
     */
    public function __construct($name, $artist, $position, $cover, $spotifyURI)
    {
        if (is_string($name) === false || $name === "") {
            throw new \NoAlbumTitleException();
        }

        if (is_string($artist) === false || $artist === "") {
            throw new \NoArtistException();
        }

        if (!$position) {
            throw new \AlbumPositionException();
        }

        if (is_numeric($position) === false || $position < 2000 || $position > date("Y")) {
            throw new \AlbumPositionException();
        }

        // Sanatize and assings values.
        $this->name = filter_var($name, FILTER_SANITIZE_STRING);
        $this->artist = filter_var($artist, FILTER_SANITIZE_STRING);
        $this->position = filter_var($position, FILTER_SANITIZE_STRING);

        // If not provided, get spotifyURI from Spotify Api.
        $this->webService = new \models\WebServiceModel();
        $this->spotifyURI = $spotifyURI;

        if (!$spotifyURI) {

            $this->spotifyURI = $this->webService->getAlbumSpotifyURI($this->artist, $this->name);
        } else {

            if (is_string($spotifyURI) === false) {
                throw new \SpotifyIDException();
            }

            $this->spotifyURI = $spotifyURI;
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
        if (is_numeric($albumID) === false) {
            throw new \AlbumIDException();
        }

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
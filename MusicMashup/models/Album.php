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

        if (is_numeric($position) === false || intval($position) < 1 || intval($position) > 10) {
            throw new \AlbumPositionException();
        }

        if (is_string($cover) === false || $cover === "") {
            throw new \InvalidCoverException;
        }

        // Sanatize and assings values.
        $this->name = filter_var($name, FILTER_SANITIZE_STRING);
        $this->artist = filter_var($artist, FILTER_SANITIZE_STRING);
        $this->position = filter_var($position, FILTER_SANITIZE_STRING);
        $this->cover = filter_var($cover, FILTER_SANITIZE_STRING);

        // If not provided, get spotifyURI from Spotify Api.
        $this->webService = new \models\WebServiceModel();

        if (!$spotifyURI) {
            $this->spotifyURI = $this->webService->getAlbumSpotifyURI($this->artist, $this->name);
        } else {

            if (is_string($spotifyURI) === false) {
                throw new \SpotifyIDException();
            }

            $this->spotifyURI = $spotifyURI;
        }
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

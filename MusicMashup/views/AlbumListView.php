<?php

namespace views;


class AlbumListView
{
    /** @var \models\AlbumList $albumList */
    private $albumList;
    private $errorMessage;


    public function response()
    {
        $ret =
            '<div class="row"><a class="btn waves-effect waves-light indigo darken-2"
                href="'.$_SERVER["PHP_SELF"].'">Tillbaka till listorna</a></div>';

        if (isset($this->errorMessage)) {
            $ret .= '<div class="error error-div">'.$this->errorMessage.'</div>';
        } else {
            $ret .=
                '<div class="row">
                    <h4 class="top-list-headline">De bästa albumen '.$this->albumList->getYear().' enligt
                    <a href="'.$this->albumList->getLink().'">'.$this->albumList->getSource().'</a></h4>
                </div>
                '.$this->renderAlbums().
                '<small>Albuminformation från <img class="last-fm-logo" src="images/Last.fm_Logo_Black.png"/></small>';
        }

        return $ret;
    }

    // Renders all the albums.
    private function renderAlbums()
    {
        $albums = $this->albumList->getAlbums();

        $ret = '';

        /** @var \models\Album $album */
        foreach ($albums as $album) {


            $ret .=
                '<div class="row album-row">
                    <div class="row">
                        <h5>'.$album->getPosition().'. '.$album->getArtist().' - '.$album->getName().'</h5>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <img class="responsive-img" src="'.$album->getCover().'" alt="Omslagsbild för '.$album->getName().'">
                        </div>
                        <div class="col s12 m6 align-right">
                            '.$this->renderAlbumPlaylist($album->getSpotifyURI()).'
                        </div>
                    </div>
                </div>';
        }
        return $ret;
    }

    // Renders link to spotify.
    private function renderAlbumPlaylist($spotifyURI)
    {
        if (!$spotifyURI) {
            return "Spellista saknas.";
        } else {
            return '<a href="'.$spotifyURI.'"><img class="spotify-loggo" src="images/listen_on_spotify-black.svg"
                    alt="Klicka för att lyssna på spotify."/></a>';
        }
    }

    public function setAlbumList($albumList)
    {
        $this->albumList = $albumList;
    }

    public function getErrorMessage()
    {
        if ($this->errorMessage !== "") {
            return '<div class="error error-div">'.$this->errorMessage.'</div>';
        }
    }

    public function setErrorMessage($message)
    {
        $this->errorMessage = $message;
    }


}
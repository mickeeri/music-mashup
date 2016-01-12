<?php

namespace views;


class AlbumListView
{
    /** @var \models\AlbumsOfTheYearList $albumList */
    private $albumList;

    public function response()
    {
        return
            '<div class="row">
                <h4 class="top-list-headline">De bästa albumen '.$this->albumList->getYear().' enligt
                <a href="'.$this->albumList->getLink().'">'.$this->albumList->getSource().'</a></h4>
            </div>
            '.$this->renderAlbums().'
            ';
    }

    private function renderAlbums()
    {
        $albums = $this->albumList->getAlbums();

        $ret = '';

        /** @var \models\Album $album */
        foreach ($albums as $album) {


            $ret .=
'<div class="row album-row">
    <div class="row">
        <h4>'.$album->getPosition().'. '.$album->getArtist().' - '.$album->getArtist().'</h4>
        <span class="top-list-position">'.$album->getPosition().'</span>
        <img class="responsive-img" src="'.$album->getCover().'" alt="Omslagsbild för '.$album->getName().'">
    </div>
    <div class="col s12 m4">
        <h5>'.$album->getName().'</h5>
        <p>'.$album->getArtist().'</p>
    </div>
    <div class="col s12 m4">'.$this->renderAlbumPlaylist($album->getSpotifyURI()).'</div>
</div>';
        }

        return $ret;
    }


//<div class="card">
//    <div class="card-image">
//        <img src="' .$album->getCover(). '" alt="Album cover for ' .$album->getName(). '"/>
//    </div>
//</div>
//<div class="card-content">
//    <span class="card-title">' .$album->getArtist(). '</span>
//    <p>' .$album->getName(). '</p>
//    <span class="album-order-number">' .$album->getPosition(). '</span>
//    </div>
//</div>
//</div>

    private function renderAlbumPlaylist($spotifyURI)
    {
        if (!$spotifyURI) {
            return "Spellista saknas.";
        } else {
            return '<iframe src="https://embed.spotify.com/?uri='.$spotifyURI.'"
                    width="400" height="360" frameborder="0" allowtransparency="true"></iframe>';
        }
    }

    public function setAlbumList($albumList)
    {
        $this->albumList = $albumList;
    }
}
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
                <h4>Top albums of '.$this->albumList->getYear().' by '.$this->albumList->getSource().'</h4>
            </div>
            <div class="row">'.$this->renderAlbums().'</div>
            ';
    }

    private function renderAlbums()
    {
        $albums = $this->albumList->getAlbums();

        $ret = '';

        /** @var \models\Album $album */
        foreach ($albums as $album) {
            $ret .=
                "<div class='col s12 m3 l2'>
                    <div class='card'>
                        <div class='card-image'>
                            <img src='".$album->getCover()."' alt='Album cover for ".$album->getName()."'/>
                        </div>
                        <div class='card-content'>
                            <span class='card-title'>".$album->getArtist()."</span>
                            <p>".$album->getName()."</p>
                            <span class='album-order-number'>".$album->getPosition()."</span>
                        </div>
                    </div>
                </div>
                <div>
                    ".$this->renderAlbumPlaylist($album->getSpotifyURI())."
                </div>";
        }

        return $ret;
    }

    private function renderAlbumPlaylist($spotifyURI)
    {
        if (!$spotifyURI) {
            return "Spellista saknas.";
        } else {
            return '<iframe src="https://embed.spotify.com/?uri='.$spotifyURI.'"
                    width="300" height="380" frameborder="0" allowtransparency="true"></iframe>';
        }
    }

    public function setAlbumList($albumList)
    {
        $this->albumList = $albumList;
    }
}
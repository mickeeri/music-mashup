<?php

namespace models;


class WebServiceModel
{

    /**
     * Using spotify api to get spotify uri.
     * @param string $artist
     * @param string $albumName
     * @return string uri;
     * @throws \Exception
     */
    public function getAlbumSpotifyURI($artist, $albumName)
    {
        $ch = curl_init();

        if (!$ch){
            throw new \CurlInitException();
        }

        $url = "https://api.spotify.com/v1/search?q=album:".curl_escape($ch, $albumName)."+artist:".curl_escape($ch, $artist)."&type=album";

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);

        $result = json_decode($result);

        if (empty($result)) {
            curl_close($ch);
            throw new \WebServiceEmptyResultException(curl_error($ch));
        } else {
            $info = curl_getinfo($ch);
            curl_close($ch);
            if ($info["http_code"] !== 200) {
                throw new \BadResponseCodeException();
            }
        }

        $uri = $result->albums->items[0]->uri;
        return filter_var($uri, FILTER_SANITIZE_STRING);
    }

}
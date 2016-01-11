<?php

namespace models;


class WebServiceModel
{

    public function getAlbumInfo($artist, $albumName)
    {
        // TODO: felhantering vid inget resultat.

        $artist = preg_replace('/\s+/', '+', $artist);
        $albumName = preg_replace('/\s+/', '+', $albumName);
        //$albumName = preg_replace('&', 'and', $albumName);


        $url = 'http://ws.audioscrobbler.com/2.0/?method=album.getinfo&artist='.$artist.'&album='.$albumName.'&lang=sv&api_key='.\Settings::LASTFM_API_KEY.'&format=json';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result);

        if (isset($result->error) || $result->album->image[2]->{"#text"} == "") {

            return "images/default-img.png";
        }

        // object(stdClass)#12 (3) { ["error"]=> int(6) ["message"]=> string(15) "Album not found" ["links"]=> array(0) { } }

        return $result;
    }

    public function getAlbumFromSpotifyAPI($artist, $albumName)
    {
        $artist = preg_replace('/\s+/', '+', $artist);
        $albumName = preg_replace('/\s+/', '+', $albumName);

        $url = "https://api.spotify.com/v1/search?q=album:".$albumName."+artist:".$artist."&type=album";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result);

        return $result;
    }

    public function createSpotifyPlayList(){

        $userID = "me222wm";

        $url = "https://api.spotify.com/v1/users/".$userID."/playlists";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $postString = "group1=$dayAndTime&username=zeke&password=coys&submit=login";

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
        $responseString = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $responseString;

    }

    public function requestAuthorizationCode()
    {
        $clientID = "eabdd691d0c44d609a8ce121d358ef02";
        $redirectURI = "http://localhost:8888/1dv449_projekt/MusicMashup/callback/";

        $requestURI = "https://accounts.spotify.com/authorize/?client_id=".$clientID."&response_type=code&redirect_uri=".$redirectURI;

        //print($requestURI);

        $ch = curl_init($requestURI);

        if (false === $ch) {
            throw new \Exception("Failed to initialize.");
        }

//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_URL, $requestURI);
//        $result = curl_exec($ch);
//        curl_close($ch);


        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
        //curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT,10);
        $output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        //echo 'HTTP code: ' . $httpcode;

        if (false === $output) {
            throw new \Exception(curl_error($ch), curl_errno($ch));
        }

        ddd($output);

        //var_dump($httpcode);

        //echo $output;
    }
}
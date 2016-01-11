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

    /**
     * Using spotify api to generate
     * @param $artist
     * @param $albumName
     * @return mixed
     * @throws \Exception
     */
    public function getAlbumSpotifyURI($artist, $albumName)
    {
        $artist = preg_replace('/\s+/', '+', $artist);
        $albumName = preg_replace('/\s+/', '+', $albumName);

        $url = "https://api.spotify.com/v1/search?q=album:".$albumName."+artist:".$artist."&type=album";

        $ch = curl_init();

        if (!$ch){
            throw new \Exception("Failed to initialize.");
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);


        $result = json_decode($result);

        if (empty($result)) {
            curl_close($ch);
            throw new \Exception(curl_error($ch));
        } else {
            $info = curl_getinfo($ch);
            curl_close($ch);

            if ($info["http_code"] !== 200) {
                throw new \Exception("Fel vid hämtning av album från Spotify.");
            }
        }

        $uri = $result->albums->items[0]->uri;

//        if (!$uri) {
//            throw new \Exception("Kunde inte hitta album på Spotify.");
//        }

        return $uri;
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

        //$requestURI = "https://accounts.spotify.com/authorize/?client_id=".$clientID."&response_type=code&redirect_uri=".$redirectURI;
        $requestURI = "https://accounts.spotify.com/api/token";
        //print($requestURI);

        $ch = curl_init($requestURI);

        if (false === $ch) {
            throw new \Exception("Failed to initialize.");
        }

        $bodyParameter = array("grant_type" => "client_credentials");
        $bodyParameter = json_encode($bodyParameter);

        $ch = curl_init($requestURI);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyParameter);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );



        if (false === $output) {
            throw new \Exception(curl_error($ch), curl_errno($ch));
        }

        ddd($output);

        //var_dump($httpcode);

        //echo $output;
    }
}
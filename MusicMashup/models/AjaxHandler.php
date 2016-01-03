<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $artist = $_POST["artist"];

    var_dump($name);
    var_dump($artist);
}






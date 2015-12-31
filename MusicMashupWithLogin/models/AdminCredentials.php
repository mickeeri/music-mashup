<?php
/**
 * Created by PhpStorm.
 * User: Micke
 * Date: 2015-12-31
 * Time: 10:54
 */

namespace models;


class AdminCredentials
{
    private $userName;
    private $password;
    private $client;

    public function __construct($name, $password, $client)
    {
        $this->userName = htmlspecialchars($name);
        $this->password = htmlspecialchars($password);
        $this->client = $client;
    }

}
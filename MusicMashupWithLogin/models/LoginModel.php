<?php

namespace models;


class LoginModel
{
    private static $adminSession = "LoginModel::UserSession";
    private static $userClientSession = "LoginModel::UserClientSession";
    private static $folder = "data/";
    private $userFacade;

    public function __construct()
    {
        $this->correctUserName = "admin";
        $this->correctPassword = "pass";
    }

    public function isLoggedIn($client)
    {
        return isset($_SESSION[self::$adminSession]) && $_SESSION[self::$userClientSession] === $client;
    }

    public function authorize($username, $password)
    {
        if ($username) {
        }
    }
}
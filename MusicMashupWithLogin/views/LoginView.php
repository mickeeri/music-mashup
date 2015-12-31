<?php

namespace views;


class LoginView
{
    private static $login = "LoginView::Login";
    private static $postUserName = "LoginView::Username";
    private static $postPassword = "LoginView::Password";

    private $model;

    public function __construct(\models\LoginModel $model)
    {
        $this->model = $model;
    }

    public function response()
    {
        if ($this->model->isLoggedIn($this->getUserClient())) {
        }
    }

    public function getCredentials()
    {
        return new \models\AdminCredentials($this->getUserName(), $this->getPassword(), $this->getUserClient());
    }

    private function getUserName()
    {
        if (isset($_POST[self::$postUserName])) {
            return trim($_POST[self::$postUserName]);
        }
    }

    private function getPassword()
    {
        if (isset($_POST[self::$postPassword])) {
            return trim($_POST[self::$postPassword]);
        }
    }

    private function getUserClient()
    {
        return new \models\UserClient($_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"]);
    }
}
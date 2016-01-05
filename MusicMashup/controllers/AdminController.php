<?php

namespace controllers;

//require_once ("models/AlbumListDAL.php");
//require_once ("models/AjaxAjaxFacade.php");


class AdminController
{
    function __construct()
    {
//        $this->dal = new \models\AlbumListDAL($db);
//        $this->facade = new \models\Facade($this->dal);
    }

    /**
     * @return \views\AdminView
     */
    public function getAdminView()
    {
        return new \views\AdminView();
    }


}
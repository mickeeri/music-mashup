<?php

namespace controllers;

require_once ("views/ListView.php");
require_once ("models/AlbumsOfTheYearList.php");
require_once ("models/Album.php");


class HomeController
{
    private $facade;
    //private $view;
    private $navigation;

    public function __construct(\models\Facade $facade, \views\NavigationView $nv)
    {
        $this->facade = $facade;
        //$this->view = $view;
        $this->navigation = $nv;
    }

    public function handleInput()
    {
        $content = "";

        if ($this->navigation->onYearPage()) {

            $content = $this->renderListsForYear();
        }

        return $content;
    }

    private function renderListsForYear()
    {
        $year = $this->navigation->getYearToShow();
        $lists = $this->facade->getListsForYear($year);
        $listView = new \views\ListView($lists);
        return $listView->render();
    }



}
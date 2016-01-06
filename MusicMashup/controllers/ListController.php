<?php

namespace controllers;


class ListController
{

    private $view;
    private $navigationView;
    private $facade;


    public function __construct($view,\views\NavigationView $nv, \models\Facade $f)
    {
        $this->view = $view;
        $this->navigationView = $nv;
        $this->facade = $f;
    }

    /**
     *
     */
    public function provideLists()
    {
        $year = $this->navigationView->getYearToShow();
        $this->view->setLists($this->facade->getListsForYear($year));
    }

    public function provideAlbumList()
    {
        $listID = $this->navigationView->getAlbumsToShow();
        $this->view->setAlbumList($this->facade->getListByID($listID));
    }





}
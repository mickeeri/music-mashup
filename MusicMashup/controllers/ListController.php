<?php

namespace controllers;


class ListController
{

    private $view;
    private $navigationView;
    private $facade;


    public function __construct(\views\AlbumListView $view,\views\NavigationView $nv, \models\Facade $f)
    {
        $this->view = $view;
        $this->navigationView = $nv;
        $this->facade = $f;
    }

    /**
     *
     */
//    public function provideLists()
//    {
//        $year = $this->navigationView->getYearToShow();
//        $this->view->setLists($this->facade->getListsForYear($year));
//    }

    public function provideAlbumList()
    {
        try {
            $listID = $this->navigationView->getAlbumsToShow();
            $this->view->setAlbumList($this->facade->getListByID($listID));
        } catch (\WebServiceEmptyResultException $e) {
            $this->view->setErrorMessage("Kunde inte hämta information om albumen från våran web service.");
        } catch (\Exception $e) {
            $this->view->setErrorMessage("Ett fel uppstod när albumen skulle hämtas");
        }

    }
}
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
     * Renders top-lists.
     */
    public function provideAlbumList()
    {
        try {
            $listID = $this->navigationView->getAlbumsToShow();
            $this->view->setAlbumList($this->facade->getListByID($listID));
        } catch (\WebServiceEmptyResultException $e) {
            $this->view->setErrorMessage("Kunde inte hämta information om albumen.");
        } catch (\Exception $e) {
            $this->view->setErrorMessage("Ett fel uppstod när albumen skulle hämtas");
        }
    }
}

<?php

namespace controllers;

// Views
require_once ("views/HomeView.php");
require_once ("views/AdminView.php");
require_once ("views/AlbumListView.php");



require_once ("models/Facade.php");
require_once ("models/AlbumListDAL.php");

// Controllers
require_once ("controllers/AdminController.php");
require_once ("controllers/HomeController.php");
require_once ("controllers/ListController.php");

class MasterController
{
    private $navigationView;
    private $view;

    public function __construct(\views\NavigationView $nv)
    {

        $this->facade = new \models\Facade();
        $this->navigationView = $nv;
    }

    public function handleInput()
    {
        $this->navigationView->setYears($this->facade->getYears());


        if ($this->navigationView->onAdminPage()) {

            $controller = new \controllers\AdminController();
            $this->view = $controller->getAdminView();
        }

        elseif ($this->navigationView->onYearPage()) {

            $this->view = new \views\ListView();
            $controller = new \controllers\ListController($this->view,$this->navigationView, $this->facade);
            $controller->provideLists();
        }

        elseif ($this->navigationView->onAlbumListPage()) {
            $this->view = new \views\AlbumListView();
            $controller = new \controllers\ListController($this->view,$this->navigationView, $this->facade);
            $controller->provideAlbumList();
        }

        else {
            // TODO homecontroller.
            //$controller = new \controllers\HomeController($this->facade, $this->navigationView);
            $this->view = new \views\HomeView();
        }

        // TODO: close db.
    }

    public function generateOutput()
    {
        return $this->view;
    }
}
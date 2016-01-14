<?php

namespace controllers;

// Views
require_once ("views/HomeView.php");
require_once ("views/AdminView.php");
require_once ("views/AlbumListView.php");
require_once ("views/AdminListsView.php");


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

            //$this->view = new \views\AdminListsView();
            $this->view = new \views\AdminView();
            //$controller = new \controllers\AdminController($this->view, $this->facade);

        }

        elseif ($this->navigationView->onAdminAlbumListsPage() || $this->navigationView->onDeleteListPage()) {

            // If there is a listID user wants to delete a list.
            $listID = $this->navigationView->getAlbumToDelete();

            $this->view = new \views\AdminListsView();
            $controller = new \controllers\AdminController($this->view, $this->facade);

            if (isset($listID)) {
                $controller->listToDelete = $listID;
            }

            $controller->getLists();

            //$this->view = $controller->getAdminView();
        }

        elseif ($this->navigationView->onAlbumListPage()) {
            $this->view = new \views\AlbumListView();
            $controller = new \controllers\ListController($this->view,$this->navigationView, $this->facade);
            $controller->provideAlbumList();
        }



        else {
            $this->view = new \views\HomeView();
            $controller = new \controllers\HomeController($this->view, $this->facade, $this->navigationView);
            $controller->provideTopLists();
        }

        // TODO: close db.
    }

    public function generateOutput()
    {
        return $this->view;
    }
}
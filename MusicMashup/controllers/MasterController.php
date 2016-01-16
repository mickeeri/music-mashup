<?php

namespace controllers;

// Views
require_once ("views/HomeView.php");
require_once ("views/AdminView.php");
require_once ("views/AlbumListView.php");
require_once ("views/AdminListsView.php");

// Models
require_once ("models/Facade.php");
require_once ("models/AlbumListDAL.php");

// Controllers
require_once ("controllers/AdminController.php");
require_once ("controllers/HomeController.php");
require_once ("controllers/ListController.php");

// Controller that decides which view and controller to instanciate.
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


        // Adminview is the form for adding new list.
        if ($this->navigationView->onAdminPage()) {
            $this->view = new \views\AdminView();
        }

        elseif ($this->navigationView->onAdminAlbumListsPage() || $this->navigationView->onDeleteListPage()) {

            // If there is a listID user wants to delete a list.
            $listID = $this->navigationView->getAlbumToDelete();

            $this->view = new \views\AdminListsView();
            $controller = new \controllers\AdminController($this->view, $this->facade, $this->navigationView);

            if (isset($listID)) {
                $controller->listToDelete = $listID;
            }

            $controller->getLists();

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
    }

    // Returns view to render.
    public function generateOutput()
    {
        return $this->view;
    }
}
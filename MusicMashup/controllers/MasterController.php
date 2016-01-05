<?php

namespace controllers;

// Views
require_once ("views/HomeView.php");
require_once ("views/AdminView.php");
require_once ("models/Facade.php");
require_once ("models/AlbumListDAL.php");

// Controllers
require_once ("controllers/AdminController.php");

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

        else {
            // TODO homecontroller.

            $this->view = new \views\HomeView();
            //$this->view->setYears($this->facade->getYears());
        }

        // TODO: close db.
    }

    public function generateOutput()
    {
        return $this->view;
    }
}
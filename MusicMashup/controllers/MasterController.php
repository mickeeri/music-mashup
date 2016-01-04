<?php

namespace controllers;
// Views
require_once ("views/HomeView.php");
require_once ("views/AdminView.php");

// Controllers
require_once ("controllers/AdminController.php");

class MasterController
{
    private $navigationView;
    private $view;

    public function __construct(\views\NavigationView $nv)
    {


        $this->navigationView = $nv;
    }

    public function handleInput()
    {

        if ($this->navigationView->onAdminPage()) {

            $controller = new \controllers\AdminController();
            $this->view = $controller->getAdminView();
        }

        else {
            $this->view = new \views\HomeView();
        }

        // TODO: close db.
    }

    public function generateOutput()
    {
        return $this->view;
    }
}
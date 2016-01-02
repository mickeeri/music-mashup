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

    public function __construct(\views\NavigationView $nv)
    {
        $this->navigationView = $nv;
    }

    public function handleInput()
    {

        if ($this->navigationView->onAdminPage()) {

            $this->view = new \views\AdminView();
            //$controller = new \controllers\AdminController($this->view, $this->$this->navigationView);
        }

        else {
            $this->view = new \views\HomeView();
        }
    }

    public function generateOutput()
    {
        return $this->view;
    }
}
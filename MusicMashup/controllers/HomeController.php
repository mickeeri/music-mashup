<?php

namespace controllers;

require_once ("models/AlbumList.php");
require_once ("models/Album.php");


class HomeController
{
    private $facade;
    private $view;
    private $navigation;

    /**
     * HomeController constructor.
     * @param \views\HomeView $view
     * @param \models\Facade $facade
     * @param \views\NavigationView $nv
     */
    public function __construct(\views\HomeView $view, \models\Facade $facade, \views\NavigationView $nv)
    {
        $this->facade = $facade;
        $this->view = $view;
        $this->navigation = $nv;
    }

    public function provideTopLists()
    {
        try {
            $years = $this->facade->getYearsAndLists();
            $this->view->setYears($years);

        } catch (\FetchAlbumListsException $e) {
            $this->view->setErrorMessage("Fel uppstod när listorna skulle hämtas från databasen.");
        } catch (\Exception $e) {
            $this->view->setErrorMessage("Ett fel uppstod.");
        }
    }



}
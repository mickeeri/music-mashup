<?php

namespace controllers;

class AdminController
{
    private $facade;
    private $view;
    private $navigationView;
    public $listToDelete;


    function __construct(\views\AdminListsView $alv, \models\Facade $facade, \views\NavigationView $nv)
    {
        $this->facade = $facade;
        $this->view = $alv;
        $this->navigationView = $nv;
    }

    /**
     * @return \views\AdminView
     */

    public function getLists()
    {

        if (isset($this->listToDelete)) {
            try {
                $list = $this->facade->getListByID($this->listToDelete);
                $this->view->setListToDelete($list);
            } catch (\GetDataException $e) {
                $this->view->setErrorMessage("Fel: hittar inte listan som ska raderas.");
            } catch (\Exception $e) {
                $this->view->setErrorMessage("Ett fel uppstod.");
            }
        }

        // Delete list.
        if ($this->view->wantsToDelteList()) {

            $listID = $this->view->getListToDelete();
            $this->facade->deleteList($listID);
            $this->navigationView->redirectToAdminListView("Listan är raderad.");
        }

        try {
            $years = $this->facade->getYearsAndLists();
            $this->view->setYears($years);
        } catch (\Exception $e) {
            $this->view->setErrorMessage("Ett fel uppstod.");
        }
    }




}
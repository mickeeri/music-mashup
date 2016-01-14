<?php

namespace controllers;

class AdminController
{
    private $facade;
    private $view;
    public $listToDelete;

    function __construct(\views\AdminListsView $alv, \models\Facade $facade)
    {
        $this->facade = $facade;
        $this->view = $alv;
    }

    /**
     * @return \views\AdminView
     */
//    public function getAdminView()
//    {
//        return new \views\AdminView();
//    }

    public function getLists()
    {
        try {
            if (isset($this->listToDelete)) {
                $list = $this->facade->getListByID($this->listToDelete);
                $this->view->setListToDelete($list);
            }


            if ($this->view->wantsToDelteList()) {
                $listID = $this->view->getListToDelete();
                $this->facade->deleteList($listID);
                var_dump("List removed");
            }



            $years = $this->facade->getYearsAndLists();
            $this->view->setYears($years);

        } catch (\FetchAlbumListsException $e) {

        }
    }




}
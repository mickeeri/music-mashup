<?php

namespace views;


class AdminListsView
{

    private $years;
    private $listToDelete;
    private static $postDeleteList = "deletelist";

    public function response()
    {


        if (isset($this->listToDelete)) {
            return $this->renderDeleteListConfirmation($this->listToDelete);
        } else {
            return $this->renderYears();
        }
    }

    private function renderYears()
    {
        $ret = '<h4>Alla topplistor</h4>
                <a href="?'.\Settings::SECRET_ADMIN_URL.'">Tillbaka</a>';

        /** @var \models\Year $year */
        foreach ($this->years as $year) {
            $ret .=
                '<div class="row">
                    <h5>'.$year->getYear().'</h5>
                    <ul class="collection">'.$this->renderLists($year->getLists()).'</ul>
                </div>';
        }

        return $ret;
    }

    private function renderLists($lists)
    {
        $ret = '';

        /** @var \models\AlbumsOfTheYearList $list */
        foreach ($lists as $list) {
            $ret .= '<li class="collection-item">'.$list->getSource().'<a href="'.$_SERVER['REQUEST_URI'].'/'.NavigationView::$removeListURI.'='.$list->getListID().'">
            <i class="material-icons right hoverable">delete</i></a></li>';
        }

        return $ret;


    }

    /**
     * @param \models\AlbumsOfTheYearList $list
     * @return string
     */
    private function renderDeleteListConfirmation($list)
    {
        return
            '<div class="row">
                <p class="flow-text">Är du säker på att du vill radera listan från '.$list->getSource().'('.$list->getYear().')?</p>
                <a class="btn waves-effect waves-light indigo darken-2" href="?'.\Settings::SECRET_ADMIN_URL.'/'.NavigationView::$adminListsURI.'">Nej, gå tillbaka</a>
                <form method="post">
                    <input type="hidden" name="'.self::$postDeleteList.'" value="'.$list->getListID().'">
                    <input class="btn waves-effect waves-light indigo darken-2" type="submit" value="Ja jag är säker">
                </form>
            </div>';
    }

    public function setYears($years)
    {
        $this->years = $years;
    }

    /**
     * @param \models\AlbumsOfTheYearList $list
     */
    public function setListToDelete($list)
    {
        $this->listToDelete = $list;
    }

    public function wantsToDelteList()
    {
        if (isset($_POST[self::$postDeleteList])) {
            return true;
        }

        return false;
    }

    public function getListToDelete()
    {
        return $_POST[self::$postDeleteList];
    }
    
}
<?php

namespace views;


class AdminListsView
{

    private $years;
    private $listToDelete;
    private static $postDeleteList = "deletelist";
    private $errorMessage;

    public function response()
    {
        $ret = $this->getErrorMessage();

        if (isset($_SESSION[NavigationView::$sessionSaveLocation])) {
            $ret .= $this->getListDeletedMessage();
            $_SESSION[NavigationView::$sessionSaveLocation] = null;
        }
        // Either show the list or delete confirmation form if user has asked to delete list.
        if (isset($this->listToDelete)) {
            $ret .= $this->renderDeleteListConfirmation($this->listToDelete);
        } else {
            $ret .= $this->renderYears();
        }

        return $ret;
    }

    /**
     * Render the years and lists for each year.
     * @return string html
     */
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

    /**
     * @param array $lists
     * @return string html
     */
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
     * Display confirmation form for removal of list.
     * @param \models\AlbumsOfTheYearList $list
     * @return string
     */
    private function renderDeleteListConfirmation($list)
    {
        return
            '<div class="row">
                <p class="flow-text">Är du säker på att du vill radera listan från '.$list->getSource().' ('.$list->getYear().')?</p>
                    <div class="col s6 m2">
                    <a class="btn" href="?'.\Settings::SECRET_ADMIN_URL.
                            '/'.NavigationView::$adminListsURI.'">Avbryt</a>
                    </div>
                <div class="col s6 m2">
                    <form method="post">
                        <input type="hidden" name="'.self::$postDeleteList.'" value="'.$list->getListID().'">
                        <input class="btn" type="submit" value="Ja">
                    </form>
                </div>
            </div>';
    }

    /**
     * @param array $years
     */
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

    /**
     * @return bool true if user has asked to delete list.
     */
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

    public function getErrorMessage()
    {
        if ($this->errorMessage !== "") {
            return '<div class="error error-div">'.$this->errorMessage.'</div>';
        }
    }

    public function setErrorMessage($message)
    {
        $this->errorMessage = $message;
    }

    public function getListDeletedMessage() {
        $message = $_SESSION[NavigationView::$sessionSaveLocation];
        return '<div class="success success-div">
                    <img class="close-message-icon" src="images/close_icon.svg">
                    '.$message.'
                </div>';
    }
    
}
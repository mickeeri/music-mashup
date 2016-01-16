<?php

namespace views;


class HomeView
{

    private $years;
    private $errorMessage;

    public function __construct()
    {
        // Empty
    }

    public function response()
    {
        return
            ''.$this->getErrorMessage().'
            <div class="row jumbotron blue-grey lighten-5">
                <h2>Årets bästa musik</h2>
                <p class="flow-text">Här presenteras de bästa albumen från år till år</p>
            </div>
            '.$this->renderYears().'
        ';
    }

    private function renderYears() {
        $ret = '';

        /** @var \models\Year $year */
        foreach ($this->years as $year) {
            $ret .=
                '<div class="row">
                    <h4>Listor från '.$year->getYear().'</h4>
                    <div class="row">'.$this->renderLists($year->getLists()).'</div>
                </div>';
        }

        return $ret;
    }

    private function renderLists($lists)
    {
        $ret = '';

        /** @var \models\AlbumList $list */
        foreach ($lists as $list) {
            $ret .=
                '<div class="col s12 m6 l4">
                    <div class="card blue-grey lighten-5">
                        <div class="card-content">
                            <span class="card-title truncate">'.$list->getSource().'</span>
                        </div>
                        <div class="card-action">
                            <a class="list-link" href="?albumlist='.$list->getListID().'">Se lista</a>
                        </div>
                    </div>
                </div>';
        }

        return $ret;
    }

    public function setYears($years)
    {
        $this->years = $years;
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
}
<?php

namespace views;


class ListView
{
    private $lists;

    public function __construct()
    {

    }

    public function response()
    {
        return
            '<div class="row">
                <h4>Albums of the year for '.$this->lists[0]->getYear().'</h4>
            </div>
            <div class="row">'.$this->renderLists().'</div>';
    }

    /**
     *
     */
    private function renderLists()
    {
        $ret = "";

        /** @var \models\AlbumsOfTheYearList $list */
        foreach ($this->lists as $list) {
            $ret .=
                '<div class="col s6 m4">
                    <div class="card blue-grey darken-1">
                    <div class="card-content white-text">
                        <span class="card-title">'.$list->getSource().'</span>
                        <div class="card-action">
                            <a href="?albumlist='.$list->getListID().'">See list</a>
                        </div>
                    </div>
                    </div>
                </div>';
        }

        return $ret;
    }

    public function setLists($lists)
    {
        $this->lists = $lists;
    }

}
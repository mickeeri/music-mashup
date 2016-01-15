<?php

namespace views;


class AdminView
{
    private $numerOfAlbumsInList = 2;

    public function response ()
    {
        return
            '<a href="?'.$_SERVER['QUERY_STRING'].'/'.NavigationView::$adminListsURI.'">Se alla listor</a>
            '.$this->generateCreateListForm() . $this->generateAlbumSearchForm()
            .$this->generateAlbumsOfTheYearList();

    }

    /**
     * @return string html with form for creating list.
     */
    private function generateCreateListForm()
    {
        return
        '<div class="row">
            <div class="col s12 m8 offset-m2">
                <h4>1. Lägg till lista</h4>
                <div id="create-list-messages" style="display:none;">
                </div>
                <form id="create-list-form">
                    <div class="row">
                        <div class="input-field">
                            <input id="source" type="text" class="validate">
                            <label class="active" for="source">Källa</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field">
                            <input id="link" type="text" class="validate">
                            <label for="link">Länk till källa</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field">
                            <select id="year" class="browser-default">
                                <option value="" disabled selected>Välj år</option>
                                '.$this->generateYearOptionField().'
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <button class="btn waves-effect waves light right" type="submit" id="createListButton">Skapa ny lista</button>
                    </div>
                </form>
            </div>
        </div>';
    }

    /**
     * @return string html with search form for searching for albums.
     */
    private function generateAlbumSearchForm()
    {
        return
        '<div class="row">            
            <div class="col s12 m8 offset-m2">
                <h4 id="find-album-header">2. Hitta album</h4>
                <small>powered by </small><a href="http://www.last.fm/"><img id="last-fm-logo" src="images/Last.fm_Logo_Black.png" alt="Logga från last.fm"></a>
                <div id="search-form-message" style="display:none;"></div>
                <form id="album-form" method="post">
                    <div class="row">
                        <div class="input-field col m11">
                            <input id="album-search-field" type="text" class="validate">
                            <label for="album-search-field">Album name</label>
                        </div>
                        <button class="btn-floating waves-effect waves light right" type="submit" id="album-search-button">
                            <i class="material-icons right">search</i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row" id="results" style="display:none;"></div>
        ';
    }

    /**
     * @return string html with empty list of top albums.
     */
    private function generateAlbumsOfTheYearList()
    {
        return
        '<div class="row">
            <div id="album-list-div" class="col s12 m12">
                <h4>New top '.$this->numerOfAlbumsInList.'</h4>
                <div id="save-message" style="display: none;"></div>
                <ul id="top-albums" class="collection">
                    '.$this->generateAlbumListItems().'
                </ul>
            </div>
        </div>';
    }

    /**
     * @return string
     */
    private function generateAlbumListItems()
    {
        $ret = "";

        for ($i = 1; $i <= $this->numerOfAlbumsInList; $i++) {
            $ret .=
                '<li id="album-li-'.$i.'" class="collection-item avatar top-albums">
                    <span class="album-order-number">'.$i.'</span>
                </li>';
        }

        return $ret;

    }

    /**
     * Generates option fields for year select.
     * @return string
     */
    private function generateYearOptionField(){

        $currentYear = intval(date("Y"));

        $ret = "";

        for ($i = $currentYear; $i >= 2000; --$i) {

            $ret .= '<option value="'.$i.'">'.$i.'</option>';

        }

        return $ret;
    }
}
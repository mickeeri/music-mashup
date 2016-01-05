<?php

namespace views;


class AdminView
{
//    private static $newListUrl = \Settings::SECRET_ADMIN_URL . "/newtopten";
//
//
//    // Input id:s
//    private static $yearInputID = "year";
//    private static $artistInputID = "artist";
//    private static $recordInputID = "record";
//    private static $submitButton = "submit";

    public function response ()
    {
        return $this->generateCreateListForm() . $this->generateAlbumSearchForm()
            . $this->generateAlbumsOfTheYearList();

    }

    private function generateCreateListForm()
    {
        // TODO: Gör det möjligt att ange en länk till listan.

        return
        '<div class="row">
            <div class="col s12 m6 offset-m3">
                <h4>1. Create List</h4>
                <div id="create-list-message" style="display:none;"></div>
                <form id="create-list-form">
                    <div class="row">
                        <div class="input-field">
                            <input id="source" type="text" class="validate browser-default">
                            <label for="source">Source</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field">
                            <input id="link" type="text" class="validate">
                            <label for="link">Link to source</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field">
                            <select id="year" class="browser-default">
                                <option value="" disabled selected>Choose year</option>
                                '.$this->generateYearOptionField().'
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <button class="btn waves-effect waves light right" type="submit" id="createListButton">Add</button>
                    </div>
                </form>
            </div>
        </div>';
    }

    private function generateAlbumSearchForm()
    {
        return
        '<div class="row">
            <div class="col s12 m6 offset-m3">
                <h4>2. Add albums</h4>
                <div id="search-form-message" style="display:none;"></div>
                <form id="album-form" method="post">
                    <div class="row">
                        <div class="input-field col m11">
                            <input id="album-search-field" type="text" class="validate" required>
                            <label for="album-search-field">Album name</label>
                        </div>
                        <button class="btn-floating waves-effect waves light right" type="submit" id="album-search-button">
                            <i class="material-icons right">search</i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row" id="results"></div>
        ';
    }

    private function generateAlbumsOfTheYearList()
    {
        return
        '<div class="row">
            <div id="album-list-div" class="col s12 m12">
                <h4>New list</h4>
                <div id="save-message" style="display: none;"></div>
                <ul id="top-albums" class="collection">
                    <li id="album-li-1" class="collection-item avatar top-album">
                        <span class="album-order-number">1</span>
                    </li>
                    <li id="album-li-2" class="collection-item avatar top-album">
                        <span class="album-order-number">2</span>
                    </li>
                    <li id="album-li-3" class="collection-item avatar top-album">
                        <span class="album-order-number">3</span>
                    </li>
                </ul>
            </div>
        </div>';
    }

    private function generateYearOptionField(){

        $currentYear = intval(date("Y"));

        $ret = "";

        for ($i = $currentYear; $i >= 2000; --$i) {

            $ret .= '<option value="'.$i.'">'.$i.'</option>';

        }

        return $ret;
    }
}
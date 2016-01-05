<?php

namespace views;


class AdminView
{
    private static $newListUrl = \Settings::SECRET_ADMIN_URL . "/newtopten";


    // Input id:s
    private static $yearInputID = "year";
    private static $artistInputID = "artist";
    private static $recordInputID = "record";
    private static $submitButton = "submit";

    public function response ()
    {
        return

            '<div class="content">
                <h2>Admin menu</h2>
                '.$this->generateCreateListForm().'
                '.$this->generateAlbumSearchForm().'
            </div>
            ';
    }

    private function generateCreateListForm()
    {
        // TODO: Gör det möjligt att ange en länk till listan.

        return
        '<div class="row">
            <h4>1. Create List</h4>
            <div id="create-list-message" style="display:none;"></div>
            <form id="create-list-form" class="col s12 m8">
                <div class="row">
                    <div class="input-field">
                        <input id="source" type="text" class="validate">
                        <label for="source">Source</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field">
                        <input id="year" type="text" class="validate">
                        <label for="year">Year</label>
                    </div>
                </div>
                <div class="row">
                    <button class="btn waves-effect waves light" type="submit" id="createListButton">Add</button>
                </div>
            </form>
        </div>';
    }

    private function generateAlbumSearchForm()
    {
        return
        '<div class="row">
            <h4>2. Add albums</h4>
            <div id="search-form-message" style="display:none;"></div>
            <form id="album-form" method="post">
                <div class="row">
                    <div class="input-field col s10 m8">
                        <input id="album-search-field" type="text" class="validate">
                        <label for="album-search-field">Album name</label>
                    </div>
                    <div class="col s1 m8">
                        <button class="btn-floating waves-effect waves light" type="submit" id="album-search-button" ">
                        <i class="material-icons right">search</i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="row" id="results"></div>
        ';
    }
}
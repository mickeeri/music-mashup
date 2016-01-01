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

            '
            <h2>Admin menu</h2>
            <div class="row">
            '.$this->generateTopListForm().'
            </div>
            ';
    }

    private function generateTopListForm()
    {
        return
        '<div id="form-messages"></div>
        <h4>Album search</h4>
        <form id="album-form" class="col s12" method="post">
            <div class="row">
                <div class="col s8">
                    <input id="'.self::$artistInputID.'" name="'.self::$artistInputID.'" type="text" class="validate">
                    <label for="'.self::$artistInputID.'">Album title</label>
                </div>
            </div>
            <button class="btn waves-effect waves light" type="submit" name="'.self::$submitButton.'">Search
            <i class="material-icons right">send</i>
            </button>
        </form>
        <div>
            <ul id="results"></ul>
        </div>
        ';
    }


    public function onNewListPage()
    {
        return isset($_GET[self::$newListUrl]);
    }
}
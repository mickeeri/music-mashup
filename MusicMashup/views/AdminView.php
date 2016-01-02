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
                '.$this->generateTopListForm().'
            </div>
            ';
    }

    private function generateTopListForm()
    {
        return
        '<div id="form-messages"></div>
        <div class="row">
            <h4>Album search</h4>
            <form id="album-form" class="col s10 m8" method="post">
                <div class="row">
                    <div class="col s8">
                        <input id="'.self::$artistInputID.'" name="'.self::$artistInputID.'" type="text" class="validate">
                        <label for="'.self::$artistInputID.'">Album title</label>
                    </div>
                    <button class="btn-floating waves-effect waves light" type="submit" name="'.self::$submitButton.'">
                    <i class="material-icons right">search</i>
                    </button>
                </div>
            </form>
        </div>
        <div class="row" id="results"></div>
        ';
    }


    public function onNewListPage()
    {
        return isset($_GET[self::$newListUrl]);
    }
}
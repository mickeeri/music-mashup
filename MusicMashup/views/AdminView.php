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

            '<h1>Hello admin</h1>
            <h2>Admin menu</h2>
            <div class="row">
            '.$this->generateTopListForm().'
            </div>
            ';
    }

    private function generateTopListForm()
    {
        return
        '<form class="col s12">
            <div class="row">
                <div class="col s4">
                    <input id="'.self::$yearInputID.'" type="text" class="validate">
                    <label for="'.self::$yearInputID.'">Year</label>
                </div>
            </div>

            <div class="row">
                <div class="col s8">
                    <input id="'.self::$artistInputID.'" type="text" class="validate">
                    <label for="'.self::$artistInputID.'">Artist</label>
                </div>
            </div>

            <div class="row">
                <div class="col s8">
                    <input id="'.self::$recordInputID.'" type="text" class="validate">
                    <label for="'.self::$recordInputID.'">Record</label>
                </div>
            </div>
            <button class="btn waves-effect waves light" type="submit" name="'.self::$submitButton.'">Submit
            <i class="material-icons right">send</i>
            </button>
        </form>
        ';
    }


    public function onNewListPage()
    {
        return isset($_GET[self::$newListUrl]);
    }
}
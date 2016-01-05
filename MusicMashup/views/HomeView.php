<?php

namespace views;


class HomeView
{
    private $years;

    public function response()
    {
        return '
            <h2>Välkommen till startsidan</h2>
        ';
    }
}
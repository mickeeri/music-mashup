<?php

namespace models;

class Year
{
    private $year;
    private $lists;

    public function __construct($year, $lists)
    {
        $this->year = $year;
        $this->lists = $lists;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function getLists()
    {
        return $this->lists;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Micke
 * Date: 2015-12-31
 * Time: 12:33
 */

namespace controllers;


class AdminController
{
    private $view;
    private $facade;
    private $navigationView;

    function __construct($view, \views\NavigationView $nv)
    {
        $this->view = $view;
        $this->navigationView = $nv;
    }
}
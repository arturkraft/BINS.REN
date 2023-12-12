<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP version 8.3
 */
class Home extends \Core\Controller
{

    /**
     * Before filter
     *
     * @return void
     */
    protected function before()
    {
        //echo "(before) ";
        //return false;
    }

    /**
     * After filter
     *
     * @return void
     */
    protected function after($name)
    {
        //echo " (after)";
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Home/index.html', [
            'location'    => 'Renfrewshire',
            'locations'   => \App\Config::LOCATIONS
        ]);
    }
}

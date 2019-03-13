<?php
/**
 * Created by PhpStorm.
 * User: 1burg
 * Date: 3/13/2019
 * Time: 10:58 PM
 */

namespace bjz\portal\controller;


use bjz\portal\view\View;

class SearchController extends Controller
{
    public function searchPageAction()
    {
        $view = new View('searchPage');
        echo $view->render();
    }
}
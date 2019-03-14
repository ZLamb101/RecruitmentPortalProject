<?php
/**
 * Created by PhpStorm.
 * User: 1burg
 * Date: 3/13/2019
 * Time: 10:58 PM
 */

namespace bjz\portal\controller;


use bjz\portal\view\View;

/**
 * Class SearchController
 *
 * Class controls all actions relevant to the candidateCollection Model
 *
 * @package bjz\portal\controller
 */
class SearchController extends Controller
{
    /**
     * Action to load the searchPage
     */
    public function indexAction()
    {
        $view = new View('searchPage');
        echo $view->render();
    }

    /**
     * Function to search through all candidates
     */
    public function searchAction()
    {
        //To complete
    }
}
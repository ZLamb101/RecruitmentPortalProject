<?php

namespace bjz\portal\controller;


use bjz\portal\view\View;
session_start();

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
     * Checks if the user is logged in as an Employer and if so grants them access to this page
     */
    public function indexAction()
    {
        if($_SESSION["loginStatus"] == Controller::EMPLOYER) {
            $view = new View('searchPage');
            echo $view->render();
        } else if($_SESSION["loginStatus"] == Controller::CANDIDATE){
            $this->redirect('candidateHomePage');
        } else {
            $this->redirect('home');
        }
    }

    /**
     * Function to search through all candidates
     */
    public function searchAction()
    {
        //To complete
    }
}
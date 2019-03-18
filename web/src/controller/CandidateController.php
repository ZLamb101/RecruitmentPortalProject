<?php

namespace bjz\portal\controller;
use bjz\portal\view\View;
session_start();

/**
 * Class CandidateController
 *
 * Class controls all actions relevant to the Candidate Model
 *
 * @package bjz\portal\controller
 */
class CandidateController extends UserController
{
    /**
     * Action to load the candidateHomePage
     * Checks if the user is logged in as a candidate and if so grants them access to this page
     */
    public function indexAction()
    {
        if($_SESSION["loginStatus"] == 1) {
            $view = new View('candidateHomePage');
            echo $view->render();
        } else {
            $this->redirect('home');
        }
    }

    /**
     * Function to create a Candidate account
     */
    public function createAccountAction()
    {
        //To complete
        //Call super first
    }
}
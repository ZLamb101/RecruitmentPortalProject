<?php

namespace bjz\portal\controller;
use bjz\portal\view\View;
use Symfony\Component\Config\ConfigCache;

session_start();

/**
 * Class EmployerController
 *
 * Class controls all actions relevant to the Employer Model
 *
 * @package bjz\portal\controller
 */
class EmployerController extends UserController
{
    /**
     * Action to load the employerHomePage
     * Checks if the user is logged in as an Employer and if so grants them access to this page
     */
    public function indexAction()
    {
        if($_SESSION["loginStatus"] == Controller::EMPLOYER) {
            $view = new View('employerHomePage');
            echo $view->render();
        } else if($_SESSION["loginStatus"] == Controller::CANDIDATE){
            $this->redirect('candidateHomePage');
        } else {
            $this->redirect('home');
        }
    }

    /**
     * Action to create an Employer account
     */
    public function createAccountAction()
    {
        //To complete
        //Call super
    }
}
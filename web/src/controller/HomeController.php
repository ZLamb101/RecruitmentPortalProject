<?php

namespace bjz\portal\controller;
use bjz\portal\model\Model;
use bjz\portal\view\View;
session_start();


/**
 * Class HomeController
 *
 * Generic controller that handles loading of all pages accessible to a user
 * not yet logged in
 *
 * @package bjz\portal\controller
 */
class HomeController extends Controller
{

    /**
     * Action to load the frontPage
     */
    public function indexAction()
    {
        try {
            new Model();
        } catch (\Exception $e){
            error_log($e->getMessage());
        }
        $view = new View('frontPage');
        echo $view->render();
    }

    /**
     * Action to load the preRegisterPage
     */
    public function preRegisterPageAction()
    {
        $view = new View('preRegisterPage');
        echo $view->render();
    }

    /**
     * Action to load the employerRegisterPage
     */
    public function employerRegisterPageAction()
    {
        $view = new View('employerRegisterPage');
        echo $view->render();
    }

    /**
     * Action to load the candidateRegisterPage
     */
    public function candidateRegisterPageAction()
    {
        $view = new View('candidateRegisterPage');
        echo $view->render();
    }

    /**
     * Action to load the registrationConfirmationPage
     */
    public function registrationConfirmationPageAction()
    {
        $view = new View('registrationConfirmationPage');
        echo $view->render();
    }

    /**
     * Action to load the passwordRecoveryPage
     */
    public function passwordRecoveryPageAction()
    {
        $view = new View('passwordRecoveryPage');
        echo $view->render();
    }

    /**
     * Action to load the passwordRecoveryConfirmationPage
     */
    public function passwordRecoveryConfirmationPageAction()
    {
        $view = new View('passwordRecoveryConfirmationPage');
        echo $view->render();
    }

    /**
     * Action to load the errorPage
     */
    public function errorPageAction()
    {
        $view = new View('errorPage');
        echo $view->render();
    }
}
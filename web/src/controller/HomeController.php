<?php

namespace bjz\portal\controller;
use bjz\portal\model\Model;
use bjz\portal\model\UserModel;
use bjz\portal\model\CandidateModel;
use bjz\portal\model\WorkExperienceModel;
use bjz\portal\model\WorkExperienceCollectionModel;
use bjz\portal\model\QualificationCollectionModel;
use bjz\portal\model\QualificationModel;
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
        if($_SESSION["loginStatus"] == Controller::GUEST) {
            $view = new View('preRegisterPage');
            echo $view->render();
        } else if($_SESSION["loginStatus"] == Controller::CANDIDATE) {
            $this->redirect('candidateHomePage');
        } else if($_SESSION["loginStatus"] == Controller::EMPLOYER) {
            $this->redirect('employerHomePage');
        }
    }

    /**
     * Action to load the employerRegisterPage
     */
    public function employerRegisterPageAction()
    {
        if($_SESSION["loginStatus"] == Controller::GUEST) {
            $view = new View('employerRegisterPage');
            echo $view->render();
        } else if($_SESSION["loginStatus"] == Controller::CANDIDATE) {
            $this->redirect('candidateHomePage');
        } else if($_SESSION["loginStatus"] == Controller::EMPLOYER) {
            $this->redirect('employerHomePage');
        }
    }

    /**
     * Action to load the candidateRegisterPage
     */
    public function candidateRegisterPageAction()
    {
        if($_SESSION["loginStatus"] == Controller::GUEST) {
            $view = new View('candidateRegisterPage');
            echo $view->render();
        } else if($_SESSION["loginStatus"] == Controller::CANDIDATE) {
            $this->redirect('candidateHomePage');
        } else if($_SESSION["loginStatus"] == Controller::EMPLOYER) {
            $this->redirect('employerHomePage');
        }
    }

    /**
     * Action to load the registrationConfirmationPage
     */
    public function registrationConfirmationPageAction()
    {
        if($_SESSION["loginStatus"] == Controller::GUEST) {
            $view = new View('registrationConfirmationPage');
            echo $view->render();
        } else if($_SESSION["loginStatus"] == Controller::CANDIDATE) {
            $this->redirect('candidateHomePage');
        } else if($_SESSION["loginStatus"] == Controller::EMPLOYER) {
            $this->redirect('employerHomePage');
        }
    }

    /**
     * Action to load the passwordRecoveryPage
     */
    public function passwordRecoveryPageAction()
    {
        if($_SESSION["loginStatus"] == Controller::GUEST) {
            $view = new View('passwordRecoveryPage');
            echo $view->render();
        } else if($_SESSION["loginStatus"] == Controller::CANDIDATE) {
            $this->redirect('candidateHomePage');
        } else if($_SESSION["loginStatus"] == Controller::EMPLOYER) {
            $this->redirect('employerHomePage');
        }
    }

    /**
     * Action to load the passwordRecoveryConfirmationPage
     */
    public function passwordRecoveryConfirmationPageAction()
    {
        if($_SESSION["loginStatus"] == Controller::GUEST) {
            $view = new View('passwordRecoveryConfirmationPage');
            echo $view->render();
        } else if($_SESSION["loginStatus"] == Controller::CANDIDATE) {
            $this->redirect('candidateHomePage');
        } else if($_SESSION["loginStatus"] == Controller::EMPLOYER) {
            $this->redirect('employerHomePage');
        }
    }

    /**
     * Action to load the errorPage
     */
    public function errorPageAction()
    {
        if($_SESSION["loginStatus"] == Controller::GUEST) {
            $view = new View('errorPage');
            echo $view->render();
        } else if($_SESSION["loginStatus"] == Controller::CANDIDATE) {
            $this->redirect('candidateHomePage');
        } else if($_SESSION["loginStatus"] == Controller::EMPLOYER) {
            $this->redirect('employerHomePage');
        }
    }
}
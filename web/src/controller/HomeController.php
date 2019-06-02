<?php

namespace bjz\portal\controller;
use bjz\portal\model\Model;
use bjz\portal\model\UserModel;
use bjz\portal\model\CandidateModel;
use bjz\portal\model\CandidateListCollectionModel;
use bjz\portal\model\EmployerModel;
use bjz\portal\model\WorkExperienceModel;
use bjz\portal\model\WorkExperienceCollectionModel;
use bjz\portal\model\QualificationCollectionModel;
use bjz\portal\model\QualificationModel;
use bjz\portal\model\ShortListCollectionModel;
use bjz\portal\model\ShortListModel;
use bjz\portal\model\SkillModel;
use bjz\portal\view\View;
use Couchbase\Exception;

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
            $this->redirect('errorPage');
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
            try{
                $skill = new SkillModel();
                $fields = $skill->getFields();
                $view = new View('candidateRegisterPage');
                echo $view->addData('Fields', $fields)->render();
            } catch (\Exception $e){
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
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
     * Checks if the guid is valid , and if so allows access to a reset page
     */
    public function verifyAction(){
        $uuid = $_GET['id'];
        try {
            $account = new UserModel();
            if ($account->check_uuid($uuid)) {
                $view = new View('resetPasswordPage');
                echo $view->addData('uuid', $uuid)->render();
            }
            throw new \Exception("Invalid uuid");
        } catch (\Exception $e){
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }



    /**
     * Action to load the errorPage
     */
    public function errorPageAction()
    {
        $view = new View('errorPage');
        echo $view->render();
    }

    /**
     * Action to load updatePasswordConfirmationPage
     */
    public function updatePasswordConfirmationIndex(){
        $view = new View('resetPasswordConfirmationPage');
        echo $view->render();
    }
}
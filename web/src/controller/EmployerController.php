<?php

namespace bjz\portal\controller;
use bjz\portal\view\View;
use bjz\portal\model\EmployerModel;

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
     * Passes an employer object through the view so that account specific information can be accessed
     */
    public function indexAction()
    {
        if($_SESSION["loginStatus"] == Controller::EMPLOYER) {
            try{
                $account = new EmployerModel();
                $account->load($_SESSION["UserID"]);
                $view = new View('employerHomePage');
                echo $view->addData('employerInfo', $account)->render();
            } catch (\Exception $e){
                $this->redirect('errorPage');
            }
        } else if($_SESSION["loginStatus"] == Controller::CANDIDATE){
            $this->redirect('candidateHomePage');
        } else {
            $this->redirect('home');
        }
    }

    /**
     * Action to load the employerEditInfoPage
     * Checks if the user is logged in as an Employer and if so grants them access to this page
     * Passes an employer object through the view so that account specific information can be accessed
     */
    public function editInfoPageAction()
    {
        if($_SESSION["loginStatus"] == Controller::EMPLOYER) {
            try{
                $account = new EmployerModel();
                $account->load($_SESSION['UserID']);
                $view = new View('employerEditInfoPage');
                echo $view->addData('employerInfo', $account)->render();
            } catch (\Exception $e){
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
        } else if($_SESSION["loginStatus"] == Controller::CANDIDATE){
            $this->redirect('candidateHomePage');
        } else {
            $this->redirect('home');
        }
    }

    /**
     * Function to update a Employer account
     * Takes the inputs from post request and loads an Employer account
     * Then updates any changed data
     */
    public function updateAccountAction(){
        if($_SESSION["loginStatus"] == Controller::EMPLOYER) {
            parent::updateAccountAction();
            try {
                $account = new EmployerModel();
                $account->load($_SESSION['UserID']);
            }catch(\Exception $e){
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
            $account->setCompanyName($_POST['company-name']);
            $account->setUrl($_POST['url']);
            $account->setContactName($_POST['contact-name']);
            $account->setAddress($_POST['address']);
            $account->setCalendarLink($_POST['calendar-link']);
            try {
                $account->save();
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
            $this->redirect('employerHomePage');
        }
    }

    /**
     * Action to create an Employer account
     * Retrieves employer information from post request and saves to account, redirects to confirmation page.
     */
    public function createAccountAction()
    {
        parent::createAccountAction();
        try {
            $account = new EmployerModel();
            $accountId = $account->findID($_POST['username']);
        } catch (\Exception $e) {
            $this->redirect('errorPage');
        }
        $account->setUserId($accountId);
        $account->setCompanyName($_POST['company-name']);
        $account->setUrl($_POST['url']);
        $account->setContactName($_POST['contact-name']);
        $account->setAddress($_POST['address']);
        try {
            $account->save();
            $view = new View('registrationConfirmationPage');
            echo $view->render();
            $account->sendConfirmationEmail($_POST['email'],$_POST['username']);
        } catch (\Exception $e) {
            $this->redirect('errorPage');
        }
    }
}
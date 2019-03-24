<?php

namespace bjz\portal\controller;
use bjz\portal\view\View;
use bjz\portal\model\EmployerModel;
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
     * Retreives employer information from post request and saves to account, redirects to confirmation page.
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
        } catch (\Exception $e) {
            $this->redirect('errorPage');
        }
        $this->redirect('registrationConfirmationPage');
        //To complete
        //Call super
    }
}
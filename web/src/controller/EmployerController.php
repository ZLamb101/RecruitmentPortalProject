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
        super();

        try {

            $account = new EmployerModel();
            $accountId = $account->UserModel::findID($_POST['username']);
            $account->load($accountId);
        } catch (\Exception $e) {
            $this->redirect('error');
        }
        $account->setCompanyName($_POST['company-name']);
        $account->setUrl($_POST['url']);
        $account->setContactName($_POST['contact-name']);
        $account->setAddress($_POST['address']);
        try {
            $account->save();
        } catch (\Exception $e) {
            $this->redirect('error');
        }
        $view = new View('accountCreated');
        echo $view->addData('account', $account)->render();
        //To complete
        //Call super
    }
}
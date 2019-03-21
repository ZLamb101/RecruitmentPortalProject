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
        if($_SESSION["loginStatus"] == Controller::CANDIDATE) {
            $view = new View('candidateHomePage');
            echo $view->render();
        } else if($_SESSION["loginStatus"] == Controller::EMPLOYER){
            $this->redirect('employerHomePage');
        } else {
            $this->redirect('home');
        }
    }

    /**
     * Function to create a Candidate account
     */
    public function createAccountAction()
    {
         try {
            $account = new CandidateModel();
        } catch (\Exception $e) {
            $this->redirect('error');
        }
        $account->setFName($_POST['first_name']);
        $account->setLName($_POST['last_name']);
        $account->setLocation($_POST['location']);
        $account->setAvailability($_POST['avilability']);
        $account->setSkills($_POST['skills']);
        
        try {
            $account->save();
        } catch (\Exception $e) {
            $this->redirect('error');
        }
        $view = new View('accountCreated');
        echo $view->addData('account', $account)->render();

        //To complete
        //Call super first
    }
}
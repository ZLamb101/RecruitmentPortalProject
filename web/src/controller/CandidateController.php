<?php

namespace bjz\portal\controller;
use bjz\portal\view\View;
use bjz\portal\model\CandidateModel;
use bjz\portal\model\QualificationModel;
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
        error_log("test1");
        parent::createAccountAction();
        try {
            $account = new CandidateModel();
            $accountId = $account->findID($_POST['username']);
            $account->load($accountId);
            
        } catch (\Exception $e) {
            $this->redirect('errorPage');
        }
        error_log("test2");
        $account->setGName($_POST['first_name']);
        error_log("test3");
        $account->setFName($_POST['last_name']);
        $account->setLocation($_POST['location']);
        $account->setAvailability($_POST['availability']);
        $account->setSkills($_POST['skills']);

        $it = 0;
        error_log("start");
        $qualificationCount = $_POST['qualification-count'];
        error_log("$qualificationCount");

        do{
            $qualification = new QualificationModel();

            $temp2 = 'year'.$qualificationCount;
            $temp3 = 'name'.$qualificationCount;
            $qualification->setYear($_POST["temp2"]);
            $qualification->setName($_POST["temp3"]);
            $gname = $_POST['name'.$qualificationCount];
            error_log("$qualificationCount number of loop is $gname");
            $qualificationCount--;
        }while ($qualificationCount >= 0);
        error_log("stop");




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
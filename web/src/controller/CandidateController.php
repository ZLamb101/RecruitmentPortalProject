<?php

namespace bjz\portal\controller;
use bjz\portal\view\View;
use bjz\portal\model\CandidateModel;
use bjz\portal\model\QualificationModel;
use bjz\portal\model\WorkExperienceModel;
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
        error_log("test 10");

        
        parent::createAccountAction();
        try {
            $account = new CandidateModel();
            $accountId = $account->findID($_POST['username']);
            $account->load($accountId);
            
        } catch (\Exception $e) {
            $this->redirect('errorPage');
        }
        
        $account->setGName($_POST['first_name']);
        $account->setFName($_POST['last_name']);
        $account->setLocation($_POST['location']);
        $account->setAvailability($_POST['availability']);
        $account->setSkills($_POST['skills']);

        $it = 0;
        $qualificationCount = $_POST['qualification-count'];
error_log("test 12");
        do{
            $qualification = new QualificationModel();

            $yearInput = 'year'.$qualificationCount;
            $nameInput = 'name'.$qualificationCount;
            $qualification->setYear($_POST["yearInput"]);
            $qualification->setName($_POST["nameInput"]);
            $qualificationCount--;
        }while ($qualificationCount >= 0);

        error_log("test 14");
        $it = 0;
        $workExperienceCount = $_POST['work-experience-count'];

        while($workExperienceCount >= 0){
            $workExperience = new WorkExperienceModel();

            $roleInput = 'role'.$workExperienceCount;
            $durationInput = 'duration'.$workExperienceCount;
            $employerInput = 'employer'.$workExperienceCount;
            $workExperience->setRole($_POST["roleInput"]);
            $workExperience->setDuration($_POST["durationInput"]);
            $workExperience->setEmployer($_POST["employerInput"]);
            $workExperienceCount--;


        }



        error_log("test 10");
        try {
            $account->save();
        } catch (\Exception $e) {
            $this->redirect('error');
        }
        $view = new View('accountCreated');
        echo $view->render();

        //To complete
        //Call super first
    }
}
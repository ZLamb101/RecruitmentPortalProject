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
        parent::createAccountAction();
        try {
            $account = new CandidateModel();
            $accountId = $account->findID($_POST['username']);
            
        } catch (\Exception $e) {
            $this->redirect('errorPage');
        }

        $account->setUserId($accountId);
        $account->setGName($_POST['first-name']);
        $account->setFName($_POST['last-name']);
        $account->setLocation($_POST['location']);
        $account->setAvailability($_POST['availability']);
        $account->setSkills($_POST['skill']);

        try {
            $account->save();
        } catch (\Exception $e) {
            $this->redirect('errorPage');
        }

        //seperate to qual create func

        $qualificationCount = $_POST['qualification-count'];

        do{   //implement case for empty fielsd
            $qualification = new QualificationModel();
            $yearInput = 'year'.$qualificationCount;
            $nameInput = 'name'.$qualificationCount;
            error_log("Saving qual");
            error_log("$accountId");
            $qualification->setOwnerId($accountId);
            $temp = $qualification->getOwnerId();
            error_log("$temp");
            $qualification->setYear($_POST["$yearInput"]);
            $qualification->setName($_POST["$nameInput"]);
            $qualificationCount--;

            try {
                $qualification->save();
            } catch (\Exception $e) {
                $this->redirect('errorPage');
            }
        }while ($qualificationCount >= 0);

       


        //seperate to workexp create func
        error_log("test 14");
        $workExperienceCount = $_POST['work-experience-count'];

        do{     //implement case for empty fields
            $workExperience = new WorkExperienceModel();
            $roleInput = 'role'.$workExperienceCount;
            $durationInput = 'duration'.$workExperienceCount;
            $employerInput = 'employer'.$workExperienceCount;
            $workExperience->setOwnerId($accountId);
            $workExperience->setRole($_POST["$roleInput"]);
            $workExperience->setDuration($_POST["$durationInput"]);
            $workExperience->setEmployer($_POST["$employerInput"]);
            $temp = $_POST["$roleInput"];
            error_log("$temp");
            $workExperienceCount--;

            try {
                $workExperience->save();
            } catch (\Exception $e) {
                $this->redirect('errorPage');
            }
        }  while($workExperienceCount >= 0);

    

       

        error_log("the end");
    
        $this->redirect('registrationConfirmationPage');
        //To complete
        //Call super first
    }
}
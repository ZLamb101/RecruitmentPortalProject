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
     * Takes the inputs from post request and creates a Candidate account
     * creates any Qualifications and work experiences attached to the account.
     *
     */
    public function createAccountAction()
    {
        parent::createAccountAction();
        try {
            $account = new CandidateModel();
            $accountId = $account->findID($_POST['username']);
            error_log("1");
            
        } catch (\Exception $e) {
            error_log("2");
            $this->redirect('errorPage');
        }

        error_log("3");
        $account->setUserId($accountId);
        $account->setGName($_POST['first-name']);
        $account->setFName($_POST['last-name']);
        $account->setLocation($_POST['location']);
        $account->setAvailability($_POST['availability']);
        $account->setSkills($_POST['skill']);
        error_log("4");
        try {
            error_log("5");
            $account->save();
            error_log("6");
        } catch (\Exception $e) {
            $this->redirect('errorPage');
        }

        error_log("7");
        $qualificationCount = $_POST['qualification-count'];
        do{
            error_log("8");
            $qualification = new QualificationModel();
            error_log("9");
            $yearInput = 'year'.$qualificationCount;
            $nameInput = 'name'.$qualificationCount;
            if($_POST["$yearInput"] == NULL || $_POST["$nameInput"] == NULL) break;
            error_log("10");
            $qualification->setOwnerId($accountId);
            $qualification->setYear($_POST["$yearInput"]);
            $qualification->setName($_POST["$nameInput"]);
            $qualificationCount--;
            try {
                error_log('11');
                $qualification->save();
            } catch (\Exception $e) {
                error_log("12");
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
        }while ($qualificationCount >= 0);

        error_log("13");
        $workExperienceCount = $_POST['work-experience-count'];
        do{
            error_log("14");
            $workExperience = new WorkExperienceModel();
            $roleInput = 'role'.$workExperienceCount;
            $durationInput = 'duration'.$workExperienceCount;
            $employerInput = 'employer'.$workExperienceCount;
            error_log("15");
            if($_POST["$roleInput"] == NULL || $_POST["$durationInput"] == NULL || $_POST["$employerInput"] == NULL) break;
            error_log("16");
            $workExperience->setOwnerId($accountId);
            $workExperience->setRole($_POST["$roleInput"]);
            $workExperience->setDuration($_POST["$durationInput"]);
            $workExperience->setEmployer($_POST["$employerInput"]);
            $workExperienceCount--;
            error_log("17");
            try {
                error_log("18");
                $workExperience->save();
            } catch (\Exception $e) {
                error_log("19");
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
        }  while($workExperienceCount >= 0);
        error_log("20");
        $this->redirect('registrationConfirmationPage');
        error_log("21");
    }
}
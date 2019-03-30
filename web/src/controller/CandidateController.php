<?php

namespace bjz\portal\controller;
use bjz\portal\view\View;
use bjz\portal\model\CandidateModel;
use bjz\portal\model\QualificationModel;
use bjz\portal\model\WorkExperienceModel;
use bjz\portal\model\SkillModel;
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
     * Passes a candidate object through the view so that account specific information can be accessed
     */
    public function indexAction()
    {
        if($_SESSION["loginStatus"] == Controller::CANDIDATE) {
            try{
                $account = new CandidateModel();
                $account->load($_SESSION[UserID]);
                $view = new View('candidateHomePage');
                echo $view->addData('candidateInfo', $account)->render();
            } catch (\Exception $e){
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
        } else if($_SESSION["loginStatus"] == Controller::EMPLOYER){
            $this->redirect('employerHomePage');
        } else {
            $this->redirect('home');
        }
    }

    /**
     * Action to load the candidateEditInfoPage
     * Checks if the user is logged in as a candidate and if so grants them access to this page
     * Passes a candidate object through the view so that account specific information can be accessed
     */
    public function editInfoPageAction()
    {
        if($_SESSION["loginStatus"] == Controller::CANDIDATE) {
            try{
                $account = new CandidateModel();
                $account->load($_SESSION[UserID]);
                $view = new View('candidateEditInfoPage');
                echo $view->addData('candidateInfo', $account)->render();
            } catch (\Exception $e){
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
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
            
        } catch (\Exception $e) {
            $this->redirect('errorPage');
        }

        $account->setUserId($accountId);
        $account->setGName($_POST['first-name']);
        $account->setFName($_POST['last-name']);
        $account->setLocation($_POST['location']);
        $account->setSkills($_POST['skill']);
        $availability = 0;
        if(isset($_POST['full-time'])) $availability += 1;
        if(isset($_POST['part-time'])) $availability +=2;
        if(isset($_POST['casual'])) $availability +=4;
        if(isset($_POST['contractor'])) $availability +=8;
        $account->setAvailability($availability);
        try {
            $account->save();
        } catch (\Exception $e) {
            $this->redirect('errorPage');
        }
        $candidateID = $account->getId();
        $this->createQualificationAction($candidateID);
        $this->createWorkExperienceAction($candidateID);




        $this->redirect('registrationConfirmationPage');
    }

    /**
     * Function to create a Qualification
     * Takes the inputs from post request and creates a Qualification
     */
    public function createQualificationAction($candidateID){
        $qualificationCount = $_POST['qualification-count'];
        do{
            $qualification = new QualificationModel();
            $yearInput = 'year'.$qualificationCount;
            $nameInput = 'name'.$qualificationCount;
            if($_POST["$yearInput"] == NULL || $_POST["$nameInput"] == NULL) break;
            $qualification->setOwnerId($candidateID);
            $qualification->setYear($_POST["$yearInput"]);
            $qualification->setName($_POST["$nameInput"]);
            $qualificationCount--;
            try {
                $qualification->save();
            } catch (\Exception $e) {
                $this->redirect('errorPage');
            }
        }while ($qualificationCount >= 0);

    }

    /**
     * Function to create a Work Experience
     * Takes the inputs from post request and creates a Work Experience
     */
    public function createWorkExperienceAction($candidateID){
        $workExperienceCount = $_POST['work-experience-count'];
        do{
            $workExperience = new WorkExperienceModel();
            $roleInput = 'role'.$workExperienceCount;
            $durationInput = 'duration'.$workExperienceCount;
            $employerInput = 'employer'.$workExperienceCount;
            if($_POST["$roleInput"] == NULL || $_POST["$durationInput"] == NULL || $_POST["$employerInput"] == NULL) break;
            $workExperience->setOwnerId($candidateID);
            $workExperience->setRole($_POST["$roleInput"]);
            $workExperience->setDuration($_POST["$durationInput"]);
            $workExperience->setEmployer($_POST["$employerInput"]);
            $workExperienceCount--;
            try {
                $workExperience->save();
            } catch (\Exception $e) {
                $this->redirect('errorPage');
            }
        }  while($workExperienceCount >= 0);

    }

    /**
     * Function to create a Work Experience
     * Takes the inputs from post request and creates a Work Experience
     */
    public function createSkillAction($candidateID){
        $skillCount = $_POST['skill-count'];
        do{
            $skill = new SkillModel();
            $fieldInput = 'field'.$skillCount;
            $subFieldInput = 'sub-field'.$skillCount;
            $contentsInput = 'contents'.$skillCount;
            if($_POST["$fieldInput"] == NULL || $_POST["$subFieldInput"] == NULL || $_POST["$contentsInput"] == NULL) break;
            $skill->setOwnerId($candidateID);
            $skill->setContents($_POST["$contentsInput"]);
            $skill->setField($skill->findField($_POST["$fieldInput"])); 
            $skill->setSubField($skill->findSubField($_POST["$subFieldInput"])); 

            $skillCount--;
            try {
                $skill->save();
            } catch (\Exception $e) {
                $this->redirect('errorPage');
            }
        }while($skillCount >= 0);

    }



}
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
     * Function to update a Candidate account
     * Takes the inputs from post request and loads a Candidate account
     * loads any Qualifications and work experiences attached to the account.
     * Then updates any changed data
     */
    public function updateAccountAction(){
        if($_SESSION["loginStatus"] == Controller::CANDIDATE) {
            parent::updateAccountAction();
            try {
                $account = new CandidateModel();
                $account->load($_SESSION['UserID']);
            }catch(\Exception $e){
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
            $account->setGName($_POST['first-name']);
            $account->setFName($_POST['last-name']);
            $account->setLocation($_POST['location']);
            $availability = 0;
            if(isset($_POST['full-time'])) $availability += 8;
            if(isset($_POST['part-time'])) $availability +=4;
            if(isset($_POST['casual'])) $availability +=2;
            if(isset($_POST['contractor'])) $availability +=1;
            $account->setAvailability($availability);
            try {
                $account->save();
            } catch (\Exception $e) {
                $this->redirect('errorPage');
            }
            $candidateID = $account->getId();
            $this->updateQualificationAction($candidateID);
            $this->updateWorkExperienceAction($candidateID);
            $this->updateSkillAction($candidateID);

            $this->redirect('candidateHomePage');
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
        $availability = 0;
        if(isset($_POST['full-time'])) $availability += 8;
        if(isset($_POST['part-time'])) $availability +=4;
        if(isset($_POST['casual'])) $availability +=2;
        if(isset($_POST['contractor'])) $availability +=1;
        $account->setAvailability($availability);
        try {
            $account->save();
        } catch (\Exception $e) {
            $this->redirect('errorPage');
        }
        $candidateID = $account->getId();
        $this->createQualificationAction($candidateID);
        $this->createWorkExperienceAction($candidateID);
        $this->createSkillAction($candidateID);

        try {
            $account->sendConfirmationEmail($_POST['email'],$_POST['username']);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
        $this->redirect('registrationConfirmationPage');
    }


   /**
     * Function to load and update a Qualification
     * Takes the inputs from post request and updates a Qualification
     */
    public function updateQualificationAction($candidateID){
        $qualificationCount = $_POST['qualification-count'];
        do{
            $qualification = new QualificationModel();

            $idInput = 'id'.$qualificationCount;
            $qualification->load($_POST["$idInput"]);
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
     * Function to load and update a Work Experience
     * Takes the inputs from post request and updates a Work Experience
     */
    public function updateWorkExperienceAction($CandidateID){
        $workExperienceCount = $_POST['work-experience-count'];
        do{
            $workExperience = new WorkExperienceModel();
            $idInput = 'id'.$workExperienceCount;
            $workExperience->load($_POST["$idInput"]);
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
     * Function to load and update a SKill
     * Takes the inputs from post request and updates a Skill
     */
    public function updateSkillAction($candidateID){
        $skillCount = $_POST['skill-count'];
        do{
            $skill = new SkillModel();
            $skillId = 'id'.$skillCount;
            $skill->load($_POST["$skillId"]);

            $fieldInput = 'field'.$skillCount;
            $subFieldInput = 'sub-field'.$skillCount;
            $contentsInput = 'contents'.$skillCount;
            if($_POST["$fieldInput"] == NULL || $_POST["$subFieldInput"] == NULL || $_POST["$contentsInput"] == NULL) break;
            $skill->setOwnerId($candidateID);
            $skill->setContents($_POST["$contentsInput"]);
            $skill->setField($_POST["$fieldInput"]);
            $skill->setSubField($_POST["$subFieldInput"]);

            $skillCount--;
            try {
                $skill->save();
            } catch (\Exception $e) {
                $this->redirect('errorPage');
            }
        }while($skillCount >= 0);


    }


    /**
     * Function to create a Skill
     * Takes the inputs from post request and creates a Skill
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
            $skill->setField($_POST["$fieldInput"]);
            $skill->setSubField($_POST["$subFieldInput"]);

            $skillCount--;
            try {
                $skill->save();
            } catch (\Exception $e) {
                $this->redirect('errorPage');
            }
        }while($skillCount >= 0);

    }


    /**
     * Function to send a calendar invite

     */
    public function sendInviteAction(){
        try {
            $account = new CandidateModel();
            $accountId = $account->findID($_POST['username']);
            $account->load($accountId);
            $email = $account->getEmail();
            $account->sendInviteEmail($email);
        } catch (\Exception $e) {
            $this->redirect('errorPage');
        }

    }



    public function deleteWorkExperienceAction(){
        $id = $_GET["q"];
        $model = new WorkExperienceModel();
        $model->delete($id);
    }

    public function deleteSkillAction(){
        $id = $_GET["q"];
        $model = new SkillModel();
        $model->delete($id);
    }
>>>>>>> d46c80dc19223a82037931a8c2f3e43e7495cde1

    public function deleteQualificationAction(){
        $id = $_GET["q"];
        $model = new QualificationModel();
        $model->delete($id);
    }

}
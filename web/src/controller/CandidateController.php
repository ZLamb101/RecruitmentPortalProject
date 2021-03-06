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
        if ($_SESSION["loginStatus"] == Controller::CANDIDATE) {
            try {
                $account = new CandidateModel();
                $account->load($_SESSION['UserID']);
                $view = new View('candidateHomePage');
                echo $view->addData('candidateInfo', $account)->render();
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
        } elseif ($_SESSION["loginStatus"] == Controller::EMPLOYER) {
            $this->redirect('employerHomePage');
        } else {
            $this->redirect('home');
        }
    }

    /**
     * Action to load the candidateDisplayPage
     * Passes a candidate object through the view so that account specific information can be accessed
     */
    public function candidateViewAction()
    {
        try {
            $account = new CandidateModel();
            $account->load($_SESSION['candidateToView']);
            $view = new View('candidateDisplayPage');
            echo $view->addData('candidateInfo', $account)->render();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }

    /**
     * Action to load the candidateEditInfoPage
     * Checks if the user is logged in as a candidate and if so grants them access to this page
     * Passes a candidate object through the view so that account specific information can be accessed
     */
    public function editInfoPageAction()
    {
        if ($_SESSION["loginStatus"] == Controller::CANDIDATE) {
            try {
                $account = new CandidateModel();
                $account->load($_SESSION['UserID']);
                $view = new View('candidateEditInfoPage');
                echo $view->addData('candidateInfo', $account)->render();
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
        } elseif ($_SESSION["loginStatus"] == Controller::EMPLOYER) {
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
    public function updateAccountAction()
    {
        if ($_SESSION["loginStatus"] == Controller::CANDIDATE) {
            parent::updateAccountAction();
            try {
                $account = new CandidateModel();
                $account->load($_SESSION['UserID']);
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
            $account->setGName($_POST['first-name']);
            $account->setFName($_POST['last-name']);
            $account->setLocation($_POST['location']);
            $availability = 0;
            if (isset($_POST['full-time'])) {
                $availability += 8;
            }
            if (isset($_POST['part-time'])) {
                $availability += 4;
            }
            if (isset($_POST['casual'])) {
                $availability += 2;
            }
            if (isset($_POST['contractor'])) {
                $availability += 1;
            }
            $account->setAvailability($availability);
            try {
                $account->save();
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
            $candidateID = $account->getId();
            $pref_qual = $this->updateQualificationAction($candidateID);
            $pref_work = $this->updateWorkExperienceAction($candidateID);
            $pref_skill = $this->updateSkillAction($candidateID);
            try {
                $account->savePreferences($pref_qual, $pref_work, $pref_skill);
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
            $this->redirect('candidateHomePage');
        }
    }


    /**
     * Function to create a Candidate account
     * Takes the inputs from post request and creates a Candidate account
     * creates any Qualifications and work experiences attached to the account.
     */
    public function createAccountAction()
    {
        parent::createAccountAction();
        try {
            $account = new CandidateModel();
            $accountId = $account->findID($_POST['username']);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }

        $account->setUserId($accountId);
        $account->setGName($_POST['first-name']);
        $account->setFName($_POST['last-name']);
        $account->setLocation($_POST['location']);
        $account->setAvailability(8);

        try {
            $account->save();
            $view = new View('registrationConfirmationPage');
            echo $view->render();
            $account->sendConfirmationEmail($_POST['email'], $_POST['username']);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }


    /**
     * Function to load and update a Qualification
     * Takes the inputs from post request and updates a Qualification
     *
     * @param  int $candidateID, the id of the candidate whose qualifications they belong to
     * @return int $pref_id, the id of the preferred qualification
     */
    public function updateQualificationAction($candidateID)
    {
        $qualificationCount = $_POST['qualification-count'];
        do {
            try {
                $qualification = new QualificationModel();
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
            $idInput = "qid" . $qualificationCount;
            if ($_POST["$idInput"]) {
                try {
                    $qualification->load($_POST["$idInput"]);
                } catch (\Exception $e) {
                    error_log($e->getMessage());
                    $this->redirect('errorPage');
                }
            }
            $yearInput = 'year' . $qualificationCount;
            $levelInput = 'level' . $qualificationCount;
            $typeInput = 'type' . $qualificationCount;
            $majorInput = 'major' . $qualificationCount;
            if ($_POST["$yearInput"] == null || $_POST["$levelInput"] == null || $_POST["$typeInput"] == null) {
                $qualificationCount--;
                continue;
            }

            $qualification->setOwnerId($candidateID);
            $qualification->setYear($_POST["$yearInput"]);
            $qualification->setLevelId($_POST["$levelInput"]);
            $qualification->setTypeId($_POST["$typeInput"]);
            $qualification->setMajor($_POST["$majorInput"]);

            $isPreferred = false;
            if ($qualificationCount == $_POST["qualification-preference"]) {
                $isPreferred = true;
            }
            $qualificationCount--;
            try {
                $qualification->save();
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
            if ($isPreferred) {
                $pref_id = $qualification->getId();
            }
        } while ($qualificationCount >= 0);
        return $pref_id;
    }



    /**
     * Function to load and update a Work Experience
     * Takes the inputs from post request and updates a Work Experience
     *
     * @param  int $candidateID, the id of the candidate whose work experience they belong to
     * @return int $pref_id, the id of the preferred work experience
     */
    public function updateWorkExperienceAction($candidateID)
    {
        $workExperienceCount = $_POST['work-experience-count'];
        do {
            try {
                $workExperience = new WorkExperienceModel();
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
            $idInput = 'weid' . $workExperienceCount;
            if ($_POST["$idInput"]) {
                try {
                    $workExperience->load($_POST["$idInput"]);
                } catch (\Exception $e) {
                    error_log($e->getMessage());
                    $this->redirect('errorPage');
                }
            }
            $roleInput = 'role' . $workExperienceCount;
            $durationInput = 'duration' . $workExperienceCount;
            $employerInput = 'employer' . $workExperienceCount;
            if ($_POST["$roleInput"] == null || $_POST["$durationInput"] == null || $_POST["$employerInput"] == null) {
                $workExperienceCount--;
                continue;
            }
            $workExperience->setOwnerId($candidateID);
            $workExperience->setRole($_POST["$roleInput"]);
            $workExperience->setDuration($_POST["$durationInput"]);
            $workExperience->setEmployer($_POST["$employerInput"]);
            $isPreferred = false;
            if ($workExperienceCount == $_POST["work-experience-preference"]) {
                $isPreferred = true;
            }
            $workExperienceCount--;
            try {
                $workExperience->save();
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
            if ($isPreferred) {
                $pref_id = $workExperience->getId();
            }
        } while ($workExperienceCount >= 0);
        return $pref_id;
    }



    /**
     * Function to load and update a Skill
     * Takes the inputs from post request and updates a Skill
     *
     * @param  int $candidateID, the id of the candidate whose skill they belong to
     * @return int $pref_id, the id of the preferred skill
     */
    public function updateSkillAction($candidateID)
    {
        $skillCount = $_POST['skill-count'];

        do {
            try {
                $skill = new SkillModel();
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
            $idInput = 'sid' . $skillCount;
            if ($_POST["$idInput"]) {
                try {
                    $skill->load($_POST["$idInput"]);
                } catch (\Exception $e) {
                    error_log($e->getMessage());
                    $this->redirect('errorPage');
                }
            }
            $fieldInput = 'field' . $skillCount;
            $subFieldInput = 'sub-field' . $skillCount;
            $contentsInput = 'contents' . $skillCount;
            if ($_POST["$fieldInput"] == null || $_POST["$subFieldInput"] == null) {
                $skillCount--;
                continue;
            }
            $skill->setOwnerId($candidateID);
            $skill->setContents($_POST["$contentsInput"]);
            $skill->setField($_POST["$fieldInput"]);
            $skill->setSubField($_POST["$subFieldInput"]);

            $isPreferred = false;

            if ($skillCount == $_POST["skill-preference"]) {
                $isPreferred = true;
            }

            $skillCount--;
            try {
                $skill->save();
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
            if ($isPreferred) {
                $pref_id = $skill->getId();
            }
        } while ($skillCount >= 0);
        return $pref_id;
    }

    /**
     * Function to get all the Types within the database and echo's
     */
    public function updateTypesAction()
    {
        try {
            $qual = new QualificationModel();
            $toConvert = $qual->getTypes();
            echo"<option value=\"all\">All Sub-Categories</option>";
            foreach ($toConvert as $item) {
                echo "<option value=\"" . $item['id'] . "\">" . $item['type'] . "</option>";
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }

    /**
     * Function to get all the Levels within the database and echo's
     */
    public function updateLevelsAction()
    {
        try {
            $qual = new QualificationModel();
            $toConvert = $qual->getLevels();
            echo"<option value=\"all\">All Categories</option>";
            foreach ($toConvert as $item) {
                echo "<option value=\"" . $item['id'] . "\">" . $item['level'] . "</option>";
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }


    /**
     * Function to delete a candidates work experience from the database
     */
    public function deleteWorkExperienceAction()
    {
        $id = $_GET["id"];
        try {
            $model = new WorkExperienceModel();
            $model->delete($id);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }


    /**
     * Function to delete a candidates skill from the database
     */
    public function deleteSkillAction()
    {
        $id = $_GET["id"];
        try {
            $model = new SkillModel();
            $model->delete($id);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }


    /**
     * Function to delete a candidates qualification from the database
     */
    public function deleteQualificationAction()
    {
        $id = $_GET["id"];
        try {
            $model = new QualificationModel();
            $model->delete($id);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }
}

<?php

namespace bjz\portal\controller;


use bjz\portal\model\SkillModel;
use bjz\portal\model\QualificationModel;
use bjz\portal\model\ShortListModel;
use bjz\portal\model\EmployerModel;
use bjz\portal\view\View;
use bjz\portal\model\SearchCandidateCollectionModel;
session_start();

/**
 * Class SearchController
 *
 * Class controls all actions relevant to the searching
 *
 * @package bjz\portal\controller
 */
class SearchController extends Controller
{
    /**
     * Action to load the searchPage
     * Checks if the user is logged in as an Employer and if so grants them access to this page
     */
    public function indexAction()
    {
        if($_SESSION["loginStatus"] == Controller::EMPLOYER) {
            try{
                $skill = new SkillModel();
                $fields = $skill->getFields();
                $qual = new QualificationModel();
                $types = $qual->getTypes();
                $account = new EmployerModel();
                $account->load($_SESSION["UserID"]);
                $view = new View('searchPage');
                $view->addData('employerInfo', $account);
                $view->addData('Quals', $types);
                echo $view->addData('Fields', $fields)->render();
            } catch (\Exception $e){
                error_log($e->getMessage());
                $this->redirect('errorPage');
            }
        } else if($_SESSION["loginStatus"] == Controller::CANDIDATE){
            $this->redirect('candidateHomePage');
        } else {
            $this->redirect('home');
        }
    }

    /**
     * Function to search for candidates based on a search query
     * This function is called asynchronously to display search results.
     */
    public function liveSearchAction()
    {
        $query = $_GET["query"];
        $field_id = $_GET["field"];
        $sub_field_id = $_GET["sub_field"];
        $qual = $_GET["qual"];
        $avail = $_GET["avail"];
        try {
            $livesearch = new SearchCandidateCollectionModel($query, $field_id, $sub_field_id, $qual, $avail);
            $candidates = $livesearch->getCandidates();
            if ($livesearch->getN() == 0) {
                echo "No matches found";
            } else {
                $result = $this->formatSearch($candidates);
                echo $result;
                return;
            }
        } catch (\Exception $e){
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
        echo "";
        return;
    }

    /**
     * Formats the search results to be displayed in HTML
     * @param $candidates, the list of candidates who were found in the search query
     * @return string, the formatted HTML Table element displaying the search results.
     */
    public function formatSearch($candidates){
        $candidateIDs = NULL;
        $response = "<table class=\"table-condensed table-striped\"><tr><th>First Name</th><th>Last Name</th><th>Qualification</th><th>Previous Experience</th><th>Skills</th><th>Add to Shortlist?</th></tr>";
        foreach($candidates as $candidate){

            if($candidateIDs == NULL){
                $candidateIDs = $candidate->getUserID();
            } else {
                $candidateIDs = $candidateIDs . ',' . $candidate->getUserID();
            }

            $response .= "<tr><td><a href=\"View-Candidate\" onclick=\"return displayCandidate('View-Candidate',".$candidate->getUserId().")\">" . $candidate->getGName() . "</a></td><td>" . $candidate->getFName() . "</td><td>" .
                            $candidate->displayPreferredQualification() . "</td><td>". $candidate->displayPreferredWorkExperience()
                            ."</td><td>". $candidate->displayPreferredSkill() ."</td><td class=\"center\"><input type='button' id='add-to-shortlist".$candidate->getUserId()."' value='+' onclick='addToShortlist(".$candidate->getUserId().")'></td></tr>";
        }
        $response = $response . '</table>';
        $response = $response . "<input type=\"hidden\" id= \"cand-ids\" value=\"$candidateIDs\">";
        return $response;
    }


    /**
     * Echo's out the entire list of skill categories
     *
     */
    public function updateFieldsAction(){
        try {
            $skill = new SkillModel();
            $toConvert = $skill->getFields();
            echo"<option value=\"all\">All Categories</option>";
            foreach ($toConvert as $item){
                echo "<option value=\"".$item['id']."\">".$item['field']."</option>";
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }

    /**
     * Echo's out the entire list of skill sub-categories
     * corresponding to specified ID received from get request
     */
    public function updateSubFieldsAction(){
        $id = $_GET["q"];
        try {
            $skill = new SkillModel();
            $toConvert = $skill->getSubFields($id);
            echo"<option value=\"all\">All Sub-Categories</option>";
            foreach ($toConvert as $item){
                echo "<option value=\"".$item['id']."\">".$item['sub_field']."</option>";
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }

    /**
     * Gets candidate Id from get request, saves it in session.
     */
    public function selectCandidateToViewAction(){
        $id = $_GET["id"];
        $_SESSION['candidateToView'] = $id;
    }
}
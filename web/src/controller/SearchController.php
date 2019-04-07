<?php

namespace bjz\portal\controller;


use bjz\portal\model\SkillModel;
use bjz\portal\model\QualificationModel;
use bjz\portal\model\ShortListModel;
use bjz\portal\view\View;
use bjz\portal\model\SearchCandidateCollectionModel;
session_start();

/**
 * Class SearchController
 *
 * Class controls all actions relevant to the candidateCollection Model
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
                $shortlist = new ShortListModel();
                $shortlist->load($_SESSION['UserID']);
                $view = new View('searchPage');
                $view->addData('ShortList', $shortlist);
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
        $response = "<table><tr><th>First Name</th><th>Last Name</th><th>Qualification</th><th>Previous Experience</th><th>Skills</th></tr>";
        foreach($candidates as $candidate){
            $response .= "<tr><td>" . $candidate->getGName() . "</td><td>" . $candidate->getFName() . "</td><td>" .
                            $candidate->displayPreferredQualification() . "</td><td>". $candidate->displayPreferredWorkExperience()
                            ."</td><td>". $candidate->displayPreferredSkill() ."</td></tr>";
        }
        $response = $response . '</table>';
        return $response;
    }


    public function updateFieldsAction(){
        try {
            $skill = new SkillModel();
            $toConvert = $skill->getFields();
            echo"<option value=\"all\">all categories</option>";
            foreach ($toConvert as $item){
                echo "<option value=\"".$item['id']."\">".$item['field']."</option>";
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }

    public function updateSubFieldsAction(){
        $id = $_GET["q"];
        try {
            $skill = new SkillModel();
            $toConvert = $skill->getSubFields($id);
            echo"<option value=\"all\">all subcategories</option>";
            foreach ($toConvert as $item){
                echo "<option value=\"".$item['id']."\">".$item['sub_field']."</option>";
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }

   
}
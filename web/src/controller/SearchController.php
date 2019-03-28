<?php

namespace bjz\portal\controller;


use bjz\portal\model\SkillModel;
use bjz\portal\view\View;
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
                $view = new View('searchPage');
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
     * Function to search through all candidates
     */
    public function searchAction()
    {
        //To complete
    }

     public function updateFieldsAction(){
        error_log("test1");
        try {

            $skill = new SkillModel();
            $toConvert = $skill->getFields();
            foreach ($toConvert as $item){
                error_log("test1")
                echo "<option value=\"".$item['id']."\">".$item['field']."</option>";
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }

    public function updateSubFieldsAction(){
        error_log("HERE");
        $id = $_GET["q"];
        try {
            $skill = new SkillModel();
            $toConvert = $skill->getSubFields($id);
            foreach ($toConvert as $item){
                echo "<option value=\"".$item['id']."\">".$item['sub_field']."</option>";
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }

   
}
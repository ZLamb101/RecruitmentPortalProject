<?php

namespace bjz\portal\controller;
use bjz\portal\model\ShortListModel;
use bjz\portal\model\CandidateModel;


/**
 * Class ShortListController
 *
 *
 *
 * @package bjz\portal\controller
 */
class ShortListController extends Controller
{

    /**
     * Gets the id of the short list to rename and the new name for it from the get array
     * Attempts to rename said shortlist using the given parameters and handles errors appropriately
     */
    public function renameShortListAction()
    {
        try {
            $id = $_GET["q"];
            $name = $_GET["name"];
            $list = new ShortListModel();
            $list->renameShortList($id, $name);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect("errorPage");
        }
    }

    /**
     * Gets the id of the short list to rename and the ID of the candidate to delete from the GET array
     * Attempts to delete said candidate from the specified shortList
     */
    public function deleteFromShortListAction()
    {
        try {
            $listID = $_GET["listID"];
            $candidateID = $_GET["candidateID"];
            $list = new ShortListModel();
            if ($list->deleteFromShortList($listID, $candidateID)) {
                echo "true";
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect("errorPage");
        }
    }

    /**
     * echo's the candidates in a shortlist
     */
    public function displayShortListAction()
    {
        try {

            $listID = $_GET["q"];
            error_log("list id = ".$listID);
            if($listID != "all") {
                $list = new ShortListModel();
                $list->load($listID);
                $candidates = $list->getCandidates();
                foreach ($candidates as $candidate) {
                    error_log($candidate->getGName());
                    echo "<p>" . $candidate->getGName() . " " . $candidate->getFName() . "</p>";
                }
            }

        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect("errorPage");
        }
    }
}
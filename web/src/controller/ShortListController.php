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

    /**
     *Function to create a new shortList
     */
    public function newShortListAction()
    {
        try {
            $name = $_GET["q"];
            $id = $_GET["id"];
            $list = new ShortListModel();
            $list->newShortList($name, $id);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect("errorPage");
        }
    }

    /**
     * Function to add a candidate to a shortlist.
     */
    public function addToShortListAction()
    {
        $candId = $_GET['candId'];
        $shortId = $_GET['shortId'];
        $short_list = new ShortListModel();
        $short_list->load($shortId);
        $short_list->addCandidate($candId);
        $short_list->save();
    }

    /**
     * Deletes a short list from the database
     */
    public function deleteShortListAction()
    {
        $listId = $_GET['listId'];
        $short_list = new ShortListModel();
        $short_list->load($listId);
        $short_list->delete();
    }

    /**
     *Function to send invites to all candidates on the specified shortList
     */
    public function sendInviteAllAction(){
        
    }
}
<?php

namespace bjz\portal\controller;
use bjz\portal\model\ShortListModel;
use bjz\portal\model\CandidateModel;
use bjz\portal\model\EmployerModel;

session_start();
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
            if($listID != "all") {
                $list = new ShortListModel();
                $list->load($listID);
                $candidates = $list->getCandidates();
                foreach ($candidates as $candidate) {
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

            $employerInfo = new EmployerModel();
            error_log($_SESSION['UserID']);
            $employerInfo->load($_SESSION["UserID"]);


            $shortLists = $employerInfo->getShortLists();
            error_log($employerInfo->getCompanyName());
            error_log($employerInfo->getCompanyName());
            $i = 0;
            $candcount = 0;
            foreach ($shortLists as $list){
                error_log($i); echo " <div id =\"shortlist".$i."\" class=\"partition\">";
                $listID = $list->getID();
                echo "<input type=\"button\" id=\"delete".$i."\" value = \"Delete\" onclick = \"deleteShortList(".$i.", ".$listID.")\">";
                echo "<input type=\"button\" id=\"re-name".$i."\" value = \"Re-name\" onclick=\"renameList(".$i.", ".$listID.")\">";
                echo "<p id = \"shortList".$i."\">".$list->getName()."</p>";
                $candidates = $list->getCandidates();
                foreach ($candidates as $candidate){
                    if($candidate->getGName() != NULL) {
                        $candID = "cand" . $candcount;
                        $candidateID = $candidate->getID();
                        echo "<p id = \"" . $candID . "\">Name: " . $candidate->getGName() . " " . $candidate->getFName() ."<input type=\"button\" id=\"deleteCandidate" . $candcount . "\" value = \"-\" onclick=\"deleteFromShortList(" . $listID . ", " . $candidateID . ", " . $candcount . "," . $i . ")\"> </p> ";

                        $candcount++;
                    }
                }
                $i++;
                echo " </div>";
            }
            echo "<input type=\"button\" id=\"newList\" value = \"Add\" onclick=\"newShortList(".$employerInfo->getId().")\">";
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
        $shortListId = $_GET["q"];
        $shortlist = new ShortListModel();
        $shortlist->load($shortListId);

        $candidates = $shortlist->getCandidates();
        foreach ($candidates as $candidate) {
            $shortlist->sendInviteEmail($candidate->getEmail());
        }

    }
}
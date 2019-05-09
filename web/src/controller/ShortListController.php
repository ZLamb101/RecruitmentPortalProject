<?php

namespace bjz\portal\controller;
use bjz\portal\model\ShortListModel;
use bjz\portal\model\CandidateModel;
use bjz\portal\model\EmployerModel;
use bjz\portal\view\View;

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
     * Gets the id of the short list to change the description of and the new description for it from the GET array
     * Attempts to change the description of said shortlist using the given parameters and handles errors appropriately
     */
    public function changeDescriptionAction()
    {
        try {
            $id = $_GET["q"];
            $description = $_GET["description"];
            $list = new ShortListModel();
            $list->changeDescription($id, $description);
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
            $candCount = $_GET['candCount'];
            $i = $_GET['divID'];

            $list = new ShortListModel();
            $list->load($listID);
            $count = 0;
            error_log("candidate ID is:".$candidateID);
            $list->deleteFromShortList($listID, $candidateID);
            //$list->save();
            $list->load($listID);
            $candidates = $list->getCandidates();
            echo "<h4 class=\"font-weight-bold\"> Candidates: </h4>";
            echo "<div class=\"row\">";
            foreach ($candidates as $candidate) {
                if($count == 4){
                    echo "</div><div class=\"row\">";
                    $count = 0;
                }
                $candID = "cand" . $candCount;
                if($candidate->getGName() != NULL) {
                    echo "<p id = \"" . $candID . "\" class=\"col-sm-3\"><a href=\"View-Candidate\" onclick=\"return displayCandidate('View-Candidate',".$candidate->getUserId().")\">" . $candidate->getGName() . "</a> " . $candidate->getFName() . "<input class='btn btn-sm pull-right' type=\"button\" id=\"deleteCandidate" . $candCount . "\" value = \"-\" onclick=\"deleteFromShortList(" . $list->getId() . ", " . $candidate->getUserId() . ", " . $candCount . ", ".$i.")\"></p>";
                    $candCount++;
                }

                $count++;
            }
            echo "</div>";
            echo "</div>";

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
                if($list->isHasInvited()){
                    echo "<p>Shortlist has been invited previously</p>";
                } else {
                    foreach ($candidates as $candidate) {
                        echo "<p>" . $candidate->getGName() . " " . $candidate->getFName() . "</p>";
                    }
                }
            }else{
                echo "Do not display";
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
            $description = $_GET["description"];
            $list = new ShortListModel();
            $list->newShortList($name, $id, $description);

            $employerInfo = new EmployerModel();
            $employerInfo->load($_SESSION["UserID"]);


            $shortLists = $employerInfo->getShortLists();
            $i = 0;
            $candCount = 0;
            foreach ($shortLists as $list){


                echo "<div class=\"partition zebra shortlist-format\" id=\"shortlist".$i."\">";
                echo "<div class=\"row\">";
                echo "<h3 id = \"shortList".$i."\" class=\"col-sm-3 font-weight-bold\"><u>".$list->getName()."</u></h3>";
                echo "<input class=\"col-sm-2 btn button-grouping btn-danger\" type=\"button\" id=\"delete".$i."\" value = \"Delete\" onclick = \"deleteShortList(".$i.", ".$list->getId().")\">";
                echo "<input class=\"col-sm-2 btn button-grouping btn-warning\" type=\"button\" id=\"re-name".$i."\" value = \"Re-name\" onclick=\"renameList(".$i.", ".$list->getId().")\">";
                echo "<input class=\"col-sm-2 btn button-grouping btn-info\" type=\"button\" id=\"change-description".$i."\" value = \"Change Description\" onclick=\"changeDescription(".$i.", ".$list->getId().")\">";
                if($list->isHasInvited()) {
                    echo "<input type=\"button\" value=\"Invites sent\" name=\"sendInvites\" disabled   >";
                }else{
                    echo "<p><a  class=\"btn btn-success button-grouping col-sm-2\" id=\"send-invite-btn\" href=\"writeEmail.php?list_id=".$list->getId()."\">Send Invite Email</a></p>";
                }
                echo "</div>";
                echo "<input type=\"hidden\" id=\"shortlist".$i."\" value=\"".$list->getId()."\" >";
                echo "<input type=\"hidden\" id=\"num".$i."\" value=\"".$candCount."\" >";
                echo "<h4>Description</h4>";
                echo "<p size = \"512\" id = \"shortListDescription".$i."\">".$list->getDescription()."</p>";
                $candidates = $list->getCandidates();

                $count = 0;
                echo "<div class=\"partition small-box-format\" id=\"candidates".$i."\">";
                echo "<h4 class=\"font-weight-bold\"> Candidates: </h4>";
                echo "<div class=\"row\">";
                foreach ($candidates as $candidate) {
                    if($count == 4){
                        echo "</div><div class=\"row\">";
                        $count = 0;
                    }
                    $candID = "cand" . $candCount;
                    if($candidate->getGName() != NULL) {

                        echo "<p id = \"" . $candID . "\" class=\"col-sm-3\"><a href=\"View-Candidate\" onclick=\"return displayCandidate('View-Candidate',".$candidate->getUserId().")\">" . $candidate->getGName() . "</a> " . $candidate->getFName() . "<input class='btn btn-sm pull-right' type=\"button\" id=\"deleteCandidate" . $candCount . "\" value = \"-\" onclick=\"deleteFromShortList(" . $list->getId() . ", " . $candidate->getUserId() . ", " . $candCount . ", ".$i.")\"></p>";

                        $candCount++;
                    }
                    $count++;
                }
                if($candCount == 0) {
                    echo "<div class=\"center\">";
                    echo "<p>Looks like you have no candidates, Click <a href=\"Search\">Here</a> to start searching!</p>";
                    echo "</div>";
                }
                echo "</div>";
                echo "</div>";
                echo "</div>";
                $i++;
            }

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
     * Function to add all candidates to a shortlist.
     */
    public function addAllToShortListAction()
    {
        $candIDs = $_GET['candidates'];
        $shortId = $_GET['shortId'];
        $ids = explode(",",$candIDs);
        $short_list = new ShortListModel();
        $short_list->load($shortId);
        foreach ($ids as $id) {
            $short_list->addCandidate($id);
        }
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

        $content = $_GET['content'];

        $employerId = $shortlist->getOwnerId();
        $employer = new EmployerModel();
        $employerLoadId = $employer->findLoadId($employerId);
        $employer->load($employerLoadId);

        $candidates = $shortlist->getCandidates();
        foreach ($candidates as $candidate) {
            $shortlist->sendInviteEmail($candidate, $content, $employer);
        }
        $shortlist->setHasInvited(1);
        $shortlist->save();

    }

    /**
     * Function to load the page to write an email to a short list of candidates
     */
    public function writeEmailAction(){
        $shortlistId = $_GET["list_id"];
        $shortlist = new ShortListModel();
        $shortlist->load($shortlistId);

        $employerId = $shortlist->getOwnerId();
        $employer = new EmployerModel();
        $employerLoadId = $employer->findLoadId($employerId);
        $employer->load($employerLoadId);

        $view = new View('writeEmail');
        $view->addData('employer', $employer);
        echo $view->addData('shortlist', $shortlist)->render();
    }
}
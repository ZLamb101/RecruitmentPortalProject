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
 * Class contains all actions relevant to the shortListModel
 *
 * @package bjz\portal\controller
 */
class ShortListController extends Controller
{

    /**
     * Function to rename a shortlist
     *
     * Gets the id of the short list to rename and the new name for it from the GET array
     * Attempts to rename said shortlist using the given parameters and handles errors appropriately
     */
    public function renameShortListAction()
    {
        try {
            $id = $_GET["id"];
            $name = $_GET["name"];
            $list = new ShortListModel();
            $list->renameShortList($id, $name);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect("errorPage");
        }
    }

    /**
     * Function to change the description of a shortlist
     *
     * Gets the id of the short list to change the description of and the new description for it from the GET array
     * Attempts to change the description of said shortlist using the given parameters and handles errors appropriately
     */
    public function changeDescriptionAction()
    {
        try {
            $id = $_POST["id"];
            $description = $_POST["description"];
            $list = new ShortListModel();
            $list->changeDescription($id, $description);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect("errorPage");
        }
    }

    /**
     * Function to delete a candidate from a shortlist
     *
     * Gets the id of the short list and the ID of the candidate to delete from said shortlist from the GET array
     * Attempts to delete said candidate from the specified shortList and handles exceptions appropriately
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
            $list->deleteFromShortList($listID, $candidateID);
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
     * Function to display a shortlist
     *
     * Gets the ID of the shortlist to display from the GET array
     * echo's the candidates in the associated shortlist and handles errors appropriately
     */
    public function displayShortListAction()
    {
        try {
            $listID = $_GET["listID"];
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
     * Function to create a new shortList
     *
     * Get the name of the shortlist, the description of the shortlist and the ID of the employer
     * the shortlist belongs to from the GET array
     *
     * Re-displays all shortlists upon creation.
     */
    public function newShortListAction()
    {
        try {
            $name = $_GET["name"];
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
                echo "<textarea class = 'form-control' disabled rows=\"3\" cols=\"50\"   size = \"512\" id = \"shortListDescription".$i."\">".$list->getDescription()."</textarea>";
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
                        $count++;
                        $candCount++;
                    }

                }

                if($count == 0) {
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
     *
     * Gets both the candidate and shortlist ID from the GET array
     */
    public function addToShortListAction()
    {
        $candId = $_GET['candId'];
        $shortId = $_GET['shortId'];
        try {
            $short_list = new ShortListModel();
            $short_list->load($shortId);
            $short_list->addCandidate($candId);
            $short_list->save();
        } catch (\Exception $e){
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }

    /**
     * Function to add all candidates to a shortlist.
     *
     * Gets a list of all candidate ids and the shortlist id being added to from the GET array
     */
    public function addAllToShortListAction()
    {
        $candIDs = $_GET['candidates'];
        $shortId = $_GET['shortId'];
        try {
            $ids = explode(",", $candIDs);
            $short_list = new ShortListModel();
            $short_list->load($shortId);
            foreach ($ids as $id) {
                $short_list->addCandidate($id);
            }
            $short_list->save();
        } catch (\Exception $e){
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }

    /**
     * Deletes a short list from the database
     *
     * Gets the ID of the shortlist to delete from the GET array
     */
    public function deleteShortListAction()
    {
        $listId = $_GET['listId'];
        try {
            $short_list = new ShortListModel();
            $short_list->load($listId);
            $short_list->delete();
        } catch (\Exception $e){
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }

    /**
     * Function to send invites to all candidates on the specified shortList
     *
     * Get's the shortlist ID from the GET array
     */
    public function sendInviteAllAction(){

        $shortListId = $_GET["listID"];
        $content = $_GET['content'];

        try {
            $shortlist = new ShortListModel();
            $shortlist->load($shortListId);

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
        } catch (\Exception $e){
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }

    /**
     * Function to load the page to write an email to a short list of candidates
     *
     * Get's the ID of the shortlist being sent to from the GET array
     */
    public function writeEmailAction(){
        $page_from = $_GET["from"];
        $shortlistId = $_GET["list_id"];

        try {
            $shortlist = new ShortListModel();
            $shortlist->load($shortlistId);

            $employerId = $shortlist->getOwnerId();
            $employer = new EmployerModel();
            $employerLoadId = $employer->findLoadId($employerId);
            $employer->load($employerLoadId);

            // Check if employer has no calendar linked.
            // If true, cannot proceed.

            if ($employer->getCalendarLink() == "NULL") {
                $_SESSION['alert'] = "You cannot send an email without first linking a calendar to your account";
                if ($page_from == "search") {
                    $this->redirect('searchPage');
                } else if ($page_from == "home") {
                    $this->redirect('employerHomePage');
                }
                return;
            }

            $view = new View('writeEmail');
            $view->addData('employer', $employer);
            echo $view->addData('shortlist', $shortlist)->render();
        } catch (\Exception $e){
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }
}
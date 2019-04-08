<?php
namespace bjz\portal\model;
/**
 * Class ShortListModel
 *
 * Represents a Short List of candidates owned by a user
 *
 * @package bjz/portal
 */
class ShortListModel extends Model
{
    /**
     * @var int, the ID of the Short List
     */
    private $id;
    /**
     * @var int, the ID of the employer the Short List belongs to
     */
    private $owner_id;
    /**
     * @var string, the name of the Short List
     */
    private $name;
    /**
     * @var array, the list of candidates on the Short List
     */
    private $candidates;

    /**
     * @return int $this->id, the ID of the Short List
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id, the new ID of the Short List
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @return int $this->owner_id, the ID of the employer the Short List belongs to
     */
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * @param int $owner_id, the new ID of the employer the Short List belongs to
     */
    public function setOwnerId($owner_id)
    {
        $this->owner_id = $owner_id;
    }

    /**
     * @return string $this->name, the name of the Short List
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name, the new name of the Short List
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Loads short list information from the database
     *
     * @param int $id, the id of the short list to load
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this ShortListModel
     */
    public function load($id)
    {
        $id = $this->db->real_escape_string($id);
        if (!$result = $this->db->query("SELECT * FROM `short_list` WHERE `id` = '$id';")){
            throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: shortListLoad");
        }
        $result = $result->fetch_assoc();
        $this->id = $id;
        $this->owner_id = $result['owner_id'];
        $this->name = $result['name'];
        $candString = $result['candidates'];
        $this->candidates = explode(",", $candString);
        return $this;
    }

    /**
     * Saves short list information to the database
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this ShortListModel
     */
    public function save()
    {
        $owner_id = $this->owner_id ?? "NULL";
        $owner_id = $this->db->real_escape_string($owner_id);
        $name = $this->name ?? "NULL";
        $name = $this->db->real_escape_string($name);
        $cand = implode(",", $this->candidates);
        $cand = $this->db->real_escape_string($cand);
        if (!isset($this->id)) {

            if (!$result = $this->db->query("INSERT INTO `short_list` VALUES (NULL, '$owner_id', '$name', '$cand');")){
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: shortListSaveNew");
            }
            $this->id = $this->db->insert_id;
        } else {

            if (!$result = $this->db->query("UPDATE `short_list` SET `owner_id` = '$owner_id', `name` = '$name', `candidates` = '$cand' WHERE `id` = '$this->id';")){
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: shortListSaveExisting");
            }
        }
        return $this;
    }

    /**
     * Converts the candidate ID's from the database into CandidateModels as required.
     * @return \Generator|CandidateModel[]
     */
    public function getCandidates()
    {
        foreach ($this->candidates as $id) {
            // Use a generator to save on memory/resources
            // load accounts from DB one at a time only when required
            yield (new CandidateModel())->load($id);
        }
    }

    /**
     * Adds a candidate to the short list
     * @param $candId, the ID of the candidate to add
     */
    public function addCandidate($candId)
    {
        // Check if candidate is already in the short list
        if(in_array($candId, $this->candidates)){
            return;
        }
        if($this->candidates == "NULL"){
            $str =$candId;
            $this->candidates = explode(",", $str);
        } else {
            $str = implode(",", $this->candidates);
            $str .= "," . $candId;
            $this->candidates = explode(",", $str);
        }
    }


    /**
     * Renames the shortList as desired
     * @param $shortListID, the ID of the shortlist to rename
     * @param $name, the desired new name
     */
    public function renameShortList($shortListID, $name){
        if(!$result = $this->db->query("UPDATE `short_list` SET `name` = '$name' WHERE `id` = '$shortListID'")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: ShortListRename");
        }
    }

    /**
     * Deletes a specified candidate from a shortlist
     *
     * @param $shortListID, the ID of the shortList to delete the candidate from
     * @param $idToDelete, the ID of the Candidate being deleted
     */
    public function deleteFromShortList($shortListID, $idToDelete){
        if(!$result = $this->db->query("SELECT * FROM `short_list` WHERE `id` = '$shortListID'")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: ShortListRemoveCandidate");
        }
        $result = $result->fetch_assoc();
        $str = $result['candidates'];
        $strToReplace = explode(",", $str);
        $index = array_search($idToDelete,$strToReplace);
        unset($strToReplace[$index]);
        $newCandidateList = implode(",", $strToReplace);
        if($newCandidateList != "") {
            if (!$result = $this->db->query("UPDATE `short_list` SET `candidates` = '$newCandidateList' WHERE `id` = '$shortListID'")) {
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: ShortListSavingRemovedCandidate");
            }
            return false;
        } //else {
         //   if (!$result = $this->db->query("DELETE FROM `short_list` WHERE `id` = '$shortListID'")) {
         //       throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: ShortListSavingRemovedCandidateLast");
         //   }
            return true;
       // }
    }

    /**
     * creates the shortList as desired
     * @param $shortListName, the name of the new shortList
     * @param $ownerID, the ID of the user the shortList belongs to
     */
    public function newShortList($shortListName, $ownerID){
        if(!$result = $this->db->query("INSERT INTO `short_list` (`id`, `owner_id`, `name`, `candidates`) VALUES 
                                                    (NULL, '$ownerID', '$shortListName', 'NULL');")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: ShortListNew");
        }
    }
}

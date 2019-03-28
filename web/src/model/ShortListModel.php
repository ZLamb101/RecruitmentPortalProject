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
        $cand = $this->candidates ?? "NULL";
        $cand = $this->db->real_escape_string($cand);
        if (!isset($this->id)) {
            error_log("new SL");
            if (!$result = $this->db->query("INSERT INTO `short_list` VALUES (NULL, '$owner_id', '$name', '$cand');")){
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: shortListSaveNew");
            }
            $this->id = $this->db->insert_id;
        } else {
            error_log("update SL");
            if (!$result = $this->db->query("UPDATE `short_list` SET `owner_id` = '$owner_id', `name` = '$name', `candidates` = '$cand' WHERE `id` = '$id';")){
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
        if($this->candidates == ""){
            $this->candidates.=$candId;
        } else {
            $this->candidates .= "," . $candId;
        }
    }
}

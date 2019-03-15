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
     * @return array $this->candidates, the list of candidates on the Short List
     */
    public function getCandidates()
    {
        return $this->candidates;
    }

    /**
     * @param array $candidates, the new list of candidates on the Short List
     */
    public function setCandidates($candidates)
    {
        $this->candidates = $candidates;
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
        if (!$result = $this->db->query("SELECT * FROM `short_list` WHERE `id` = $id;")){
            throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: shortListLoad");
        }
        $result = $result->fetch_assoc();
        $this->id = $id;
        $this->owner_id = $result['owner_id'];
        $this->name = $result['name'];
        $this->candidates = new CandidateCollectionModel($id); // Must write to collect for short list and to collect for search
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
        // DOES NOT CURRENTLY SAVE CANDIDATES
        $id = $this->id ?? "NULL";
        $id = $this->db->real_escape_string($id);
        $owner_id = $this->owner_id ?? "NULL";
        $owner_id = $this->db->real_escape_string($owner_id);
        $name = $this->name ?? "NULL";
        $name = $this->db->real_escape_string($name);
        if (!isset($id)) {
            if (!$result = $this->db->query("INSERT INTO `short_list` VALUES (NULL, '$owner_id', '$name');")){
                throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: shortListSaveNew");
            }
            $this->id = $this->db->insert_id;
        } else {
            if (!$result = $this->db->query("UPDATE `short_list` SET `owner_id` = '$owner_id', `name` = '$name' WHERE `id` = $id;")){
                throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: shortListSaveExisting");
            }
        }
        return $this;
    }
}

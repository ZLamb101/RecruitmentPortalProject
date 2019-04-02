<?php
namespace bjz\portal\model;
use bjz\portal\model\CandidateModel;
/**
 * Class ShortListModel
 *
 * Represents an instance of a candidate's work experience
 *
 * @package bjz/portal
 */
class WorkExperienceModel extends Model
{
    /**
     * @var int, the ID of the instance
     */
    private $id;
    /**
     * @var int, the ID of the candidate the instance belongs to
     */
    private $owner_id;
    /**
     * @var string, the role the candidate had during the instance
     */
    private $role;
    /**
     * @var string, the company the candidate was employed by
     */
    private $employer;
    /**
     * @var int, the length of time a candidate was employed in this position, in months
     */
    private $duration;

    /**
     * @return int, the ID of the instance
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id, the new ID of the instance
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int, the ID of the candidate the instance belongs to
     */
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * @param int $owner_id, the new ID of the candidate the instance belongs to
     */
    public function setOwnerId($owner_id)
    {
        $this->owner_id = $owner_id;
    }

    /**
     * @return string, the role the candidate had during the instance
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role, the new role the candidate had during the instance
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return string, company the candidate was employed by
     */
    public function getEmployer()
    {
        return $this->employer;
    }

    /**
     * @param string $employer, the new company the candidate was employed by
     */
    public function setEmployer($employer)
    {
        $this->employer = $employer;
    }

    /**
     * @return int, the length of time a candidate was employed in this position, in months
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $duration, the new length of time a candidate was employed in this position, in months
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * Loads Work Experience instance information from the database
     *
     * @param int $id, the id of the Work Experience instance to load
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this WorkExperienceModel
     */
    public function load($id)
    {
        $id = $this->db->real_escape_string($id);
        if (!$result = $this->db->query("SELECT * FROM `work_experience` WHERE `id` = '$id';")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: workExperienceLoad");
        }
        $result = $result->fetch_assoc();
        $this->id = $id;
        $this->owner_id = $result['owner_id'];
        $this->role = $result['role'];
        $this->employer = $result['employer'];
        $this->duration = $result['duration'];
        return $this;
    }

    /**
     * Saves the Work Experience instance information to the database
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this WorkExperienceModel
     */
    public function save()
    {
       // $id = $this->id ?? NULL;
      //  $id = $this->db->real_escape_string($id);
        $owner_id = $this->owner_id ?? "NULL";
       // $owner_id = $this->db->real_escape_string($owner_id);
        $role = $this->role ?? "NULL";
        $role = $this->db->real_escape_string($role);
        $employer = $this->employer ?? "NULL";
        $employer = $this->db->real_escape_string($employer);
        $duration = $this->duration ?? "NULL";
        $duration = $this->db->real_escape_string($duration);
        if (!isset($id)) {
            if (!$result = $this->db->query("INSERT INTO `work_experience` VALUES (NULL, '$owner_id', '$role', '$employer', '$duration');")){
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: workExpSaveNew");
            }
            $this->id = $this->db->insert_id;
        } else {
            if (!$result = $this->db->query("UPDATE `work_experience` SET `owner_id` = '$owner_id', `role` = '$role', `employer` = '$employer' 
                                              `duration` = '$duration' WHERE `id` = '$id';")){
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: workExpSaveExisting");
            }
        }
        return $this;
    }

    public function delete($id){
        if(!$result = $this->db->query("DELETE from `work_experience` WHERE `id` = '$id'")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: workExpDelete");
        }
    }
}
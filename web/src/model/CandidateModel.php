<?php
namespace bjz\portal\model;
/**
 * Class CandidateModel
 *
 * Represents the candidate and their information.
 *
 * @package bjz\portal\model
 */
class CandidateModel extends UserModel
{
    /**
     * @var int, the ID of the candidate.
     */
    private $id;
    /**
     * @var int, the ID of the user account tied to this candidate
     */
    private $user_id;
    /**
     * @var string, the given name of the candidate
     */
    private $g_name;
    /**
     * @var string, the family name of the candidate
     */
    private $f_name;
    /**
     * @var string, the suburb of the candidates residence
     */
    private $location;
    /**
     * @var string, the availability of the candidate to work e.g. part time, full time
     */
    private $availability;
    /**
     * @var string, the skills the candidate has
     */
    private $skills;
    /**
     * @var array, an array of the candidates instances of work experience
     */
    private $workExperiences;
    /**
     * @var array, the array of the candidates qualifications
     */
    private $qualifications;

    /**
     * @return int $this->id, the ID of the candidate
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param int $id, the new ID of the candidate
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int $this->user_id, the id of the user tied to this candidate
     */
    public function getUserId()
    {
        return $this->user_id;
    }
    /**
     * @param $uid, the new user id tied to this candidate
     */
    public function setUserId($uid)
    {
        $this->user_id = $uid;
    }
    /**
     * @return string $this->g_name, the given name of the candidate
     */
    public function getGName()
    {
        return $this->g_name;
    }
    /**
     * @param string $g_name, the new given name of the candidate
     */
    public function setGName($g_name)
    {
        $this->g_name = $g_name;
    }
    /**
     * @return string $this->f_name, the family name of the candidate
     */
    public function getFName()
    {
        return $this->f_name;
    }

    /**
     * @param string $f_name, the new family name of the candidate
     */
    public function setFName($f_name)
    {
        $this->f_name = $f_name;
    }

    /**
     * @return string $this->location, the suburb the candidate lives in
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location, the new suburb the candidate lives in
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return string $this->availability, the availability of the candidate to work
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * @param string $availability, the new availability of the candidate to work
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;
    }

    /**
     * @return string, the candidates skills
     */
    public function getSkills(){
        return $this->skills;
    }

    /**
     * @param string $skills, the new set of skills of the candidate
     */
    public function setSkills($skills)
    {
        $this->skills = $skills;
    }

    /**
     * @return array $this->workExperiences, the array of the candidates work experience instances
     */
    public function getWorkExperience(){
        return $this->workExperiences;
    }

    /**
     * @param array $workExperiences, the new set of work experience of the candidate.
     */
    public function setWorkExperiences($workExperiences)
    {
        $this->workExperiences = $workExperiences;
    }

    /**
     * @return array $this->qualifications, the array of a candidates qualifications
     */
    public function getQualifications()
    {
        return $this->qualifications;
    }

    /**
     * @param array $qualifications, the new array of candidate qualifications
     */
    public function setQualifications($qualifications)
    {
        $this->qualifications = $qualifications;
    }

    /**
     * Loads candidate information from the database
     *
     * @param int $id, the id of the candidate to load
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this CandidateModel
     */
    public function load($id)
    {
        $id = $this->db->real_escape_string($id);
        if (!$result = $this->db->query("SELECT * FROM `candidate` WHERE `id` = $id;")) {
            throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: candidateLoad");
        }
        $result = $result->fetch_assoc();
        $this->user_id = $result['user_id'];
        $this->f_name = $result['f_name'];
        $this->g_name = $result['g_name'];
        $this->location = $result['location'];
        $this->availability = $result['availability'];
        $this->skills = $result['skills'];
        $workExp = new WorkExperienceCollectionModel($this->id);
        $this->workExperiences = $workExp->getWorkExperiences();
        $qualifications = new QualificationCollectionModel($this->id);
        $this->qualifications = $qualifications->getQualifications();
        $this->id = $id;
        parent::load($this->user_id);
        return $this;
    }

    /**
     * Saves candidate information to the database
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this CandidateModel
     */
    public function save(){
        // DOES NOT CURRENTLY SAVE W.E.
        // DOES NOT CURRENTLY SAVE QUALIFICATIONS
        $uid = $this->user_id ?? "NULL";
        $uid = $this->db->real_escape_string($uid);
        $given = $this->f_name ?? "NULL";
        $given = $this->db->real_escape_string($given);
        $family = $this->g_name ?? "NULL";
        $family = $this->db->real_escape_string($family);
        $location = $this->location ?? "NULL";
        $location = $this->db->real_escape_string($location);
        $avail = $this->availability ?? "NULL";
        $avail = $this->db->real_escape_string($avail);
        $skills = $this->skills ?? "NULL";
        $skills = $this->db->real_escape_string($skills);
        if(!isset($this->id)){
            // new candidate
            if(!$result = $this->db-query("INSERT INTO `candidate` VALUES (NULL, '$uid', '$given', '$family', '$location', '$avail', '$skills');")){
                throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: candSaveNew");
            }
            $this->id = $this->db->insert_id;
        } else {
            // existing candidate, update information
            if (!$result = $this->db->query("UPDATE `candidate` SET `user_id` = '$uid', `g_name` = '$given', `f_name` = '$family', 
                                              `location` = '$location', `availability` = '$avail', `skills` = '$skills' WHERE `id` = $this->id);")) {
                throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: candSaveExisting");
            }
        }
        return $this;
    }
}
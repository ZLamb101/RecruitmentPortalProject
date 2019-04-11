<?php
namespace bjz\portal\model;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


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
        if (!$result = $this->db->query("SELECT * FROM `candidate` WHERE `user_id` = $id;")) {
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: candidateLoad");
        }
        $result = $result->fetch_assoc();
        $this->user_id = $result['user_id'];
        $this->f_name = $result['f_name'];
        $this->g_name = $result['g_name'];
        $this->location = $result['location'];
        $this->availability = $result['availability'];
        $workExp = new WorkExperienceCollectionModel($result['id']);
        $this->workExperiences = $workExp->getWorkExperiences();
        $qualifications = new QualificationCollectionModel($result['id']);
        $this->qualifications = $qualifications->getQualifications();
        $skills = new SkillCollectionModel($result['id']);
        $this->skills = $skills->getSkills();
        $this->id = $result['id'];
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
        $given = $this->g_name ?? "NULL";
        $given = $this->db->real_escape_string($given);
        $family = $this->f_name ?? "NULL";
        $family = $this->db->real_escape_string($family);
        $location = $this->location ?? "NULL";
        $location = $this->db->real_escape_string($location);
        $avail = $this->availability ?? "NULL";
        $avail = $this->db->real_escape_string($avail);
        if(!isset($this->id)){
            // new candidate
            if(!$result = $this->db->query("INSERT INTO `candidate` VALUES (NULL, '$uid', '$family', '$given', '$location', '$avail');")){
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: candSaveNew");
            }
            $this->id = $this->db->insert_id;
        } else {
            // existing candidate, update information
            if (!$result = $this->db->query("UPDATE `candidate` SET `user_id` = '$uid', `f_name` = '$family', `g_name` = '$given', 
                                              `location` = '$location', `availability` = '$avail' WHERE `id` = $this->id;")) {
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: candSaveExisting");
            }
        }
        return $this;
    }

    /**
     * Collects the candidates preferred work experience id
     * @return int, the id of the preferred work experience
     */
    public function getPreferredWorkExperience()
    {
        if(!$result = $this->db->query("SELECT `work_experience`.`id` FROM `work_experience` 
                                        LEFT JOIN `preferences` ON `preferences`.`preferred_workEx_id` = `work_experience`.`id` 
                                        WHERE `preferences`.`owner_id` = '$this->id';")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: getprefworkexp");
        }
        $result = $result->fetch_assoc();
        return $result['id'];
    }

    /**
     * Collects the candidates preferred qualification id
     * @return int, the id of the preferred qualification
     */
    public function getPreferredQualification()
    {
        if(!$result = $this->db->query("SELECT `qualification`.`id` FROM `qualification` 
                                        LEFT JOIN `preferences` ON `preferences`.`preferred_qual_id` = `qualification`.`id` 
                                        WHERE `preferences`.`owner_id` = '$this->id';")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: getprefqual");
        }
        $result = $result->fetch_assoc();
        return $result['id'];
    }

    /**
     * Collects the candidates preferred skill id
     * @return int, the id of the preferred skill
     */
    public function getPreferredSkill()
    {
        if(!$result = $this->db->query("SELECT `skill`.`id` FROM `skill` 
                                        LEFT JOIN `preferences` ON `preferences`.`preferred_skill_id` = `skill`.`id` 
                                        WHERE `preferences`.`owner_id` = '$this->id';")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: getprefskill");
        }
        $result = $result->fetch_assoc();
        return $result['id'];
    }

    /***
     * Saves the candidates selected preferences to the database
     * @param $pref_qual, the id of the candidates preferred qualification
     * @param $pref_work, the id of the candidates preferred work experience
     * @param $pref_skill, the id of the candidates preferred skill
     */
    public function savePreferences($pref_qual, $pref_work, $pref_skill)
    {
        if(!$result = $this->db->query("SELECT * FROM `preferences` WHERE `owner_id` = '$this->id';")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: saveprefprecheck");
        }

        if($result->num_rows == 0){
            if(!$result = $this->db->query("INSERT INTO `preferences` VALUES (NULL, '$this->id', '$pref_qual', '$pref_work', '$pref_skill');")){
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: saveprefNEW");
            }
        } else {
            $result = $result->fetch_assoc();
            $pref_id = $result['id'];
            error_log("UPDATE: PREF ID FOUND IS : ".$pref_id);
            if(!$result = $this->db->query("UPDATE `preferences` SET `owner_id` = '$this->id', `preferred_qual_id` = '$pref_qual', 
                                            `preferred_workEx_id` = '$pref_work', `preferred_skill_id` = '$pref_skill'
                                            WHERE `id` = '$pref_id';")){
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: saveprefUPDATE");
            }
        }
    }

    /**
     * Retrieves the candidates preferred qualification
     * @return string, the correct display output of the preferred qualification
     */
    public function displayPreferredQualification()
    {
        if(!$result = $this->db->query("SELECT `preferred_qual_id` FROM `preferences` WHERE `owner_id` = '$this->id';")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: displayPrefQual1");
        }

        if($result->num_rows != 0){
            $result = $result->fetch_assoc();
            $prefID = $result['preferred_qual_id'];
            if(!$result = $this->db->query("SELECT `level`, `type`, `year`, `major` FROM `qualification`
                                            LEFT JOIN `qual_level` ON `qualification`.`level_id` = `qual_level`.`id` 
                                            LEFT JOIN `qual_type` ON `qualification`.`type_id` = `qual_type`.`id`
                                            WHERE `qualification`.`id` = '$prefID';")){
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: displayPrefQual2");
            }
            $result = $result->fetch_assoc();
            return $result['level'] . " of " . $result['type'] . " (" . $result['major'] . ") - " . $result['year'];
        } else {
            return "";
        }
    }

    /**
     * Retrieves the candidates preferred work experience
     * @return string, the correct display output of the preferred work experience
     */
    public function displayPreferredWorkExperience()
    {
        if(!$result = $this->db->query("SELECT `preferred_workEx_id` FROM `preferences` WHERE `owner_id` = '$this->id';")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: displayPrefWork1");
        }

        if($result->num_rows != 0){
            $result = $result->fetch_assoc();
            $prefID = $result['preferred_workEx_id'];
            if(!$result = $this->db->query("SELECT `role`, `employer`, `duration` FROM `work_experience`
                                            WHERE `work_experience`.`id` = '$prefID';")){
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: displayPrefWork2");
            }
            $result = $result->fetch_assoc();
            return $result['role'] . " - " . $result['employer'] . " - " . $this->convertDuration($result['duration']);
        } else {
            return "";
        }
    }

    /**
     * Retrieves the candidates preferred skill
     * @return string, the correct display output of the preferred skill
     */
    public function displayPreferredSkill()
    {
        if(!$result = $this->db->query("SELECT `preferred_skill_id` FROM `preferences` WHERE `owner_id` = '$this->id';")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: displayPrefSkill1");
        }

        if($result->num_rows != 0){
            $result = $result->fetch_assoc();
            $prefID = $result['preferred_skill_id'];
            if(!$result = $this->db->query("SELECT field, sub_field, contents FROM skill 
                                         LEFT JOIN field ON skill.`field_id` = field.`id`
                                         LEFT JOIN sub_field ON skill.`sub_field_id` = sub_field.`id`
                                         WHERE skill.`id` = '$prefID';")){
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: displayPrefSkill2");
            }
            $result = $result->fetch_assoc();
            return $result['field'] . " - " . $result['sub_field'] . " - " . $result['contents'];
        } else {
            return "";
        }
    }

    /**
     * Converts the numeric duration of a candidates work experience to a display format
     * @param int duration, the duration (in months) of a work experience instance.
     * @return string, the string output in either year-month format or just months
     */
    public function convertDuration($duration)
    {
        if($duration > 12){
            $years = intdiv($duration, 12);
            $months = $duration % 12;
            if($months != 0){
                return $years . " Year". $this->isOne($years). ", ". $months . " Month" . $this->isOne($months);
            } else{
                return $years . " Year". $this->isOne($years);
            }
        } else {
            return $duration . " Month". $this->isOne($duration);
        }
    }

    /***
     * Determines if a word needs to be pluralised based on the numerical value associated with it
     * @param $value, the value to be checked for a 1
     * @return string, the correct plural/non plural for the word
     */
    public function isOne($value)
    {
        if($value == 1){
            return "";
        } else{
            return "s";
        }
    }









}
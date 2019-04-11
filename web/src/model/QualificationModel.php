<?php
namespace bjz\portal\model;
/**
 * Class QualificationModel
 *
 * Represents a qualification of a candidate
 *
 * @package bjz/portal
 */
class QualificationModel extends Model
{
    /**
     * @var int, the ID of the qualification
     */
    private $id;
    /**
     * @var int, the ID of the candidate the qualifications belongs to
     */
    private $owner_id;

    /**
     * @var int, the ID of the level the qualifications contains
     */
    private $level_id;

    /**
     * @var int, the ID of the type the qualifications contains
     */
    private $type_id;

    /**
     * @var int, the year the qualification was achieved
     */
    private $year;

    /***
     * @var string, the major selected for the qualification
     */
    private $major;

    /**
     * @return int, the ID of the qualification
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id, the new ID of the qualification
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int, the ID of the candidate the qualifications belongs to
     */
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * @param int $owner_id, the new ID of the candidate the qualifications belongs to
     */
    public function setOwnerId($owner_id)
    {
        $this->owner_id = $owner_id;
    }

    /**
     * @return int, the ID of the level the qualifications contains
     */
    public function getLevelId()
    {
        return $this->level_id;
    }

    /**
     * @param int $level_id, the new ID of the level the qualifications contains
     */
    public function setLevelId($level_id)
    {
        $this->level_id = $level_id;
    }

    /**
     * @return int, the ID of the type the qualifications contains
     */
    public function getTypeId()
    {
        return $this->type_id;
    }

    /**
     * @param int $type_id, the new ID of the type the qualifications contains
     */
    public function setTypeId($type_id)
    {
        $this->type_id = $type_id;
    }

    /**
     * @return int, the year the qualification was achieved
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param int $year, the new year the qualification was achieved
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return string, the major associated with the qualification
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * @param string $major, the new major associated with the qualification
     */
    public function setMajor($major)
    {
        $this->major = $major;
    }



    /**
     * Loads qualification information from the database
     *
     * @param int $id, the id of the qualification to load
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this QualificationModel
     */
    public function load($id)
    {

        $id = $this->db->real_escape_string($id);
        if (!$result = $this->db->query("SELECT * FROM `qualification` WHERE `id` = '$id';")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: qualificationLoad");
        }
        $result = $result->fetch_assoc();
        $this->id = $id;
        $this->owner_id = $result['owner_id'];
        $this->level_id = $result['level_id'];
        $this->type_id = $result['type_id'];
        $this->year = $result['year'];
        $this->major = $result['major'];
        return $this;
    }

    /**
     * Saves the qualification instance information to the database
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this QualificationModel
     */
    public function save()
    {

        $owner_id = $this->owner_id ?? "NULL";

        $level_id = $this->level_id ?? "NULL";

        $type_id = $this->type_id ?? "NULL";

        $year = $this->year ?? "NULL";
        $year = $this->db->real_escape_string($year);

        $major = $this->major ?? "NULL";
        $major = $this->db->real_escape_string($major);


        if (!isset($this->id)) {
            if (!$result = $this->db->query("INSERT INTO `qualification` VALUES (NULL, '$owner_id', '$level_id','$type_id', '$year', '$major');")){
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: qualSaveNew");
            }
            $this->id = $this->db->insert_id;
        } else {
            if (!$result = $this->db->query("UPDATE `qualification` SET `owner_id` = '$owner_id', `level_id` = '$level_id', `type_id` = '$type_id', `year` = '$year', 
                                              `major` = '$major' WHERE `id` = '$this->id';")){
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: qualSaveExisting");
            }
        }
        return $this;
    }

    public function delete($id){
        if(!$result = $this->db->query("DELETE from `qualification` WHERE `id` = '$id'")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: qualDelete");
        }
    }


    /**
     * Function to get all the levels within the database
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return bool|\mysqli_result all the fields and corresponding id's
     */
    public function getLevels(){
        if(!$result = $this->db->query("SELECT * FROM `qual_level`;")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: qualGetLevels");
        }
        return $result;
    }

    /**
     * Function to get all the types within the database
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return bool|\mysqli_result all sub-fields of the specified field
     */
    public function getTypes(){
        if(!$result = $this->db->query("SELECT * FROM `qual_type` ;")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: qualGetTypes");
        }
        return $result;
    }

    /**
     * Function to get the qual level associated to id
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return bool|\mysqli_result all the fields and corresponding id's
     */
    public function findLevel($id){
        if(!$result = $this->db->query("SELECT `level` FROM `qual_level` WHERE `id` = '$id';")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: qualGetLevel");
        }
        $result = $result->fetch_assoc();
        $result = $result['level'];
        return $result;

    }

    /**
     * Function to get qual type associated to id
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return bool|\mysqli_result all the fields and corresponding id's
     */
    public function findType($id){
        if(!$result = $this->db->query("SELECT `type` FROM `qual_type` WHERE `id` = '$id';")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: qualGetType");
        }
        $result = $result->fetch_assoc();
        $result = $result['type'];
        return $result;

    }


}
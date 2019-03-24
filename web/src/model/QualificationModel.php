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
     * @var string, the name of the qualification
     */
    private $name;
    /**
     * @var int, the year the qualification was achieved
     */
    private $year;

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
     * @return string, the name of the qualification
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name, the new name of the qualification
     */
    public function setName($name)
    {
        $this->name = $name;
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
            throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: qualificationLoad");
        }
        $result = $result->fetch_assoc();
        $this->id = $id;
        $this->owner_id = $result['owner_id'];
        $this->name = $result['name'];
        $this->year = $result['$year'];
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
        $id = $this->id ?? "NULL";
       // $id = $this->db->real_escape_string($id);
        $owner_id = $this->owner_id ?? "NULL";
       // $owner_id = $this->db->real_escape_string($owner_id);
        $name = $this->name ?? "NULL";
        $name = $this->db->real_escape_string($name);
        $year = $this->year ?? "NULL";
        $year = $this->db->real_escape_string($year);
        if (!isset($id)) {
            if (!$result = $this->db->query("INSERT INTO `qualification` VALUES (NULL, '$owner_id', '$name', '$year');")){
                throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: workExpSaveNew");
            }
            $this->id = $this->db->insert_id;
        } else {
            if (!$result = $this->db->query("UPDATE `qualification` SET `owner_id` = '$owner_id', `name` = '$name', `year` = '$year' 
                                              WHERE `id` = $id;")){
                throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: workExpSaveExisting");
            }
        }
        return $this;
    }


}
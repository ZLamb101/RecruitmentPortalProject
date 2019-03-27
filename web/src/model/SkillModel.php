<?php
/**
 * Created by PhpStorm.
 * User: zane
 * Date: 27/03/19
 * Time: 8:55 AM
 */

namespace bjz\portal\model;

/**
 * Class SkillModel
 *
 * Represents a skill of a candidate
 *
 * @package bjz/portal
 */
class SkillModel extends Model
{

    /**
     * @var int, the ID of the skill
     */
    private $id;

    /**
     * @var int, the ID of the candidate the skill belongs to
     */
    private $owner_id;

    /**
     * @var string, the name of the field the skill is under
     */
    private $field;

    /**
     * @var string, the name of the sub-field the skill is under
     */
    private $sub_field;

    /**
     * @var string, the contents describing their skill
     */
    private $contents;

    /**
     * @return int, the ID of the skill
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id, the new ID of the skill
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int, the ID of the candidate the skill belongs to
     */
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * @param int $owner_id, the new ID of the candidate the skill belongs to
     */
    public function setOwnerId($owner_id)
    {
        $this->owner_id = $owner_id;
    }

    /**
     * @return string, the name of the field the sub-field and skill is associated with
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param string $field, the new name field the sub-field and skill is associated with
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @return string, the name of the sub-field the skill is associated with
     */
    public function getSubField()
    {
        return $this->sub_field;
    }

    /**
     * @param string $sub_field, the new name of the sub-field the skill is associated with
     */
    public function setSubField($sub_field)
    {
        $this->sub_field = $sub_field;
    }

    /**
     * @return string, the message explaining their skill in certain sub-field
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param string $contents, the new message explaining their skill in certain sub-field
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
    }

    /**
     * Loads skill information from the database
     *
     * @param int $id, the id of the skill to load
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this SkillModel
     */
    public function load($id)
    {
        $id = $this->db->real_escape_string($id);
        if (!$result = $this->db->query("SELECT * FROM `skill` WHERE `id` = '$id';")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: qualificationLoad");
        }
        $result = $result->fetch_assoc();
        $this->id = $id;
        $this->owner_id = $result['owner_id'];
        $this->field = $result['field'];
        $this->sub_field = $result['sub_field'];
        $this->contents = $result['contents'];
        return $this;
    }

    /**
     * Saves the skill instance information to the database
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this SkillModel
     */
    public function save()
    {

        $owner_id = $this->owner_id ?? "NULL";
        $field = $this->field ?? "NULL";
        $field = $this->db->real_escape_string($field);
        $sub_field = $this->sub_field ?? "NULL";
        $sub_field = $this->db->real_escape_string($sub_field);
        $contents = $this->contents ?? "NULL";
        $contents = $this->db->real_escape_string($contents);

        if (!isset($id)) {
            if (!$result = $this->db->query("INSERT INTO `skill` VALUES (NULL, '$owner_id', '$field', '$sub_field', '$contents');")){
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: skillSaveNew");
            }
            $this->id = $this->db->insert_id;
        } else {
            if (!$result = $this->db->query("UPDATE `skill` SET `owner_id` = '$owner_id', `field` = '$field', `sub_field` = '$sub_field', `contents` = '$contents' 
                                              WHERE `id` = '$id';")){
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: skillSaveExisting");
            }
        }
        return $this;
    }

    /**
     * Function to get all the fields within the database
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return bool|\mysqli_result all the fields and corresponding id's
     */
    public function getFields(){
        if(!$result = $this->db->query("SELECT * FROM `field`")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: skillGetFields");
        }
        return $result;
    }

    /**
     * Function to get all the sub_fields based on a field id
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @param $id the id of the parent field
     * @return bool|\mysqli_result all sub-fields of the specified field
     */
    public function getSubFields($id){
        if(!$result = $this->db->query("SELECT * FROM `sub_field` WHERE `field_id` = '$id';")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: skillGetSubFields");
        }
        return $result;
    }
}
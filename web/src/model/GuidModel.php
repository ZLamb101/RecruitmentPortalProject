<?php
namespace bjz\portal\model;
use bjz\portal\model\Model;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
/**
 * Class GuidModel
 *
 * Represents a global unique identifier. Used to create time sensitive links
 * for password recovery.
 *
 * @package bjz/portal
 */
class GuidModel extends Model
{
    /**
     * @var int, id of the guid
     */
    private $id;
    /**
     * @var int, the user id for the guid
     */
    private $user_id;
    /**
     * @var string, the uuid
     */
    private $uuid;
    /**
     * @var string, the expired time of the
     */
    private $expiredTime;

    /**
     * Returns the ID of the guid
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the ID of the guid
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns the ID of the user the guid is associated to.
     * @return string
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Sets the user associated with the guid, based on their ID
     * @param string $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Gets the guid
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Sets the guid
     * @param string $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * Returns the time the guid is set to expire
     * @return string
     */
    public function getExpiredTime()
    {
        return $this->expiredTime;
    }

    /**
     * Sets the time the guid will expire
     * @param string $expiredTime
     */
    public function setExpiredTime($expiredTime)
    {
        $this->expiredTime = $expiredTime;
    }

    /**
     * Loads GUID information from the database
     *
     * @param int $uuid, the uuid of the guid to load
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this GuidModel
     */
    public function load($uuid)
    {
        if (!$result = $this->db->query("SELECT * FROM `passwordguids` WHERE `guid` = '$uuid';")) {
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: guidLoad");
        }
        $result = $result->fetch_assoc();
        $this->user_id = $result['user_id'];
        $this->uuid = $result['guid'];
        $this->expireTime = $result['expireTime'];
        $this->id = $result['id'];
        return $this;
    }

    /**
     * Saves guid information to the database
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this GuidModel
     */
    public function GuidModel($username)
    {
        $this->uuid = $this->gen_uuid();

        $username = $this->db->real_escape_string($username);
        $this->user_id = $this->findID($username);

        $this->expireTime = date('Y-m-d H:i:s');

        // New user - Perform INSERT
        if (!$result = $this->db->query("INSERT INTO `passwordguids` VALUES (NULL,'$this->user_id','$this->uuid', '$this->expireTime');")) {
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: guidSaveNew");
        }
        $this->id = $this->db->insert_id;

        return $this;
    }

    /**
     * Deletes guid from the database
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this GuidModel
     */
    public function delete()
    {
        //Not sure if this should be allowed. Will not currently delete children?
        if (!$result = $this->db->query("DELETE FROM `user` WHERE `user`.`id` = $this->id;")) {
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: guidDelete");
        }
        return $this;
    }

     /***
     * Searches for the user_id of an explicit username
     *
     * @param $username string, the username to look for in the database
     * @return string, the user_id for the associated username
     *
     * @throws \mysqli_sql_exception, if the SQL query fails
     */
    public function findID($username){
         if (!$result = $this->db->query("SELECT * FROM `user` WHERE `user`.`username` = '$username';")) {
            throw new \mysqli_sql_exception($this->db->error, $this->db->errno);
        }
      
        $result = $result->fetch_assoc();
        if(!$result){
            throw new \mysqli_sql_exception("Failed");
        }
        return $result['id'];

    }

    /***
     * Generates a new uuid and returns it for use in a verification link
     * @return string, the uuid generated
     */
    function createVerificationLink(){

        $this->uuid = $this->gen_uuid();
        $expireTime = date('Y-m-d H:i:s');

        // New user - Perform INSERT
        if (!$result = $this->db->query("INSERT INTO `passwordguids` VALUES (NULL,'$this->user_id','$this->uuid', '$expireTime');")) {
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: guidSaveNew");
        }
        $this->id = $this->db->insert_id;

        return $this->uuid;
    }

    /**
     * Generates a unique identifier for password recovery
     * @return string, a unique identifier to send in an email link
     */
    function gen_uuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    /**
     * Deletes the guid from the database
     */
    function deleteGuid(){
         if (!$result = $this->db->query(" DELETE FROM `passwordguids` WHERE `guid` = '$this->uuid' ;")) {
             throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: guidSaveNew");
         }
    }







}

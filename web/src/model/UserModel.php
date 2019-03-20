<?php
namespace bjz\portal\model;
use bjz\portal\model\Model;
/**
 * Class UserModel
 *
 * Represents a user of the system
 *
 * @package bjz/portal
 */
class UserModel extends Model
{
    /**
     * @var int, id of the user
     */
    private $id;
    /**
     * @var string, username of the user
     */
    private $username;
    /**
     * @var string, the password of the user (will be hashed)
     */
    private $password;
    /**
     * @var string, the email address of the user
     */
    private $email;
    /**
     * @var string, the phone number of the user
     */
    private $phone_number;
    /**
     * @return int $this->id, the id of the user
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return string $this->username, the username of the user
     */
    public function getUsername()
    {
        return $this->username;
    }
    /**
     * @param string $username, the username of the user
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
    /**
     * @return string $this->password, the password of the user
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * @param string $password, the password of the user
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
    /**
     * @return string $this->email, the email address of the user
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * @param string $email, the new email address of the user
     */
    public function setEmail($email){
        $this->email = $email;
    }
    /**
     * @return string $this->phone_number, the phone number of the user
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }
    /**
     * @param string $phone_number, the new phone number for the user
     */
    public function setPhoneNumber($phone_number){
        $this->phone_number = $phone_number;
    }
    /**
     * Loads user information from the database
     *
     * @param int $id, the id of the user to load
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this UserModel
     */
    public function load($id)
    {
        $id = $this->db->real_escape_string($id);
        if (!$result = $this->db->query("SELECT * FROM `user` WHERE `id` = $id;")) {
            throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: userLoad");
        }
        $result = $result->fetch_assoc();
        $this->username = $result['username'];
        $this->password = $result['password'];
        $this->email = $result['email'];
        $this->phone_number = $result['phone_number'];
        $this->id = $id;
        return $this;
    }
    /**
     * Saves user information to the database
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this UserModel
     */
    public function save()
    {
        $username = $this->username ?? "NULL";
        $username = $this->db->real_escape_string($username);
        $password = $this->password ?? "NULL";
        $password = $this->db->real_escape_string($password);
        $email = $this->email ?? "NULL";
        $email = $this->db->real_escape_string($email);
        $phone = $this->phone_number ?? "NULL";
        $phone = $this->db->real_escape_string($phone);
        if (!isset($this->id)) {
            // New user - Perform INSERT
            $password = password_hash($password, PASSWORD_BCRYPT);
            if (!$result = $this->db->query("INSERT INTO `user` VALUES (NULL,'$username','$password','$email','$phone');")) {
                throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: userSaveNew");
            }
            $this->id = $this->db->insert_id;
        } else {
            // saving existing user - perform UPDATE
            if (!$result = $this->db->query("UPDATE `user` SET `username` = '$username', `password` = '$password', 
                                              `email` = '$email', `phone_number` = '$phone' WHERE `id` = $this->id;")) {
                throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: userSaveExisting");
            }
        }
        return $this;
    }

    /**
     * Deletes user from the database
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this UserModel
     */
    public function delete()
    {
        //Not sure if this should be allowed. Will not currently delete children?
        if (!$result = $this->db->query("DELETE FROM `user` WHERE `user`.`id` = $this->id;")) {
            throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: userDelete");
        }
        return $this;
    }

    public function validateLogin($username, $password){
        error_log("$username $password");
        if(!$result = $this->db->query("SELECT * FROM `user` WHERE `username` = '$username';")){
            throw new \mysqli_sql_exception("An account with that username doesn't exist");
        }
        $result = $result->fetch_assoc();
        error_log("$result");
        if(!$result){
            throw new \mysqli_sql_exception("Failed");
        }
        $resultPassword = $result['password'];
        if(password_verify($password, $resultPassword)){
            return $result['id'];
        } else {
            throw new \mysqli_sql_exception("Password doesn't match");     //CHANGE
        }
    }

    public function determineType($userID){
        if($result = $this->db->query("SELECT * FROM `employer` WHERE `user_id` = '$userID'")){
            return 2;
        } else {
            return 1;
        }
    }

     /***
     * Checks whether an account with the submitted username already exists.
     *
     * @param $username string, the username to look for in the database
     * @return string, really a boolean, but read as a string for use in Javascript, Returns true
     *         if there are no existing accounts with the submitted username meaning a user can register
     *         that name.
     *
     * @throws \mysqli_sql_exception, if the SQL query fails
     */
    public function findName($username)
    {
        $username = mysqli_real_escape_string($this->db, $username);
        if (!$result = $this->db->query("SELECT * FROM `user` WHERE `user`.`username` = '$username';")) {
            throw new \mysqli_sql_exception($this->db->error, $this->db->errno);
        }
        if ($result->num_rows == 0) {
            return 'true'; // If no other user exists with this username, return true
        } else {
            return 'false';
        }
    }

}

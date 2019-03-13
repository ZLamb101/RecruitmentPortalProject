<?php
namespace bjz\portal\model;
use Exception;
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
        if (!isset($this->id)) {
            // New user - Perform INSERT
            $password = password_hash($password, PASSWORD_BCRYPT);
            if (!$result = $this->db->query("INSERT INTO `user` VALUES (NULL,'$username','$password');")) {
                throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: userSaveNew");
            }
            $this->id = $this->db->insert_id;
        } else {
            // saving existing user - perform UPDATE
            if (!$result = $this->db->query("UPDATE `user` SET `username` = '$username', `password` = '$password' WHERE `id` = $this->id;")) {
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
        if (!$result = $this->db->query("DELETE FROM `user` WHERE `user`.`id` = $this->id;")) {
            throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: userDelete");
        }
        return $this;
    }
}

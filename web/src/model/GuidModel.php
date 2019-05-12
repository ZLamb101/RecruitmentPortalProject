<?php
namespace bjz\portal\model;
use bjz\portal\model\Model;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param string $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getExpiredTime()
    {
        return $this->expiredTime;
    }

    /**
     * @param string $expiredTime
     */
    public function setExpiredTime($expiredTime)
    {
        $this->expiredTime = $expiredTime;
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
     * Saves user information to the database
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this UserModel
     */
    public function GuidModel($username)
    {
        $this->uuid = $this->gen_uuid();

        $username = $this->db->real_escape_string($username);
        $this->user_id = $this->findID($username);

        $this->expireTime = date('Y-m-d H:i:s');
        error_log($this->expireTime);

        // New user - Perform INSERT
        if (!$result = $this->db->query("INSERT INTO `passwordguids` VALUES (NULL,'$this->user_id','$this->uuid', '$this->expireTime');")) {
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: guidSaveNew");
        }
        $this->id = $this->db->insert_id;

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
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: userDelete");
        }
        return $this;
    }

    /**
     * Validates that the supplied login is correct.
     *
     * @param $username the username as entered by the user
     * @param $password the password as entered by the user
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $result['id'] the ID of the successfully validated user
     */
    public function validateLogin($username, $password){
        if(!$result = $this->db->query("SELECT * FROM `user` WHERE `username` = '$username';")){
            throw new \mysqli_sql_exception("An account with that username doesn't exist");
        }
        $result = $result->fetch_assoc();
        if(!$result){
            throw new \mysqli_sql_exception("Failed");
        }
        $resultPassword = $result['password'];
        if(password_verify($password, $resultPassword)){
            return $result['id'];
        } else {
            throw new \mysqli_sql_exception("Password doesn't match");
        }
    }

    /**
     * Checks whether the logging in user is a candidate or employer
     *
     * @param $userID the ID of the user being checked
     *
     * @return int either 1 or 2 corresponding to the type of user being logged in
     */
    public function determineType($userID){
        $result = $this->db->query("SELECT * FROM `employer` WHERE `user_id` = '$userID'");
        if($result->num_rows == 1){
            return 2;       //2 represents employer
        } else {
            return 1;       //1 represents candidate
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


    /**
     * This function sends a confirmation to users email when account has been created.
     *
     */
    public function sendConfirmationEmail($email, $username)
    {

        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions

        //Server settings
        $mail->SMTPDebug = false;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'Vestarecruit@gmail.com';                 // SMTP username
        $mail->Password = 'Bobtool22';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;

        // TCP port to connect to
        //Recipients
        $mail->setFrom('Vestarecruit@gmail.com', 'Vestarecruit');
        $mail->addAddress($email);     // Add a recipient
        $mail->addBCC('Vestarecruit@gmail.com');
        //Content

        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Vesta Recruit Account Confirmation';
        $mail->Body = 'Welcome to Vesta Recruit, ' . $username . ' we have confirmed your details and your account has been registered. To begin using Vesta Recruit, head back to our website and login!';
        $mail->AltBody = 'Welcome to Vesta Recruit, ' . $username . ' we have confirmed your details and your account has been registered. To begin using Vesta Recruit, head back to our website and login!';

        $mail->send();
        // echo 'Message has been sent';

    }

    /**
     * This function sends a email when a user is recovering their password
     *
     */
    public function sendPasswordRecoveryEmail($uuid)
    {

        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions

        //Server settings
        $mail->SMTPDebug = false;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'Vestarecruit@gmail.com';                 // SMTP username
        $mail->Password = 'Bobtool22';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;

        // TCP port to connect to
        //Recipients
        $mail->setFrom('Vestarecruit@gmail.com', 'Vestarecruit');
        $mail->addAddress($this->email);     // Add a recipient
        $mail->addBCC('Vestarecruit@gmail.com');
        //Content

        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Vesta Recruit Password Recovery';
        $mail->Body = 'You lost your password. too bad nerd follow this link <a href="http://localhost:8000/Verify/?id='.$uuid.'"> link </a>';
        $mail->AltBody = 'You lost your password. too bad nerd';

        $mail->send();
        // echo 'Message has been sent';

    }


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

    function createVerificationLink(){

        $uuid = $this->gen_uuid();

        $username = $this->username ?? "NULL";
        $username = $this->db->real_escape_string($username);
        $user_id = $this->findID($username);

        $expireTime = date('Y-m-d H:i:s');
        error_log($expireTime);

        

        // New user - Perform INSERT
        if (!$result = $this->db->query("INSERT INTO `passwordguids` VALUES (NULL,'$user_id','$uuid', '$expireTime');")) {
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: guidSaveNew");
        }
        $this->id = $this->db->insert_id;

        return $uuid;
    }







}

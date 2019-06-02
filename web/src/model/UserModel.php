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
     * Gets the user's username
     * @return string $this->username, the username of the user
     */
    public function getUsername()
    {
        return $this->username;
    }
    /**
     * Sets the user's username
     * @param string $username, the username of the user
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
    /**
     * Returns the password of the user
     * @return string $this->password, the password of the user
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * Sets the password of the user
     * @param string $password, the password of the user
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
    /**
     * Return the email address of the user
     * @return string $this->email, the email address of the user
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * Sets the email address of the user
     * @param string $email, the new email address of the user
     */
    public function setEmail($email){
        $this->email = $email;
    }
    /**
     * Returns the phone number of the user
     * @return string $this->phone_number, the phone number of the user
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }
    /**
     * Gets the phone number of the user
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
        if (!$result = $this->db->query("SELECT * FROM `user` WHERE `id` = '$id';")) {
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: userLoad");
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
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: userSaveNew");
            }
            $this->id = $this->db->insert_id;
        } else {
            // saving existing user - perform UPDATE
            if (!$result = $this->db->query("UPDATE `user` SET `username` = '$username', `password` = '$password', 
                                              `email` = '$email', `phone_number` = '$phone' WHERE `id` = $this->id;")) {
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: userSaveExisting");
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
     * @param $email string, the email address to send the email to
     * @param $username string, the username of the user
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
     * @param $uuid string, the universally unique identifier assigned to account to reset the password of that account
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
        $mail->Body = 'To reset your password, please click <a href="http://localhost:8000/Verify/?id='.$uuid.'">here</a>.';
        $mail->AltBody = 'Reset your password';

        $mail->send();
        // echo 'Message has been sent';

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
     * Checks that a uuid is valid
     * @param $str, the uuid used to access the Verify webpage
     * @return bool, returns true if the uuid exists
     */
    function check_uuid($str){
        if (!$result = $this->db->query("SELECT * FROM `passwordguids` WHERE `guid` = '$str';")) {
            throw new \mysqli_sql_exception($this->db->error, $this->db->errno);
        }
        if($result->num_rows == 0){
            return false;
        }
        return true;
    }
}

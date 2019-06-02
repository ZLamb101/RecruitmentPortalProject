<?php

namespace bjz\portal\controller;
use bjz\portal\model\UserModel;
use bjz\portal\model\GuidModel;
use bjz\portal\view\View;
session_start();


/**
 * Class UserController
 *
 * Contains functionality relevant to both types of User Controllers
 *
 * @package bjz\portal\controller
 */
class UserController extends Controller
{
    /**
     * Action to log the user out
     */
    public function logoutAction()
    {
        session_unset();
        $this->redirect("home");
    }

    /**
     * Manages a request to register a user, checks if the username already exists in the database.
     * Gets the username from the GET array
    */
    public function validateUsernameAction()
    {
        $username = $_GET["name"];
        try {
            $a = new UserModel();
            echo $a->findName($username);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }

    /**
     * Action to log in
     */
    public function loginAction()
    {
        try{
            $user = new UserModel();
            $userID = $user->validateLogin($_POST['username_input'], $_POST['password_input']);

            $userType = $user->determineType($userID);

            $_SESSION["loginStatus"] = $userType;
            $_SESSION["UserID"] = $userID;

            if($userType == 1) {
                $this->redirect("candidateHomePage");
            } else {
                $this->redirect("employerHomePage");
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $_SESSION["loginStatus"] = 0;
            $_SESSION["invalidDetails"] = 1;
            $this->redirect("home");
        }
    }


    /**
     * Function to update a User account
     * Takes the inputs from post request and loads a User account
     * Then updates any changed data
     */
    public function updateAccountAction(){
        try {
            $account = new UserModel();
            $account->load($_SESSION['UserID']);

            $account->setPhoneNumber($_POST['phone-number']);
            $account->setEmail($_POST['email']);

            $account->save();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }


    /**
     * Function to create an account
     */
    public function createAccountAction()
    {
        try {
            $account = new UserModel();

            $account->setUsername($_POST['username']);
            $account->setEmail($_POST['email']);
            $account->setPassword($_POST['password']);
            $account->setPhoneNumber($_POST['phone-number']);

            $account->save();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }


    /**
     * Function send verification email to owner of the account.
     * checks if account exists,
     * Generates guid
     * Send Email to email address attached to account
     */
    public function passwordRecoveryAction()
    {
        try {
            $account = new UserModel();
            $username = $_POST['username'];
            if($account->findName($username)){
                $id = $account->findId($username);
                $account->load($id);
                $guid = new GuidModel();
                $guid->setUserId($id);
                $guid->createVerificationLink();
                $account->sendPasswordRecoveryEmail($guid->getUuid());
            }
            $this->redirect('passwordRecoveryConfirmationPage');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }

    /**
     * Function send update password after resetting
     */
    public function updatePassword(){
        $password = $_POST['password'];
        $confirmPassword = $_POST['password_confirm'];
        $uuid = $_POST['uuid'];
        try {
            $guid = new GuidModel();
            $guid->load($uuid);

            if ($password != $confirmPassword) {
                $this->redirect('updatePassword');
            } else {
                $account = new UserModel();
                $account->load($guid->getUserId());
                $password = password_hash($password, PASSWORD_BCRYPT);
                $account->setPassword($password);

                $account->save();
                $this->redirect('updatePasswordConfirmation');
                $guid->deleteGuid();
            }
        } catch (\Exception $e){
            error_log($e->getMessage());
            $this->redirect('errorPage');
        }
    }
}
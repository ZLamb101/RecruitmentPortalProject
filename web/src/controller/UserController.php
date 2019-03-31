<?php

namespace bjz\portal\controller;
use bjz\portal\model\UserModel;
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
        //unset($_SESSION['loginStatus']);
        session_unset();
        $this->redirect("home");
    }

 

    /**
    * Manages a request to register a user, checks if the username already exists in the database.
    */
    public function validateUsernameAction()
    {
        $username = $_GET["q"];
        try {
            $a = new UserModel();
            echo $a->findName($username);
        } catch (\Exception $e) {
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
            //Currently just saves the UserID however will be modified to save more data later to make searching easier
            $_SESSION["UserID"] = $userID;

            if($userType == 1) {
                $this->redirect("candidateHomePage");
            } else {
                $this->redirect("employerHomePage");
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $_SESSION["loginStatus"] = 0;
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
        } catch (\Exception $e) {
            $this->redirect('error');
        }
        $account->setPhoneNumber($_POST['phone-number']);
        $account->setEmail($_POST['email']);

        try {
            $account->save();
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
            $this->redirect('error');
        }
        $account->setUsername($_POST['username']);
        $account->setEmail($_POST['email']);
        $account->setPassword($_POST['password']);
        $account->setPhoneNumber($_POST['phone-number']);
       
        try {
            $account->save();
        } catch (\Exception $e) {
            $this->redirect('errorPage');
        }
        //To complete
        //Generic to both users
    }
}
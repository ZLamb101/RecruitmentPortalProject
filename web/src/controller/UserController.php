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
        unset($_SESSION['loginStatus']);
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
            $this->redirect('error');
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
            error_log("BELOW");
            $_SESSION["loginStatus"] = 0;
            $this->redirect("home");
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
        $account->setName($_POST['name']);
        $account->setUsername($_POST['username']);
        $account->setEmail($_POST['email']);
        $account->setPassword($_POST['password']);
       
        return $account;
        //To complete
        //Generic to both users
    }
}
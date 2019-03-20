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
        //To implement
    }

    /**
     * Action to validate a username
     */
    public function validateUsernameAction()
    {
        //To implement
    }

    /**
     * Action to log in
     */
    public function loginAction()
    {
        try{
            $user = new UserModel();
            $user->validateLogin($_POST['username_input'], $_POST['password_input']);
            $_SESSION["loginStatus"] = 1;   //need to discern when it is an employer or candidate
            //$_SESSION["id"] = $user->getId();

            $this->redirect("candidateHomePage");
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
        //To complete
        //Generic to both users
    }
}
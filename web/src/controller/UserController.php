<?php

namespace bjz\portal\controller;


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
    * Manages a request to register a user, checks if the username already exists in the database.
    */
    public function validateUsernameAction()
    {
        $username = $_GET["q"];
        try {
            $a = new UserModel();
           // echo $a->findName($username);
        } catch (\Exception $e) {
            $this->redirect('error');
        }
    }

    /**
     * Action to log in
     */
    public function loginAction()
    {
        //To complete
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
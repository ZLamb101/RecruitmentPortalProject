<?php
/**
 * Created by PhpStorm.
 * User: 1burg
 * Date: 3/13/2019
 * Time: 10:59 PM
 */

namespace bjz\portal\controller;
use bjz\portal\view\View;

/**
 * Class CandidateController
 *
 * Class controls all actions relevant to the Candidate Model
 *
 * @package bjz\portal\controller
 */
class CandidateController extends UserController
{
    /**
     * Action to load the candidateHomePage
     */
    public function indexAction()
    {
        $view = new View('candidateHomePage');
        echo $view->render();
    }

    /**
     * Function to create a Candidate account
     */
    public function createAccountAction()
    {
        //To complete
        //Call super first
    }
}
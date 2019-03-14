<?php
/**
 * Created by PhpStorm.
 * User: 1burg
 * Date: 3/13/2019
 * Time: 10:59 PM
 */

namespace bjz\portal\controller;


/**
 * Class EmployerController
 *
 * Class controls all actions relevant to the Employer Model
 *
 * @package bjz\portal\controller
 */
class EmployerController extends UserController
{
    /**
     * Action to load the employerHomePage
     */
    public function indexAction()
    {
        $view = new View('employerHomePage');
        echo $view->render();
    }

    /**
     * Action to create an Employer account
     */
    public function createAccountAction()
    {
        //To complete
        //Call super
    }
}
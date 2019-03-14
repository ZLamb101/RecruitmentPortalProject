<?php
/**
 * Created by PhpStorm.
 * User: 1burg
 * Date: 3/13/2019
 * Time: 10:58 PM
 */

namespace bjz\portal\controller;
use bjz\portal\model\Model;
use bjz\portal\view\View;



class HomeController extends Controller
{

    public function indexAction()
    {
        try {
            new Model();
        } catch (\Exception $e){
            $e->getMessage();
        }
        $view = new View('frontPage');
        echo $view->render();
    }

    public function preRegisterPageAction()
    {
        $view = new View('preRegisterPage');
        echo $view->render();
    }

    public function employerRegisterPageAction()
    {
        $view = new View('employerRegisterPage');
        echo $view->render();
    }

    public function candidateRegisterPageAction()
    {
        $view = new View('candidateRegisterPage');
        echo $view->render();
    }

    public function registrationConfirmationPageAction()
    {
        $view = new View('registrationConfirmationPage');
        echo $view->render();
    }

    public function passwordRecoveryPageAction()
    {
        $view = new View('passwordRecoveryPage');
        echo $view->render();
    }

    public function passwordRecoveryConfirmationPageAction()
    {
        $view = new View('passwordRecoveryConfirmationPage');
        echo $view->render();
    }

    public function errorPageAction()
    {
        $view = new View('errorPage');
        echo $view->render();
    }
}
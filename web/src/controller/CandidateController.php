<?php
/**
 * Created by PhpStorm.
 * User: 1burg
 * Date: 3/13/2019
 * Time: 10:59 PM
 */

namespace bjz\portal\controller;


class CandidateController extends Controller
{
    public function indexAction()
    {
        $view = new View('candidateHomePage');
        echo $view->render();
    }

    public function createAccountAction()
    {
        //To complete
    }

    public function loginAction()
    {
        //To complete
    }
}
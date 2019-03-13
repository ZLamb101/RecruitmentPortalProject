<?php
/**
 * Created by PhpStorm.
 * User: 1burg
 * Date: 3/13/2019
 * Time: 10:59 PM
 */

namespace bjz\portal\controller;


class EmployerController extends Controller
{
    public function employerHomePageAction()
    {
        $view = new View('employerHomePage');
        echo $view->render();
    }
}
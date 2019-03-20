<?php
use PHPRouter\RouteCollection;
use PHPRouter\Router;
use PHPRouter\Route;

$collection = new RouteCollection();

// example of using a redirect to another route
$collection->attachRoute(
    new Route(
        '/',
        array(
            '_controller' => 'bjz\portal\controller\HomeController::indexAction',
            'methods' => 'GET',
            'name' => 'home'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/Employer-Home',
        array(
            '_controller' => 'bjz\portal\controller\EmployerController::indexAction',
            'methods' => 'GET',
            'name' => 'employerHomePage'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/Candidate-Home',
        array(
            '_controller' => 'bjz\portal\controller\CandidateController::indexAction',
            'methods' => 'GET',
            'name' => 'candidateHomePage'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/Search',
        array(
            '_controller' => 'bjz\portal\controller\SearchController::indexAction',
            'methods' => 'GET',
            'name' => 'searchPage'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/Pre-Register',
        array(
            '_controller' => 'bjz\portal\controller\HomeController::preRegisterPageAction',
            'methods' => 'GET',
            'name' => 'preRegisterPage'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/Employer-Register',
        array(
            '_controller' => 'bjz\portal\controller\HomeController::employerRegisterPageAction',
            'methods' => 'GET',
            'name' => 'employerRegisterPage'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/Candidate-Register',
        array(
            '_controller' => 'bjz\portal\controller\HomeController::candidateRegisterPageAction',
            'methods' => 'GET',
            'name' => 'candidateRegisterPage'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/Registration-Confirmed',
        array(
            '_controller' => 'bjz\portal\controller\HomeController::registrationConfirmationPageAction',
            'methods' => 'GET',
            'name' => 'registrationConfirmationPage'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/Password-Recovery',
        array(
            '_controller' => 'bjz\portal\controller\HomeController::passwordRecoveryPageAction',
            'methods' => 'GET',
            'name' => 'passwordRecoveryPage'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/Password-Recovery-Confirmation',
        array(
            '_controller' => 'bjz\portal\controller\HomeController::passwordRecoveryConfirmationPageAction',
            'methods' => 'GET',
            'name' => 'passwordRecoveryConfirmationPage'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/Error',
        array(
            '_controller' => 'bjz\portal\controller\HomeController::errorPageAction',
            'methods' => 'GET',
            'name' => 'errorPage'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/login-submit',
        array(
            '_controller' => 'bjz\portal\controller\UserController::loginAction',
            'methods' => 'POST',
            'name' => 'loginSubmit'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/logout',
        array(
            '_controller' => 'bjz\portal\controller\UserController::logoutAction',
            'methods' => 'POST',
            'name' => 'logout'
        )
    )
);

$router = new Router($collection);
$router->setBasePath('/');
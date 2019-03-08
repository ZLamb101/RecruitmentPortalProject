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
            '_controller' => 'bjz\portal\controller\AccountController::indexAction',
            'methods' => 'GET',
            'name' => 'home'
        )
    )
);




$router = new Router($collection);
$router->setBasePath('/');

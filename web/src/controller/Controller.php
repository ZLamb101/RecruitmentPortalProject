<?php

namespace bjz\portal\controller;

/**
 * Class Controller
 * Base class to all Controller classes which implements functions
 * utilized by each of them
 */
class Controller
{
    /**
     * A constant value for an Employer status allowing for quick equivalency checking and good readability
     */
    const EMPLOYER = 2;
    /**
     * A constant value for an Candidate status allowing for quick equivalency checking and good readability
     */
    const CANDIDATE = 1;
    /**
     * A constant value for an Guest status allowing for quick equivalency checking and good readability
     */
    const GUEST = 0;


    /**
     * Redirect to another route
     *
     * @param $route
     * @param array $params
     */
    public function redirect($route, $params = [])
    {
        // Generate a redirect url for a given named route
        $url = $GLOBALS['router']->generate($route, $params);
        header("Location: $url");
    }
}

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
        '/Candidate-Registration',
        array(
            '_controller' => 'bjz\portal\controller\CandidateController::createAccountAction',
            'methods' => 'POST',
            'name' => 'submitCandidateRegistration'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/Employer-Registration',
        array(
            '_controller' => 'bjz\portal\controller\EmployerController::createAccountAction',
            'methods' => 'POST',
            'name' => 'submitEmployerRegistration'
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
        '/Login-Submit',
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
            'methods' => 'GET',
            'name' => 'logout'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/registrationValidation.php',
        array(
            '_controller' => 'bjz\portal\controller\UserController::validateUsernameAction',
            'methods' => 'GET',
            'name' => 'registration'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/edit-candidate-information',
        array(
            '_controller' => 'bjz\portal\controller\CandidateController::editInfoPageAction',
            'methods' => 'GET',
            'name' => 'editCandidateInfoPage'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/edit-employer-information',
        array(
            '_controller' => 'bjz\portal\controller\EmployerController::editInfoPageAction',
            'methods' => 'GET',
            'name' => 'editEmployerInfoPage'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/populateSubFields.php',
        array(
            '_controller' => 'bjz\portal\controller\SearchController::updateSubFieldsAction',
            'methods' => 'GET',
            'name' => 'subfields'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/populateFields.php',
        array(
            '_controller' => 'bjz\portal\controller\SearchController::updateFieldsAction',
            'methods' => 'GET',
            'name' => 'fields'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/populateLevels.php',
        array(
        '_controller' => 'bjz\portal\controller\CandidateController::updateLevelsAction',
            'methods' => 'GET',
            'name' => 'levels'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/populateTypes.php',
        array(
            '_controller' => 'bjz\portal\controller\CandidateController::updateTypesAction',
            'methods' => 'GET',
            'name' => 'types'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/livesearch.php',
        array(
            '_controller' => 'bjz\portal\controller\SearchController::liveSearchAction',
            'methods' => 'GET',
            'name' => 'liveSearch'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/addToShortList.php',
        array(
            '_controller' => 'bjz\portal\controller\ShortListController::addToShortListAction',
            'methods' => 'GET',
            'name' => 'addToShortList'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/deleteShortList.php',
        array(
            '_controller' => 'bjz\portal\controller\ShortListController::deleteShortListAction',
            'methods' => 'GET',
            'name' => 'deleteShortList'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/Update-Employer-Data',
        array(
            '_controller' => 'bjz\portal\controller\EmployerController::updateAccountAction',
            'methods' => 'POST',
            'name' => 'updateEmployerData'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/Update-Candidate-Data',
        array(
            '_controller' => 'bjz\portal\controller\CandidateController::updateAccountAction',
            'methods' => 'POST',
            'name' => 'updateCandidateData'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/Password-Recovery',
        array(
            '_controller' => 'bjz\portal\controller\UserController::passwordRecoveryAction',
            'methods' => 'POST',
            'name' => 'passwordRecovery'
        )
    )
);


$collection->attachRoute(
    new Route(
        '/Send-Invite',
        array(
            '_controller' => 'bjz\portal\controller\CandidateController::sendInviteAction',
            'methods' => 'POST',
            'name' => 'sendInvite'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/deleteWorkExperience.php',
        array(
            '_controller' => 'bjz\portal\controller\CandidateController::deleteWorkExperienceAction',
            'methods' => 'GET',
            'name' => 'deleteWorkExperience'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/deleteSkill.php',
        array(
            '_controller' => 'bjz\portal\controller\CandidateController::deleteSkillAction',
            'methods' => 'GET',
            'name' => 'deleteSkill'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/deleteQualification.php',
        array(
            '_controller' => 'bjz\portal\controller\CandidateController::deleteQualificationAction',
            'methods' => 'GET',
            'name' => 'deleteQualification'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/renameShortList.php',
        array(
            '_controller' => 'bjz\portal\controller\ShortListController::renameShortListAction',
            'methods' => 'GET',
            'name' => 'renameShortList'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/deleteFromShortList.php',
        array(
            '_controller' => 'bjz\portal\controller\ShortListController::deleteFromShortListAction',
            'methods' => 'GET',
            'name' => 'deleteFromShortList'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/displayShortList.php',
        array(
            '_controller' => 'bjz\portal\controller\ShortListController::displayShortListAction',
            'methods' => 'GET',
            'name' => 'displayShortList'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/newShortList.php',
        array(
            '_controller' => 'bjz\portal\controller\ShortListController::newShortListAction',
            'methods' => 'GET',
            'name' => 'newShortList'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/sendInvites.php',
        array(
            '_controller' => 'bjz\portal\controller\ShortListController::sendInviteAllAction',
            'methods' => 'GET',
            'name' => 'sendInviteAll'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/changeDescriptionShortList.php',
        array(
            '_controller' => 'bjz\portal\controller\ShortListController::changeDescriptionAction',
            'methods' => 'GET',
            'name' => 'changeDescription'
        )
    )
);

$router = new Router($collection);
$router->setBasePath('/');
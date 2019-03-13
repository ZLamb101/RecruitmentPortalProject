<?php
namespace bjz\portal\model;

use mysqli;

/**
 * Class Model
 *
 * Base class for all Models, establishes link to the database.
 *
 */
class Model
{
    /**
     * @var mysqli, the database used by the application
     */
    protected $db;
    /**
     * @var string, the host of the database
     */
    const DB_HOST = 'mysql';
    /**
     * @var string, the username of the mysql admin account
     */
    const DB_USER = 'ebdb';
    /**
     * @var string, the password of the mysql admin account
     */
    const DB_PASS = '_k?,<!eXMUvRmk3,';
    /**
     * @var string, the name of the database
     */
    const DB_NAME = 'bjzPortal12';

    /**
     * Model constructor.
     *
     * Initializes the database.
     *
     * // NOT IMPLEMENTED: Generates the sample database if none exists.
     *
     * @throws \Exception if the database cannot connect
     **/
    public function __construct()
    {
        $this->db = new mysqli(
            Model::DB_HOST,
            Model::DB_USER,
            Model::DB_PASS,
            Model::DB_NAME
        );

        if (!$this->db) {
            throw new \Exception($this->db->connect_error, $this->db->connect_errno);
        }

        generateDummyData();
    }

    public function generateDummyData(){
        //TODO Write dummy data and creation
    }
}

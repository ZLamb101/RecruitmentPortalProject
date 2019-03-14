<?php
namespace bjz\portal\model;

use mysqli;

/**
 * Class Model
 *
 * Base class for all Models, establishes link to the database.
 *
 * @package bjz/portal
 **/
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
    const DB_USER = 'root';
    /**
     * @var string, the password of the mysql admin account
     */
    const DB_PASS = 'root';
    /**
     * @var string, the name of the database
     */
    const DB_NAME = 'bjzPortal';


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
            Model::DB_PASS
            // Model::DB_NAME
        );

        if (!$this->db) {
            throw new \Exception($this->db->connect_error, $this->db->connect_errno);
        }


        $this->db->query("CREATE DATABASE IF NOT EXISTS " . Model::DB_NAME . ";");
        if (!$this->db->select_db(Model::DB_NAME)) {
            error_log("Mysql database not available!", 0);
            throw new \mysqli_sql_exception();
        }

        //generateDummyData();
    }

    public function generateDummyData(){
        //TODO Write dummy data and creation

    }
}

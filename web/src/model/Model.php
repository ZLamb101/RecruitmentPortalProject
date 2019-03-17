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
        $this->generateDatabase();
        //generateDummyData();
    }

    public function generateDatabase(){

        // User Table
        $result = $this->db->query("SHOW TABLES LIKE 'user';");
        if ($result->num_rows == 0) {
            // table doesn't exist
            // create it and populate with sample data

            $result = $this->db->query(
                "CREATE TABLE `user` (
                                          `id` int(8) unsigned NOT NULL AUTO_INCREMENT UNIQUE,
                                          `username` varchar(256) NOT NULL,
                                          `password` varchar(256) NOT NULL,
                                          `email` varchar(256) DEFAULT NULL,
                                          `phone_number` varchar (256) NOT NULL,
                                          PRIMARY KEY (`id`) 
                                          );");

            if (!$result) {
                throw new \mysqli_sql_exception($this->db->error, $this->db->errno);
                error_log("Failed creating table: user", 0);
            }
        }

        $result = $this->db->query("SHOW TABLES LIKE 'employer';");
        if ($result->num_rows == 0) {
            // table doesn't exist
            // create it and populate with sample data
            $result = $this->db->query(
                "CREATE TABLE `employer` (
                                                `id` int(8) unsigned NOT NULL AUTO_INCREMENT UNIQUE,
                                                `user_id` int (8) unsigned NOT NULL,
                                                `address` varchar(256) DEFAULT NULL,
                                                `company_name` varchar(256) DEFAULT NULL,
                                                `contact_name` varchar(256) DEFAULT NULL,
                                                `url` varchar(256) DEFAULT NULL,
                                                PRIMARY KEY (`id`),
                                                FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
                                                );");

            if (!$result) {
                throw new \mysqli_sql_exception($this->db->error, $this->db->errno);
                error_log("Failed creating table: employer", 0);
            }
        }

        $result = $this->db->query("SHOW TABLES LIKE 'candidate';");
        if ($result->num_rows == 0) {
            // table doesn't exist
            // create it and populate with sample data
            $result = $this->db->query(
                "CREATE TABLE `candidate` (
                                                `id` int(8) unsigned NOT NULL AUTO_INCREMENT UNIQUE,
                                                `user_id` int (8) unsigned NOT NULL,
                                                `f_name` varchar(256) DEFAULT NULL,
                                                `g_name` varchar(256) DEFAULT NULL,
                                                `location` varchar(256) DEFAULT NULL,
                                                `availability` varchar(256) DEFAULT NULL,
                                                `skills` varchar(256) DEFAULT NULL,
                                                PRIMARY KEY (`id`),
                                                FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
                                                );");

            if (!$result) {
                throw new \mysqli_sql_exception($this->db->error, $this->db->errno);
                error_log("Failed creating table: candidate", 0);
            }
        }

        $result = $this->db->query("SHOW TABLES LIKE 'qualification';");
        if ($result->num_rows == 0) {
            // table doesn't exist
            // create it and populate with sample data
            $result = $this->db->query(
                "CREATE TABLE `qualification` (
                                                    `id` int(8) unsigned NOT NULL AUTO_INCREMENT UNIQUE,
                                                    `owner_id` int (8) unsigned NOT NULL,
                                                    `name` varchar(256) DEFAULT NULL,
                                                    `year` varchar(256) DEFAULT NULL,
                                                    PRIMARY KEY (`id`),
                                                    FOREIGN KEY (`owner_id`) REFERENCES `candidate`(`id`)
                                                    );");

            if (!$result) {
                throw new \mysqli_sql_exception($this->db->error, $this->db->errno);
                error_log("Failed creating table: qualification", 0);
            }
        }
        // W.E

        $result = $this->db->query("SHOW TABLES LIKE 'work_experience';");
        if ($result->num_rows == 0) {
            // table doesn't exist
            // create it and populate with sample data
            $result = $this->db->query(
                "CREATE TABLE `work_experience` (
                                                    `id` int(8) unsigned NOT NULL AUTO_INCREMENT UNIQUE,
                                                    `owner_id` int (8) unsigned NOT NULL,
                                                    `role` varchar(256) DEFAULT NULL,
                                                    `employer` varchar(256) DEFAULT NULL,
                                                    `duration` varchar(256) DEFAULT NULL,
                                                    PRIMARY KEY (`id`),
                                                    FOREIGN KEY (`owner_id`) REFERENCES `candidate`(`id`)
                                                    );");

            if (!$result) {
                throw new \mysqli_sql_exception($this->db->error, $this->db->errno);
                error_log("Failed creating table: work_experience", 0);
            }
        }

        // SL

        $result = $this->db->query("SHOW TABLES LIKE 'short_list';");
        if ($result->num_rows == 0) {
            // table doesn't exist
            // create it and populate with sample data
            $result = $this->db->query(
                "CREATE TABLE `short_list` (
                                                    `id` int(8) unsigned NOT NULL AUTO_INCREMENT UNIQUE,
                                                    `owner_id` int (8) unsigned NOT NULL,
                                                    `name` varchar(256) DEFAULT NULL,
                                                    PRIMARY KEY (`id`),
                                                    FOREIGN KEY (`owner_id`) REFERENCES `employer`(`id`)
                                                    );");

            if (!$result) {
                throw new \mysqli_sql_exception($this->db->error, $this->db->errno);
                error_log("Failed creating table: short_list", 0);
            }
        }

    }

    public function generateDummyData(){
        //TODO Write dummy data and creation

    }
}

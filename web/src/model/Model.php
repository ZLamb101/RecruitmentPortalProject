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
                throw new \mysqli_sql_exception("Failed to create user table");
            }

            $pw1 = password_hash("Jdrumm12", PASSWORD_BCRYPT);
            $pw2 = password_hash("Bupton12", PASSWORD_BCRYPT);
            $pw3 = password_hash("Zlamb987", PASSWORD_BCRYPT);
            $pw4 = password_hash("Tharris11", PASSWORD_BCRYPT);
            if(!$this->db->query("INSERT INTO `user` (`id`, `username`, `password`, `email`, `phone_number`) VALUES
                                                    (1, 'jdrumm', '$pw1', 'jordan.b.drumm@gmail.com', '0210220342'),
                                                    (2, 'bupton', '$pw2', 'bupton@hotmail.co.nz', '0220413672'),
                                                    (3, 'zlamb', '$pw3', 'zanelamb@live.com', '0274929473'),
                                                    (4, 'tharris', '$pw4', 'tim@gmail.com', '0210867283');")){
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy user data.", $this->db->errno);
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
                throw new \mysqli_sql_exception("Failed to create employer table");
            }

            if(!$this->db->query("INSERT INTO `employer` (`id`, `user_id`, `address`, `company_name`, `contact_name`, `url`) VALUES 
                                                    (1, '4', '227 Dairy Flat Hwy, Albany', 'Vesta Central', 'Tim Harris', 'vesta-central.com');")){
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy employer data.", $this->db->errno);
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
                throw new \mysqli_sql_exception("Failed to create candidate table");
            }

            if(!$this->db->query("INSERT INTO `candidate` (`id`, `user_id`, `f_name`, `g_name`, `location`, `availability`, `skills`) VALUES 
                                                    ('1', '1', 'Jordan', 'Drumm', 'Torbay', 'Full-time', 'Haskell, Prolog'), 
                                                    ('2', '2', 'Benjamin', 'Upton', 'Glenfield', 'Part-time', 'HTML, CSS, JS'),
                                                    ('3', '3', 'Zane', 'Lamb', 'Albany', 'Part-time', 'N/A');")){
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy candidate data.", $this->db->errno);
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
                throw new \mysqli_sql_exception("Failed to create qualification table");
            }

            if(!$this->db->query("INSERT INTO `qualification` (`id`, `owner_id`, `name`, `year`) VALUES 
                                                    (NULL, '1', 'Computer Science', '2019'),
                                                    (NULL, '1', 'Business', '2024'),
                                                    (NULL, '2', 'Health Science', '1804'),
                                                    (NULL, '3', 'Arts', '2020');")){
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy qualification data.", $this->db->errno);
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
                throw new \mysqli_sql_exception("Failed to create work_experience table");
            }

            if(!$this->db->query("INSERT INTO `work_experience` (`id`, `owner_id`, `role`, `employer`, `duration`) VALUES 
                                                    (NULL, '1', 'Senior Developer', 'Google', '16'), 
                                                    (NULL, '1', 'Junior Developer', 'TradeMe', '6'),
                                                    (NULL, '2', 'Garbage Man', 'E-Waste', '15'),
                                                    (NULL, '3', 'Physio', 'Torbay Physio', '8'),
                                                    (NULL, '3', 'Stock Broker', 'Easy Cash', '12');")){
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy work_experience data.", $this->db->errno);
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
                                                    `candidates` varchar(800) DEFAULT NULL,
                                                    PRIMARY KEY (`id`),
                                                    FOREIGN KEY (`owner_id`) REFERENCES `employer`(`id`)
                                                    );");

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create short_list table");
            }

            if(!$this->db->query("INSERT INTO `short_list` (`id`, `owner_id`, `name`, `candidates`) VALUES 
                                                    (NULL, '1', 'Junior Developer', '1,2,3');")){
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy short_list data.", $this->db->errno);
            }
        }

    }
}

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

    public function generateUserTable()
    {
        include 'userDummyData.php';
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
                                          );"
            );

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create user table");
            }

            $pw1 = password_hash("Jdrumm12", PASSWORD_BCRYPT);
            $pw2 = password_hash("Bupton12", PASSWORD_BCRYPT);
            $pw3 = password_hash("Zlamb987", PASSWORD_BCRYPT);
            $pw4 = password_hash("Tharris11", PASSWORD_BCRYPT);

            if (!$this->db->query(
                "INSERT INTO `user` (`id`, `username`, `password`, `email`, `phone_number`) VALUES
                                                    (1, 'jdrumm', '$pw1', 'jordan.b.drumm@gmail.com', '0210220342'),
                                                    (2, 'bupton', '$pw2', 'bupton@hotmail.co.nz', '0220413672'),
                                                    (3, 'zlamb', '$pw3', 'zanelamb@live.com', '0274929473'),
                                                    (4, 'tharris', '$pw4', 'tim@gmail.com', '0210867283'), " . $userDummy . ";"
            )
            ) {
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy user data.", $this->db->errno);
            }
        }
    }

    public function generateEmployerTable()
    {
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
                                                `calendar` varchar (256) DEFAULT NULL,
                                                PRIMARY KEY (`id`),
                                                FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
                                                );"
            );

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create employer table");
            }

            if (!$this->db->query(
                "INSERT INTO `employer` (`id`, `user_id`, `address`, `company_name`, `contact_name`, `url`, `calendar`) VALUES 
                                                    (1, '4', 'Albany', 'Vesta Central', 'Tim Harris', 'vesta-central.com', 'https://calendar.google.com/calendar/selfsched?sstoken=UUY1TVNrb0FSMVVCfGRlZmF1bHR8YWIyYTE2MWFlZDk0MjNjOGZiMGY3MjQyNTk0Njk2Yzc');"
            )
            ) {
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy employer data.", $this->db->errno);
            }
        }
    }

    public function generateCandidateTable()
    {
        include 'candidateDummyData.php';
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
                                                `availability` int(8) DEFAULT NULL,
                                                PRIMARY KEY (`id`),
                                                FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
                                                );"
            );

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create candidate table");
            }

            if (!$this->db->query(
                "INSERT INTO `candidate` (`id`, `user_id`, `f_name`, `g_name`, `location`, `availability`) VALUES 
                                                    ('1', '1', 'Drumm', 'Jordan', 'Torbay', '1'), 
                                                    ('2', '2', 'Upton', 'Benjamin', 'Glenfield','3'),
                                                    ('3', '3', 'Lamb', 'Zane', 'Albany', '7')," . $candidateDummy . ";"
            )
            ) {
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy candidate data.", $this->db->errno);
            }
        }
    }

    public function generateGuidTable()
    {
        $result = $this->db->query("SHOW TABLES LIKE 'passwordguids';");
        if ($result->num_rows == 0) {
            // table doesn't exist
            // create it and populate with sample data
            $result = $this->db->query(
                "CREATE TABLE `passwordguids` (
                                                `id` int(8) unsigned NOT NULL AUTO_INCREMENT UNIQUE,
                                                `user_id` int (8) unsigned NOT NULL,
                                                `guid` varchar(256) NOT NULL,
                                                `expireTime` DATETIME NOT NULL,
                  
                                                PRIMARY KEY (`id`),
                                                FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
                                                );"
            );

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create passwordguids table");
            }
        }
    }

    public function generateWorkExperienceTable()
    {
        include 'workExpDummyData.php';
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
                                                    );"
            );

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create work_experience table");
            }

            if (!$this->db->query(
                "INSERT INTO `work_experience` (`id`, `owner_id`, `role`, `employer`, `duration`) VALUES 
                                                    (NULL, '1', 'Senior Developer', 'Google', '16'), 
                                                    (NULL, '1', 'Junior Developer', 'TradeMe', '6'),
                                                    (NULL, '2', 'Garbage Man', 'E-Waste', '15'),
                                                    (NULL, '3', 'Physio', 'Torbay Physio', '8'),
                                                    (NULL, '3', 'Stock Broker', 'Easy Cash', '12')," . $workExpDummy . ";"
            )
            ) {
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy work_experience data.", $this->db->errno);
            }
        }
    }

    public function generateShortListTable()
    {
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
                                                    `description` varchar(512) DEFAULT NULL,
                                                    `hasInvited` int(8) NOT NULL,
                                                    PRIMARY KEY (`id`),
                                                    FOREIGN KEY (`owner_id`) REFERENCES `employer`(`id`)
                                                    );"
            );

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create short_list table");
            }

            if (!$this->db->query(
                "INSERT INTO `short_list` (`id`, `owner_id`, `name`, `candidates`, `description`, `hasInvited`) VALUES 
                                                    (NULL, '1', 'Junior Developer', '1,2,3', 'Jr. C# developer',0),
                                                    (NULL, '1', 'Tester', '3,2', 'Ideally undergrad', 0);"
            )
            ) {
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy short_list data.", $this->db->errno);
            }
        }
    }

    public function generateQualTypeTable()
    {
        include 'qualTypeDummyData.php';
        $result = $this->db->query("SHOW TABLES LIKE 'qual_type';");
        if ($result->num_rows == 0) {
            // table doesn't exist
            // create it and populate with sample data
            $result = $this->db->query(
                "CREATE TABLE `qual_type` (
                                                    `id` int(8) unsigned NOT NULL AUTO_INCREMENT UNIQUE,
                                                    `type` varchar(256) DEFAULT NULL,
                                                    PRIMARY KEY (`id`)
                                                    );"
            );

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create qual_type table");
            }

            if (!$this->db->query(
                "INSERT INTO `qual_type` (`id`, `type`) VALUES 
                                                    " . $qualTypeDummy
            )
            ) {
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy qual_type data.", $this->db->errno);
            }
        }
    }

    public function generateQualLevelTable()
    {
        include 'qualLevelDummyData.php';
        $result = $this->db->query("SHOW TABLES LIKE 'qual_level';");
        if ($result->num_rows == 0) {
            // table doesn't exist
            // create it and populate with sample data
            $result = $this->db->query(
                "CREATE TABLE `qual_level` (
                                                    `id` int(8) unsigned NOT NULL AUTO_INCREMENT UNIQUE,
                                                    `level` varchar(256) DEFAULT NULL,
                                                    PRIMARY KEY (`id`)
                                                    );"
            );

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create qual_level table");
            }

            if (!$this->db->query(
                "INSERT INTO `qual_level` (`id`, `level`) VALUES 
                                                    " . $qualLevelDummy
            )
            ) {
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy qual_level data.", $this->db->errno);
            }
        }
    }

    public function generateQualificationsTable()
    {
        include 'qualDummyData.php';
        $result = $this->db->query("SHOW TABLES LIKE 'qualification';");
        if ($result->num_rows == 0) {
            // table doesn't exist
            // create it and populate with sample data
            $result = $this->db->query(
                "CREATE TABLE `qualification` (
                                                    `id` int(8) unsigned NOT NULL AUTO_INCREMENT UNIQUE,
                                                    `owner_id` int (8) unsigned NOT NULL,
                                                    `level_id` int (8) unsigned NOT NULL,
                                                    `type_id` int (8) unsigned NOT NULL,
                                                    `year` varchar(256) DEFAULT NULL,
                                                    `major` varchar(256) DEFAULT NULL,
                                                    PRIMARY KEY (`id`),
                                                    FOREIGN KEY (`owner_id`) REFERENCES `candidate`(`id`),
                                                    FOREIGN KEY (`level_id`) REFERENCES `qual_level`(`id`),
                                                    FOREIGN KEY (`type_id`) REFERENCES `qual_type`(`id`)
                                                    );"
            );

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create qualification table");
            }

            if (!$this->db->query(
                "INSERT INTO `qualification` (`id`, `owner_id`, `level_id`,`type_id`, `year`, `major`) VALUES 
                                                    (NULL, '1', '3','4', '2019', 'Computer Science'),
                                                    (NULL, '1', '2','6', '2024', 'Healthy Eating'),
                                                    (NULL, '2', '1','3', '1804', 'Jump Jam'),
                                                    (NULL, '3', '2','1', '2020', 'Exercise Physiology')," . $qualDummy . ";"
            )
            ) {
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy qualification data.", $this->db->errno);
            }
        }
    }

    public function generateFieldTable()
    {
        include 'fieldDummyData.php';
        //Field Table
        $result = $this->db->query("SHOW TABLES LIKE 'field';");
        if ($result->num_rows == 0) {
            // table doesn't exist
            // create it and populate with sample data
            $result = $this->db->query(
                "CREATE TABLE `field` (
                                                    `id` int(8) unsigned NOT NULL AUTO_INCREMENT UNIQUE,
                                                    `field` varchar(256) DEFAULT NULL,
                                                    PRIMARY KEY (`id`)
                                                    );"
            );

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create field table");
            }

            if (!$this->db->query(
                "INSERT INTO `field` (`id`, `field`) VALUES 
                                                    " . $fieldDummy
            )
            ) {
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy field data.", $this->db->errno);
            }
        }
    }

    public function generateSubFieldTable()
    {
        include 'subFieldDummyData.php';
        //Sub Field Table
        $result = $this->db->query("SHOW TABLES LIKE 'sub_field';");
        if ($result->num_rows == 0) {
            // table doesn't exist
            // create it and populate with sample data
            $result = $this->db->query(
                "CREATE TABLE `sub_field` (
                                                    `id` int(8) unsigned NOT NULL AUTO_INCREMENT UNIQUE,
                                                    `field_id` int (8) unsigned NOT NULL,
                                                    `sub_field` varchar(256) DEFAULT NULL,
                                                    PRIMARY KEY (`id`),
                                                    FOREIGN KEY (`field_id`) REFERENCES `field`(`id`)
                                                    );"
            );

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create sub_field table");
            }

            if (!$this->db->query(
                "INSERT INTO `sub_field` (`id`, `field_id`, `sub_field`) VALUES
                                                    " . $subFieldDummy
            )
            ) {
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy sub_field data.", $this->db->errno);
            }
        }
    }

    public function generateSkillTable()
    {
        include 'skillDummyData.php';
        //Skill Table
        $result = $this->db->query("SHOW TABLES LIKE 'skill';");
        if ($result->num_rows == 0) {
            // table doesn't exist
            // create it and populate with sample data
            $result = $this->db->query(
                "CREATE TABLE `skill` (
                                                    `id` int(8) unsigned NOT NULL AUTO_INCREMENT UNIQUE,
                                                    `owner_id` int (8) unsigned NOT NULL,
                                                    `field_id` int (8) unsigned NOT NULL,
                                                    `sub_field_id` int (8) unsigned NOT NULL, 
                                                    `contents` varchar(256) DEFAULT NULL,
                                                    PRIMARY KEY (`id`),
                                                    FOREIGN KEY (`owner_id`) REFERENCES `candidate`(`id`),
                                                    FOREIGN KEY (`field_id`) REFERENCES `field`(`id`),
                                                    FOREIGN KEY (`sub_field_id`) REFERENCES `sub_field`(`id`)
                                                    );"
            );

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create skill table");
            }

            if (!$this->db->query(
                "INSERT INTO `skill` (`id`, `owner_id`, `field_id`, `sub_field_id`, `contents`) VALUES
                                                    (NULL, 1, 1, 1, 'Counting money'),
                                                    (NULL, 1, 1, 2, 'Doing the books'),
                                                    (NULL, 2, 1, 3, 'Being Cool'),
                                                    (NULL, 3, 1, 4, 'Being lame')," . $skillDummy . "
                                                    ;"
            )
            ) {
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy skill data.", $this->db->errno);
            }
        }
    }

    public function generatePreferencesTable()
    {
        include 'preferencesDummyData.php';
        //Preferences Table
        $result = $this->db->query("SHOW TABLES LIKE 'preferences';");
        if ($result->num_rows == 0) {
            // table doesn't exist
            // create it and populate with sample data
            $result = $this->db->query(
                "CREATE TABLE `preferences` (
                                                    `id` int(8) unsigned NOT NULL AUTO_INCREMENT UNIQUE,
                                                    `owner_id` int (8) unsigned NOT NULL,
                                                    `preferred_qual_id` int (8) unsigned NOT NULL,
                                                    `preferred_workEx_id` int (8) unsigned NOT NULL,
                                                    `preferred_skill_id` int (8) unsigned NOT NULL,
                                                    PRIMARY KEY (`id`),
                                                    FOREIGN KEY (`owner_id`) REFERENCES `candidate`(`id`),
                                                    FOREIGN KEY (`preferred_qual_id`) REFERENCES `qualification`(`id`),
                                                    FOREIGN KEY (`preferred_workEx_id`) REFERENCES `work_experience`(`id`),
                                                    FOREIGN KEY (`preferred_skill_id`) REFERENCES `skill`(`id`)
                                                    );"
            );

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create preference table");
            }

            if (!$this->db->query(
                "INSERT INTO `preferences` (`id`, `owner_id`, `preferred_qual_id`, `preferred_workEx_id`, `preferred_skill_id`) VALUES
                                                    (NULL, 1, 1, 1, 1),
                                                    (NULL, 2, 3, 3, 3),
                                                    (NULL, 3, 4, 4, 4), " . $prefDummy . "
                                                    ;"
            )
            ) {
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy preference data.", $this->db->errno);
            }
        }
    }

    /**
     * Generates the database and inserts dummy data.
     */
    public function generateDatabase()
    {
        $this->generateUserTable();
        $this->generateEmployerTable();
        $this->generateCandidateTable();
        $this->generateGuidTable();
        $this->generateWorkExperienceTable();
        $this->generateShortListTable();
        $this->generateQualTypeTable();
        $this->generateQualLevelTable();
        $this->generateQualificationsTable();
        $this->generateFieldTable();
        $this->generateSubFieldTable();
        $this->generateSkillTable();
        $this->generatePreferencesTable();
    }
}

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

    /**
     * Generates the database and inserts dummy data.
     */
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
            include 'userDummyData.php';
            if(!$this->db->query("INSERT INTO `user` (`id`, `username`, `password`, `email`, `phone_number`) VALUES
                                                    (1, 'jdrumm', '$pw1', 'jordan.b.drumm@gmail.com', '0210220342'),
                                                    (2, 'bupton', '$pw2', 'bupton@hotmail.co.nz', '0220413672'),
                                                    (3, 'zlamb', '$pw3', 'zanelamb@live.com', '0274929473'),
                                                    (4, 'tharris', '$pw4', 'tim@gmail.com', '0210867283'), ". $userDummy .";")){
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
                                                `calendar` varchar (256) DEFAULT NULL,
                                                PRIMARY KEY (`id`),
                                                FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
                                                );");

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create employer table");
            }

            if(!$this->db->query("INSERT INTO `employer` (`id`, `user_id`, `address`, `company_name`, `contact_name`, `url`, `calendar`) VALUES 
                                                    (1, '4', 'Albany', 'Vesta Central', 'Tim Harris', 'vesta-central.com', 'https://calendar.google.com/calendar/selfsched?sstoken=UUY1TVNrb0FSMVVCfGRlZmF1bHR8YWIyYTE2MWFlZDk0MjNjOGZiMGY3MjQyNTk0Njk2Yzc');")){
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
                                                `availability` int(8) DEFAULT NULL,
                                                PRIMARY KEY (`id`),
                                                FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
                                                );");

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create candidate table");
            }
            include 'candidateDummyData.php';
            if(!$this->db->query("INSERT INTO `candidate` (`id`, `user_id`, `f_name`, `g_name`, `location`, `availability`) VALUES 
                                                    ('1', '1', 'Drumm', 'Jordan', 'Torbay', '1'), 
                                                    ('2', '2', 'Upton', 'Benjamin', 'Glenfield','3'),
                                                    ('3', '3', 'Lamb', 'Zane', 'Albany', '7')," . $candidateDummy .";")){
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy candidate data.", $this->db->errno);
            }
        }

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
                                                );");

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create passwordguids table");
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
            include 'workExpDummyData.php';
            if(!$this->db->query("INSERT INTO `work_experience` (`id`, `owner_id`, `role`, `employer`, `duration`) VALUES 
                                                    (NULL, '1', 'Senior Developer', 'Google', '16'), 
                                                    (NULL, '1', 'Junior Developer', 'TradeMe', '6'),
                                                    (NULL, '2', 'Garbage Man', 'E-Waste', '15'),
                                                    (NULL, '3', 'Physio', 'Torbay Physio', '8'),
                                                    (NULL, '3', 'Stock Broker', 'Easy Cash', '12')," . $workExpDummy .";")){
                // ," . $workExpDummy ."
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
                                                    `description` varchar(512) DEFAULT NULL,
                                                    `hasInvited` int(8) NOT NULL,
                                                    PRIMARY KEY (`id`),
                                                    FOREIGN KEY (`owner_id`) REFERENCES `employer`(`id`)
                                                    );");

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create short_list table");
            }

            if(!$this->db->query("INSERT INTO `short_list` (`id`, `owner_id`, `name`, `candidates`, `description`, `hasInvited`) VALUES 
                                                    (NULL, '1', 'Junior Developer', '1,2,3', 'Jr. C# developer',0),
                                                    (NULL, '1', 'Tester', '3,2', 'Ideally undergrad', 0);")){
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy short_list data.", $this->db->errno);
            }
        }


        //Qual Type
        $result = $this->db->query("SHOW TABLES LIKE 'qual_type';");
        if ($result->num_rows == 0) {
            // table doesn't exist
            // create it and populate with sample data
            $result = $this->db->query(
                "CREATE TABLE `qual_type` (
                                                    `id` int(8) unsigned NOT NULL AUTO_INCREMENT UNIQUE,
                                                    `type` varchar(256) DEFAULT NULL,
                                                    PRIMARY KEY (`id`)
                                                    );");

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create qual_type table");
            }

            if(!$this->db->query("INSERT INTO `qual_type` (`id`, `type`) VALUES 
                                                    (NULL, 'Accountancy'),
                                                     (NULL, 'AgriCommerce'),
                                                     (NULL, 'Agricultural Science'),
                                                     (NULL, 'Applied Economics'),
                                                     (NULL, 'Arts'),
                                                     (NULL, 'Aviation'), 
                                                     (NULL, 'Aviation Management'), 
                                                     (NULL, 'Business'), 
                                                     (NULL, 'Commercial Music'), 
                                                     (NULL, 'Communication'), 
                                                     (NULL, 'Construction'), 
                                                     (NULL, 'Creative Media Production'), 
                                                     (NULL, 'Education'), 
                                                     (NULL, 'Health Science'), 
                                                     (NULL, 'Horticultural Science'), 
                                                     (NULL, 'Information Sciences'), 
                                                     (NULL, 'Maori Visual Arts'), 
                                                     (NULL, 'Nursing'), 
                                                     (NULL, 'Resource and Environmental Planning'), 
                                                     (NULL, 'Retail and Business Management'), 
                                                     (NULL, 'Science'), 
                                                     (NULL, 'Social Work'), 
                                                     (NULL, 'Sport and Exercise'), 
                                                     (NULL, 'Sport Management'), 
                                                     (NULL, 'Veterinary Science'), 
                                                     (NULL, 'Veterinary Technology');")){
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy qual_type data.", $this->db->errno);
            }
        }

        //Qual Level
        $result = $this->db->query("SHOW TABLES LIKE 'qual_level';");
        if ($result->num_rows == 0) {
            // table doesn't exist
            // create it and populate with sample data
            $result = $this->db->query(
                "CREATE TABLE `qual_level` (
                                                    `id` int(8) unsigned NOT NULL AUTO_INCREMENT UNIQUE,
                                                    `level` varchar(256) DEFAULT NULL,
                                                    PRIMARY KEY (`id`)
                                                    );");

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create qual_level table");
            }

            if(!$this->db->query("INSERT INTO `qual_level` (`id`, `level`) VALUES 
                                                    (NULL, 'Certificate'),
                                                    (NULL, 'Diploma'),
                                                    (NULL, 'Bachelors'),
                                                    (NULL, 'Masters'),
                                                    (NULL, 'Doctorates');")){
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy qual_level data.", $this->db->errno);
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
                                                    `level_id` int (8) unsigned NOT NULL,
                                                    `type_id` int (8) unsigned NOT NULL,
                                                    `year` varchar(256) DEFAULT NULL,
                                                    `major` varchar(256) DEFAULT NULL,
                                                    PRIMARY KEY (`id`),
                                                    FOREIGN KEY (`owner_id`) REFERENCES `candidate`(`id`),
                                                    FOREIGN KEY (`level_id`) REFERENCES `qual_level`(`id`),
                                                    FOREIGN KEY (`type_id`) REFERENCES `qual_type`(`id`)
                                                    );");

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create qualification table");
            }
            include 'qualDummyData.php';
            if(!$this->db->query("INSERT INTO `qualification` (`id`, `owner_id`, `level_id`,`type_id`, `year`, `major`) VALUES 
                                                    (NULL, '1', '3','4', '2019', 'Computer Science'),
                                                    (NULL, '1', '2','6', '2024', 'Healthy Eating'),
                                                    (NULL, '2', '1','3', '1804', 'Jump Jam'),
                                                    (NULL, '3', '2','1', '2020', 'Exercise Physiology')," . $qualDummy . ";")){
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy qualification data.", $this->db->errno);
            }
        }

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
                                                    );");

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create field table");
            }

            if(!$this->db->query("INSERT INTO `field` (`id`, `field`) VALUES 
                                                    (NULL, 'Accounting'),
                                                    (NULL, 'Agriculture, fishing and forestry'),
                                                    (NULL, 'Architecture'),
                                                    (NULL, 'Automotive'),
                                                    (NULL, 'Banking, finance & insurance'),
                                                    (NULL, 'Construction & roading'),
                                                    (NULL, 'Customer service'),
                                                    (NULL, 'Education'),
                                                    (NULL, 'Engineering'),
                                                    (NULL, 'Executive & general management'),
                                                    (NULL, 'Government & council'),
                                                    (NULL, 'Healthcare'),
                                                    (NULL, 'Hospitality & tourism'),
                                                    (NULL, 'HR & recruitment'),
                                                    (NULL, 'IT'),
                                                    (NULL, 'Legal'),
                                                    (NULL, 'Manufacturing & operations'),
                                                    (NULL, 'Marketing, media & communications'),
                                                    (NULL, 'Office & administration'),
                                                    (NULL, 'Property'),
                                                    (NULL, 'Retail'),
                                                    (NULL, 'Sales'),
                                                    (NULL, 'Science & technology'),
                                                    (NULL, 'Trades & services'),
                                                    (NULL, 'Transport & logistics')
                                                    ;")){
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy field data.", $this->db->errno);
            }
        }

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
                                                    );");

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create sub_field table");
            }

            if(!$this->db->query("INSERT INTO `sub_field` (`id`, `field_id`, `sub_field`) VALUES
                                                    (NULL, 1, 'Accountancy'),
                                                    (NULL, 1, 'Accounts administration'),
                                                    (NULL, 1, 'Accounts payable'),
                                                    (NULL, 1, 'Accounts receivable'),
                                                    (NULL, 1, 'Analysts'),
                                                    (NULL, 1, 'Finance management & control'),
                                                    (NULL, 1, 'Management'),
                                                    (NULL, 1, 'Payroll'),
                                                    (NULL, 2, 'Farming'),
                                                    (NULL, 2, 'Fishing'),
                                                    (NULL, 2, 'Forestry'),
                                                    (NULL, 2, 'Horticulture'),
                                                    (NULL, 3, 'Architecture'),
                                                    (NULL, 3, 'Drafting'),
                                                    (NULL, 3, 'Interior design'),
                                                    (NULL, 4, 'Automotive technician'),
                                                    (NULL, 4, 'Diesel mechanic'),
                                                    (NULL, 4, 'Management'),
                                                    (NULL, 4, 'Panel & paint'),
                                                    (NULL, 4, 'Sales, operations & parts'),
                                                    (NULL, 5, 'Analysis'),
                                                    (NULL, 5, 'Client Interaction'),
                                                    (NULL, 5, 'Corporate & institutional banking'),
                                                    (NULL, 5, 'Credit & lending'),
                                                    (NULL, 5, 'Financial planning & investment'),
                                                    (NULL, 5, 'Insurance'),
                                                    (NULL, 5, 'Management'),
                                                    (NULL, 5, 'Risk & compliance'),
                                                    (NULL, 5, 'Settlements'),
                                                    (NULL, 5, 'Clerical skills'),
                                                    (NULL, 6, 'Estimation'),
                                                    (NULL, 6, 'Health & safety'),
                                                    (NULL, 6, 'Labouring'),
                                                    (NULL, 6, 'Machine operation'),
                                                    (NULL, 6, 'Planning'),
                                                    (NULL, 6, 'Project & contracts management'),
                                                    (NULL, 6, 'Quantity surveying'),
                                                    (NULL, 6, 'Traffic management'),
                                                    (NULL, 6, 'Site management'),
                                                    (NULL, 6, 'Supervision'),
                                                    (NULL, 6, 'Surveying'),
                                                    (NULL, 7, 'Call centre'),
                                                    (NULL, 7, 'Customer interaction'),
                                                    (NULL, 7, 'Management'),
                                                    (NULL, 8, 'Nannying'),
                                                    (NULL, 8, 'Early childhood'),
                                                    (NULL, 8, 'Primary'),
                                                    (NULL, 8, 'Secondary'),
                                                    (NULL, 8, 'Tertiary'),
                                                    (NULL, 8, 'Tutoring'),
                                                    (NULL, 8, 'Training'),
                                                    (NULL, 9, 'Building services'),
                                                    (NULL, 9, 'Civil & structural'),
                                                    (NULL, 9, 'Drafting'),
                                                    (NULL, 9, 'Electrical'),
                                                    (NULL, 9, 'Energy'),
                                                    (NULL, 9, 'Environmental'),
                                                    (NULL, 9, 'Geotechnical'),
                                                    (NULL, 9, 'Industrial'),
                                                    (NULL, 9, 'Maintenance'),
                                                    (NULL, 9, 'Management'),
                                                    (NULL, 9, 'Mechanical'),
                                                    (NULL, 9, 'Project management'),
                                                    (NULL, 9, 'Water & waste'),
                                                    (NULL, 10, 'Executive management'),
                                                    (NULL, 10, 'General management'),
                                                    (NULL, 11, 'Central government'),
                                                    (NULL, 11, 'Defence'),
                                                    (NULL, 11, 'Local and regional council'),
                                                    (NULL, 12, 'Administration'),
                                                    (NULL, 12, 'Caregiving'),
                                                    (NULL, 12, 'Community & social services'),
                                                    (NULL, 12, 'Dentistry'),
                                                    (NULL, 12, 'Doctors & specialists'),
                                                    (NULL, 12, 'Fitness & wellbeing'),
                                                    (NULL, 12, 'Management'),
                                                    (NULL, 12, 'Nursing & midwifery'),
                                                    (NULL, 12, 'Occupational therapy'),
                                                    (NULL, 12, 'Pharmacy'),
                                                    (NULL, 12, 'Physiotherapy'),
                                                    (NULL, 12, 'Psychology & counselling'),
                                                    (NULL, 12, 'Radiography & sonography'),
                                                    (NULL, 12, 'Veterinary'),
                                                    (NULL, 13, 'Barkeeping & baristing'),
                                                    (NULL, 13, 'Culinary'),
                                                    (NULL, 13, 'Housekeeping'),
                                                    (NULL, 13, 'Kitchen skills'),
                                                    (NULL, 13, 'Management'),
                                                    (NULL, 13, 'Reception & clerical'),
                                                    (NULL, 13, 'Tourism'),
                                                    (NULL, 13, 'Travel consultancy'),
                                                    (NULL, 13, 'Waiting skills'),
                                                    (NULL, 14, 'Health & safety'),
                                                    (NULL, 14, 'Human Resources'),
                                                    (NULL, 14, 'Recruitment'),
                                                    (NULL, 15, 'Architecture'),
                                                    (NULL, 15, 'Business & system analysis'),
                                                    (NULL, 15, 'Data Warehousing'),
                                                    (NULL, 15, 'Business intelligence'),
                                                    (NULL, 15, 'Database'),
                                                    (NULL, 15, 'Functional consultants'),
                                                    (NULL, 15, 'Management'),
                                                    (NULL, 15, 'Networking & storage'),
                                                    (NULL, 15, 'Programming & development'),
                                                    (NULL, 15, 'Project management'),
                                                    (NULL, 15, 'Sales '),
                                                    (NULL, 15, 'Security'),
                                                    (NULL, 15, 'System engineering'),
                                                    (NULL, 15, 'Telecommunications'),
                                                    (NULL, 15, 'Testing'),
                                                    (NULL, 15, 'Training'),
                                                    (NULL, 15, 'User experience'),
                                                    (NULL, 16, 'In-house counsel'),
                                                    (NULL, 16, 'Private practice'),
                                                    (NULL, 16, 'Secretarial'),
                                                    (NULL, 17, 'Fitting & machining'),
                                                    (NULL, 17, 'Machine operation'),
                                                    (NULL, 17, 'Management'),
                                                    (NULL, 17, 'Process & assembly'),
                                                    (NULL, 17, 'Purchasing & inventory'),
                                                    (NULL, 17, 'Quality assurance'),
                                                    (NULL, 17, 'Warehousing'),
                                                    (NULL, 17, 'Supervision'),
                                                    (NULL, 18, 'Advertising'),
                                                    (NULL, 18, 'Brand & product management'),
                                                    (NULL, 18, 'Communications & PR'),
                                                    (NULL, 18, 'Design'),
                                                    (NULL, 18, 'Digital marketing'),
                                                    (NULL, 18, 'Direct marketing'),
                                                    (NULL, 18, 'Journalism'),
                                                    (NULL, 18, 'Management'),
                                                    (NULL, 18, 'Market research & analysis'),
                                                    (NULL, 19, 'Administration'),
                                                    (NULL, 19, 'Data entry'),
                                                    (NULL, 19, 'EA, PA & secretarial skills'),
                                                    (NULL, 19, 'Office management'),
                                                    (NULL, 19, 'Reception'),
                                                    (NULL, 20, 'Commercial sales & leasing'),
                                                    (NULL, 20, 'Consultancy & valuation'),
                                                    (NULL, 20, 'Facilities & commercial property management'),
                                                    (NULL, 20, 'Residential sales & management'),
                                                    (NULL, 21, 'Buying'),
                                                    (NULL, 21, 'Management'),
                                                    (NULL, 21, 'Merchandising'),
                                                    (NULL, 21, 'Retail assistant'),
                                                    (NULL, 22, 'Account management'),
                                                    (NULL, 22, 'Business development'),
                                                    (NULL, 22, 'Sales management'),
                                                    (NULL, 22, 'Sales'),
                                                    (NULL, 22, 'Telesales'),
                                                    (NULL, 23, 'Research'),
                                                    (NULL, 24, 'Air conditioning & refrigeration'),
                                                    (NULL, 24, 'Beauty therapy'),
                                                    (NULL, 24, 'Boat building'),
                                                    (NULL, 24, 'Building & carpentry'),
                                                    (NULL, 24, 'Butchery'),
                                                    (NULL, 24, 'Baking'),
                                                    (NULL, 24, 'Cleaning'),
                                                    (NULL, 24, 'Electrical'),
                                                    (NULL, 24, 'Flooring'),
                                                    (NULL, 24, 'Gardening & landscaping'),
                                                    (NULL, 24, 'Glazier skills'),
                                                    (NULL, 24, 'Hairdressing'),
                                                    (NULL, 24, 'Labouring'),
                                                    (NULL, 24, 'Painting'),
                                                    (NULL, 24, 'Plumbing'),
                                                    (NULL, 24, 'Printing'),
                                                    (NULL, 24, 'Roofing'),
                                                    (NULL, 24, 'Security'),
                                                    (NULL, 24, 'Sign Writers'),
                                                    (NULL, 24, 'Technicians'),
                                                    (NULL, 24, 'Welding'),
                                                    (NULL, 25, 'Driving & courier skills'),
                                                    (NULL, 25, 'Freighting'),
                                                    (NULL, 25, 'Import & export'),
                                                    (NULL, 25, 'Management'),
                                                    (NULL, 25, 'Operations'),
                                                    (NULL, 25, 'Supply chain & planning'),
                                                    (NULL, 25, 'Warehouse & distribution')                                              
                                                    ;")){
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy sub_field data.", $this->db->errno);
            }
        }

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
                                                    );");

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create skill table");
            }
            include 'skillDummyData.php';
            if (!$this->db->query("INSERT INTO `skill` (`id`, `owner_id`, `field_id`, `sub_field_id`, `contents`) VALUES
                                                    (NULL, 1, 1, 1, 'Counting money'),
                                                    (NULL, 1, 1, 2, 'Doing the books'),
                                                    (NULL, 2, 1, 3, 'Being Cool'),
                                                    (NULL, 3, 1, 4, 'Being lame')," . $skillDummy ."
                                                    ;")) {
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy skill data.", $this->db->errno);
            }
        }

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
                                                    );");

            if (!$result) {
                throw new \mysqli_sql_exception("Failed to create preference table");
            }
            include 'preferencesDummyData.php';
            if (!$this->db->query("INSERT INTO `preferences` (`id`, `owner_id`, `preferred_qual_id`, `preferred_workEx_id`, `preferred_skill_id`) VALUES
                                                    (NULL, 1, 1, 1, 1),
                                                    (NULL, 2, 3, 3, 3),
                                                    (NULL, 3, 4, 4, 4), ". $prefDummy."
                                                    ;")) {
                // handle appropriately
                throw new \mysqli_sql_exception("Failed to create dummy preference data.", $this->db->errno);
            }
        }
    }
}

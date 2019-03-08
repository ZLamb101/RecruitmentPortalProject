<?php
namespace bjz\portal\model;

use mysqli;

/**
 * Class Model
 *
 */
class Model
{
    protected $db;

    // is this the best place for these constants?
    const DB_HOST = 'mysql';
    const DB_USER = 'root';
    const DB_PASS = 'root';
    const DB_NAME = 'portal';

    public function __construct()
    {
        $this->db = new mysqli(
            Model::DB_HOST,
            Model::DB_USER,
            Model::DB_PASS
            //Model::DB_NAME
        );

        if (!$this->db) {
            throw new \Exception($this->db->connect_error, $this->db->connect_errno);
        }

        //----------------------------------------------------------------------------
        // This is to make our life easier
        // Create your database and populate it with sample data
        $this->db->query("CREATE DATABASE IF NOT EXISTS " . Model::DB_NAME . ";");

        if (!$this->db->select_db(Model::DB_NAME)) {
            throw new \mysqli_sql_exception($this->db->error, $this->db->errno);
            error_log("Mysql database not available!", 0);
        }

        $result = $this->db->query("SHOW TABLES LIKE 'account';");

        if ($result->num_rows == 0) {
            // table doesn't exist
            // create it and populate with sample data

        }



        //----------------------------------------------------------------------------
    }
}

<?php

namespace App\Config;

use PDO;
use PDOException;

class Database {
    private $host = 'srv1575.hstgr.io';
    private $db_name = 'u564798502_multiempresa';
    private $username = 'u564798502_root';
    private $password = 'Root93...';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
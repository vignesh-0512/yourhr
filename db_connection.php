<?php
class Database {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "job_portal";
    private $conn;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            error_log("Connection failed: " . $this->conn->connect_error);
            die("Connection failed. Please try again later.");
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>

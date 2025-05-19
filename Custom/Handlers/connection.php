<?php
class Connection {
    private $host = "localhost";  
    private $user = "root";       
    private $pass = "";           
    private $dbname = "enrollment_1-4"; 
    protected $conn;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);

        if ($this->conn->connect_error) {
            die(json_encode(["error" => "Database connection failed: " . $this->conn->connect_error]));
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function close() {
        $this->conn->close();
    }
}
?>

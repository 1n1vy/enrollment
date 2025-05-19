<?php
require_once '../Handlers/connection.php';

class Semester{

    private $db;

    public function __construct() {
        $database = new Connection();
        $this->db = $database->getConnection();
    }

    public function getApiSemester(){
        $sql = "SELECT * FROM semesters";
        $result = $this->db->query($sql);

        $semester = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $semester[] = $row;
            }
        }
        return $semester;
    }
}
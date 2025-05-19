<?php
require_once '../Handlers/connection.php';

class EducationLevel{

    private $db;

    public function __construct() {
        $database = new Connection();
        $this->db = $database->getConnection();
    }

    public function getApiEducationlevel(){
        $sql = "SELECT * FROM education_levels";
        $result = $this->db->query($sql);

        $education_levels = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $education_levels[] = $row;
            }
        }
        return $education_levels;
    }
}
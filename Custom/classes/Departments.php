<?php
require_once '../Handlers/connection.php';

class Departments {
    private $db;

    public function __construct() {
        $database = new Connection(); // Initialize Connection class
        $this->db = $database->getConnection(); // Get the database connection
    }

    public function getApiDepartments() {
        $sql = "SELECT * FROM departments";
        $result = $this->db->query($sql);

        $departments = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $departments[] = $row;
            }
        }
        return $departments;
    }
    public function getDepartmentById($id){
        $sql = "SELECT * FROM departments WHERE department_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if($row = $result->fetch_assoc()){
            print_r($row);
        }
        else{
            echo false;
        }
    }
}
?>

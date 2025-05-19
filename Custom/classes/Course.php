<?php
require_once '../Handlers/connection.php';

class Course {
    private $db;

    public function __construct() {
        $database = new Connection();
        $this->db = $database->getConnection();
    }
    public function getApiCourseByDepartmentId($id = null) {
        $courses = [];
    
        if (!empty($id)) { // Ensure ID is provided
            $sql = "SELECT * FROM courses WHERE department_id = ?";
            $stmt = $this->db->prepare($sql);
    
            if (!$stmt) {
                die(json_encode(["status" => "error", "message" => "Prepare failed: " . $this->db->error]));
            }
    
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $sql = "SELECT * FROM courses"; // ✅ Corrected SQL query
            $result = $this->db->query($sql); // ✅ Use `query()` for non-prepared statements
        }
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $courses[] = $row;
            }
        }
    
        return $courses;
    }
    
}
?>

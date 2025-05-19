<?php 
require_once '../Handlers/connection.php';

class Major{
    private $db;

    public function __construct()
    {
        $database = new Connection(); // Initialize Connection class
        $this->db = $database->getConnection(); // Get the database con
    }
    public function getApiMajorByCourseAndDepartment($departmentId, $courseId = null) {
        $majors = [];

        if ($courseId) {
            // If course_id is provided, filter by both course_id and department_id
            $sql = "SELECT * FROM majors m 
                    INNER JOIN courses c ON m.course_id = c.id 
                    WHERE c.department_id = ? AND m.course_id = ?";
            $stmt = $this->db->prepare($sql);

            if (!$stmt) {
                die(json_encode(["status" => "error", "message" => "Prepare failed: " . $this->db->error]));
            }

            $stmt->bind_param('ii', $departmentId, $courseId);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            // If course_id is not provided, fetch all majors for the given department_id
            $sql = "SELECT * FROM majors m
                    INNER JOIN courses c ON m.course_id = c.id
                    WHERE c.department_id = ?";
            $stmt = $this->db->prepare($sql);

            if (!$stmt) {
                die(json_encode(["status" => "error", "message" => "Prepare failed: " . $this->db->error]));
            }

            $stmt->bind_param('i', $departmentId); // Bind department_id parameter
            $stmt->execute();
            $result = $stmt->get_result();
        }

        // If results are found, return the majors
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $majors[] = $row; // Add each major to the array
            }
        }

        return $majors;
    }
}
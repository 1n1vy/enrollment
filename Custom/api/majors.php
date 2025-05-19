<?php

header('Content-Type: application/json');
require_once "../classes/Major.php";

// Fetch course_id and department_id from query string
$courseId = isset($_GET['course_id']) && is_numeric($_GET['course_id']) ? intval($_GET['course_id']) : null;
$departmentId = isset($_GET['department_id']) && is_numeric($_GET['department_id']) ? intval($_GET['department_id']) : null;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Ensure department_id is provided, as it's required
    if ($departmentId) {
        $major = new Major();
        // Fetch majors based on department_id and optional course_id
        echo json_encode($major->getApiMajorByCourseAndDepartment($departmentId, $courseId));
    } else {
        echo json_encode(["status" => "error", "message" => "Missing department_id"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

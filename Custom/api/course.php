<?php

header('Content-Type: application/json');
require_once "../classes/Course.php";

$id = isset($_GET['department_id']) && is_numeric($_GET['department_id']) ? intval($_GET['department_id']) : null;
    $course = new Course();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($course->getApiCourseByDepartmentId($id));
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
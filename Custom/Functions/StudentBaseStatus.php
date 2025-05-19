<?php

require_once __DIR__ . '/Handlers/connection.php';
require_once '../Functions/StudentBase/display.php';
require_once '../Functions/StudentBase/queries.php';

$conn = new Connection();
$mysqli = $conn->getConnection();

// Get filters from URL
// URL Parameters
$department_id   = isset($_GET['college']) ? (int) $_GET['college'] : 0;
$course_id       = isset($_GET['course']) ? (int) $_GET['course'] : 0;
$gender          = isset($_GET['gender']) ? strtolower(trim($_GET['gender'])) : 'all';
$year_level      = isset($_GET['year_level']) ? trim($_GET['year_level']) : 'all';
$student_status  = isset($_GET['student_status']) ? trim(strtolower($_GET['student_status'])) : 'all';
$age_param       = isset($_GET['age']) && trim($_GET['age']) !== '' ? (int) trim($_GET['age']) : null;

// Get level names using your education_levels table
$level_names = getLevelNamesById($mysqli, $year_level);
$level_names = getLevelNames($mysqli, $level_names);

// Build the select columns based on the level names and gender
$select_levels = buildSelectColumns($mysqli, $level_names, $gender);

// Build WHERE conditions
$where_conditions = [];

// Gender filter
if ($gender !== 'all') {
    $gender_escaped = $mysqli->real_escape_string($gender);
    $where_conditions[] = "s.gender = '$gender_escaped'";
}

// Department filter
if ($department_id !== 0) {
    $where_conditions[] = "d.id = $department_id";
}

// Course filter
if ($course_id !== 0) {
    $where_conditions[] = "c.id = $course_id";
}

// Education level filter
$escaped_levels = implode("','", array_map(function($v) use ($mysqli) {
    return $mysqli->real_escape_string(trim($v));
}, $level_names));
$where_conditions[] = "e.level_name IN ('$escaped_levels')";

// Student status filter
if ($student_status !== 'all') {
    $status_escaped = $mysqli->real_escape_string($student_status);
    $where_conditions[] = "s.student_status = '$status_escaped'";
}

// Age filter (if provided, only include students with the exact age)
if ($age_param !== null) {
    $where_conditions[] = "s.age = $age_param";
}

// Combine all conditions with AND
$where = 'WHERE ' . implode(' AND ', $where_conditions);

// Execute query
$result = fetchStudentData($mysqli, $where, $select_levels);

// Prepare data for display
$data = [];
$department_counts = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
        $dept = $row['department'];
        if (!isset($department_counts[$dept])) {
            $department_counts[$dept] = 0;
        }
        $department_counts[$dept]++;
    }
}

displayTable($data, $department_counts, $level_names, $gender);
?>
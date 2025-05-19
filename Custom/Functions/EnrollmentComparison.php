<?php
require_once '../../Custom/Handlers/connection.php';
require_once '../../Custom/Functions/Comparison/EnrollmentComparison.php';
require_once '../../Custom/Functions/Comparison/EnrollmentQuery.php';

$database = new Connection();
$conn = $database->getConnection();

$database = new Connection();
$conn = $database->getConnection();

$department_id = isset($_GET['college']) && $_GET['college'] !== '' ? (int) $_GET['college'] : 0;
$course_id     = isset($_GET['course']) && $_GET['course'] !== '' ? (int) $_GET['course'] : 0;
$major_id      = isset($_GET['major']) && $_GET['major'] !== '' ? (int) $_GET['major'] : 0;
$gender        = isset($_GET['gender']) ? strtolower(trim($_GET['gender'])) : 'all';
$year_level    = $_GET['year_level'] ?? 'all';
$student_status = isset($_GET['student_status']) ? strtolower(trim($_GET['student_status'])) : 'all';
$age_param     = isset($_GET['age']) && trim($_GET['age']) !== '' ? (int) trim($_GET['age']) : null;
$prev_year     = $_GET['prev_year'] ?? '';
$prev_term     = $_GET['prev_term'] ?? '';
$curr_year     = $_GET['curr_year'] ?? '';
$curr_term     = $_GET['curr_term'] ?? '';

$prevResults = getEnrollmentCounts(
    $conn,
    $prev_year,
    $prev_term,
    $gender,
    $department_id,
    $course_id,
    $major_id,
    $year_level,
    $student_status,
    $age_param
);

$currResults = getEnrollmentCounts(
    $conn,
    $curr_year,
    $curr_term,
    $gender,
    $department_id,
    $course_id,
    $major_id,
    $year_level,
    $student_status,
    $age_param
);

$prevTitle = "$prev_year Term $prev_term";
$currTitle = "$curr_year Term $curr_term";

buildComparisonTable($prevResults, $currResults, $prevTitle, $currTitle, $conn, $department_id);
?>

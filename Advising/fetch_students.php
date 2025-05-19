<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../includes/db_connection.php';

$query = "SELECT s.student_number, s.first_name, s.last_name, s.middle_name, s.gender, s.created_at, c.course_name, m.major_name, d.department_name, s.student_status, s.course_id, s.major_id, s.departments_id, s.classification_code
    FROM students s
    LEFT JOIN courses c ON s.course_id = c.id
    LEFT JOIN majors m ON s.major_id = m.id
    LEFT JOIN departments d ON s.departments_id = d.id  -- Corrected department join
";



$result = $conn->query($query);

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

echo json_encode($students);
?>

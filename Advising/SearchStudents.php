<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../includes/db_connection.php';

// Define the query to fetch students with required columns
$query = "SELECT s.student_number, s.last_name, s.first_name,  s.school_year,  s.classification_code, s.is_paid AS confirm_paid, s.payment_mode, c.course_name, s.gender
          FROM students s
          LEFT JOIN courses c ON s.course_id = c.id";

// Execute the query
$result = $conn->query($query);

// Prepare an array to hold the student data
$students = [];
while ($row = $result->fetch_assoc()) {
    // Add each student to the array
    $students[] = $row;
}

// Return the result as JSON
echo json_encode($students);
?>


<?php
// Ensure JSON response only when handling an AJAX request
if (isset($_GET['student_number']) && !empty($_GET['student_number'])) {
    header('Content-Type: application/json'); // Set response type to JSON

    // Start session if not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Connect to database
    require '../includes/db_connection.php'; 

    $student_number = $_GET['student_number'];

    // Prepare and execute the query to get student details and suggestions
    $stmt = $conn->prepare("SELECT id, student_number, first_name, last_name, middle_name 
                            FROM students 
                            WHERE student_number LIKE ? LIMIT 10"); // Use LIKE for partial matches
    $student_number = "%" . $student_number . "%"; // Add wildcards for partial match
    $stmt->bind_param("s", $student_number);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = [
            'student_number' => $row['student_number'],
            'full_name' => "{$row['last_name']}, {$row['first_name']} {$row['middle_name']}"
        ];
    }

    // If there are results, return the data
    if ($students) {
        echo json_encode([
            'success' => true,
            'students' => $students
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Student not found']);
    }

    $stmt->close();
    $conn->close();
    exit(); // Stop further execution after JSON response
}
?>

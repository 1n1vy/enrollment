<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: http://localhost/capst/index.php");
    exit();
}

// Include the database connection
if (file_exists('../../includes/db_connection.php')) {
    require_once '../../includes/db_connection.php';
} else {
    die('Database connection file not found!');
}

// Check if form data was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $building_code = $conn->real_escape_string($_POST['building']);
    $floor = $conn->real_escape_string($_POST['floor']);
    $room_number = $conn->real_escape_string($_POST['room_number']);
    $room_capacity = is_numeric($_POST['room_capacity']) ? (int)$_POST['room_capacity'] : null;
    $no_subject = isset($_POST['no_subject']) ? 1 : 0;
    $room_conflict = isset($_POST['room_conflict']) ? 1 : 0;
    $room_type = $conn->real_escape_string($_POST['room_type']);
    $status = $conn->real_escape_string($_POST['status']);
    $description = $conn->real_escape_string($_POST['description']);
    $inspection_date = $conn->real_escape_string($_POST['inspection_date']);

    // Validate required fields
    if (empty($building_code) || empty($floor) || empty($room_number) || empty($room_type)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
        exit();
    }

    // Construct SQL query
    $sql = "INSERT INTO rooms (building_code, floor, room_number, room_capacity, no_subject, room_conflict, room_type, status, description, last_inspection_date) 
            VALUES ('$building_code', '$floor', '$room_number', '$room_capacity', '$no_subject', '$room_conflict', '$room_type', '$status', '$description', '$inspection_date')";

    // Execute query
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Room added successfully!']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();

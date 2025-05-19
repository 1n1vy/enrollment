<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page
    header("Location: http://localhost/capst/index.php");
    exit();
}

// Include the database connection
if (file_exists('../../includes/db_connection.php')) {
    require_once '../../includes/db_connection.php';
} else {
    die('Database connection file not found!');
}

// Fetch room data from the database
$sql = "SELECT id, building_code, floor, room_number, last_inspection_date, description, room_type, status, no_subject, room_capacity FROM rooms";
$result = $conn->query($sql);

// Check if there are any rows returned
$rooms = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $rooms]);
} else {
    echo json_encode(['success' => false, 'message' => 'No rooms found.']);
}

// Close the database connection
$conn->close();

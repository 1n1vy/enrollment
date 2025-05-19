<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: http://localhost/capst/index.php");
    exit();
}

// Include database connection
if (file_exists('../../includes/db_connection.php')) {
    require_once '../../includes/db_connection.php';
} else {
    die('Database connection file not found!');
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $id = intval($_POST['id']); // Ensure ID is an integer
    $building_code = $conn->real_escape_string($_POST['building']); // Updated to match `building_code`
    $floor = $conn->real_escape_string($_POST['floor']);
    $room_number = $conn->real_escape_string($_POST['room_number']);
    $room_capacity = intval($_POST['room_capacity']); // Convert to integer
    $no_subject = isset($_POST['no_subject']) ? 1 : 0;
    $room_conflict = isset($_POST['room_conflict']) ? 1 : 0;
    $room_type = $conn->real_escape_string($_POST['room_type']);
    $status = $conn->real_escape_string($_POST['status']);
    $description = $conn->real_escape_string($_POST['description']);
    $inspection_date = $conn->real_escape_string($_POST['inspection_date']);

    // Construct the UPDATE query
    $sql = "UPDATE rooms SET 
        building_code = '$building_code', 
        floor = '$floor', 
        room_number = '$room_number', 
        room_capacity = $room_capacity, 
        no_subject = $no_subject, 
        room_conflict = $room_conflict, 
        room_type = '$room_type', 
        status = '$status', 
        description = '$description', 
        last_inspection_date = '$inspection_date' 
        WHERE id = $id";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // Redirect after successful update
        $_SESSION['update_success'] = true; // Set success flag for modal
        header("Location: main.php");
        exit();
    } else {
        // Display error message
        echo "Error updating record: " . $conn->error;
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();

<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: http://localhost/capst/index.php");
    exit();
}

if (file_exists('../../includes/db_connection.php')) {
    require_once '../../includes/db_connection.php';
} else {
    die('Database connection file not found!');
}

$roomId = $_GET['id'];
$query = "DELETE FROM rooms WHERE id = $roomId";
if ($conn->query($query)) {
    echo 'Room deleted successfully';
} else {
    echo 'Error deleting room: ' . $conn->error;
}

<?php
session_start();
require '../includes/db_connection.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($password)) {
        header("Location: http://localhost/capst/index.php?error=emptyfields");
        exit();
    }

    // Prepare and execute query with join to fetch role and specific role
    $stmt = $conn->prepare("
        SELECT u.*, r.role_name, sr.specific_name 
        FROM users u
        JOIN roles r ON u.role_id = r.id
        LEFT JOIN specific_roles sr ON u.specific_role_id = sr.id
        WHERE u.username = ?
    ");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role_as'] = $user['role_name']; // Set role from role_name
            $_SESSION['specific_role'] = $user['specific_name']; // Set specific role from specific_name

            // Redirect based on role
            if ($user['role_name'] === 'Admin') {
                header("Location: http://localhost/capst/welcome.php"); //change this if you have page for admin
            } elseif ($user['role_name'] === 'Faculty') {
                header("Location: http://localhost/capst/welcome.php"); //change this if you have page for faculty
            } else {
                header("Location: http://localhost/capst/welcome.php");
            }
            exit();
        } else {
            header("Location: http://localhost/capst/index.php?error=invalidpassword");
            exit();
        }
    } else {
        header("Location: http://localhost/capst/index.php?error=usernotfound");
        exit();
    }
} else {
    header("Location: http://localhost/capst/index.php");
    exit();
}

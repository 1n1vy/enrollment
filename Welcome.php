<?php
require 'includes/auth.php';
redirectIfNotLoggedIn();

// Optionally, restrict access by role
checkRole(['Admin', 'Faculty', 'Registrar', 'Building Manager']);
require_once 'db.php';
// var_dump($_SESSION); // Check if role_as is set properly

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Back</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <!-- Navbar placeholder -->
    <nav id="navbar-placeholder">
        <p>Loading navbar...</p>
    </nav>


    <!-- Main content -->
    <div class="main-content" id="mainContent">
        <div class="form-container bg-white shadow-lg rounded-lg p-8">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-800">Welcome to Enrollment System</h2>
                <p class="text-gray-600">We're glad to see you again.</p>
            </div>
        </div>
    </div>

    <!-- Load Navbar and Script -->
    <?php
    require_once './navbar-component.php';
    ?>
</body>

</html>
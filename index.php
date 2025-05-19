<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'includes/auth.php';
redirectIfLoggedIn();
require_once 'db.php';

$messageLogin = ""; // Placeholder for login messages

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'emptyfields':
            $messageLogin = "Please fill in all fields.";
            break;
        case 'invalidpassword':
            $messageLogin = "Invalid password.";
            break;
        case 'usernotfound':
            $messageLogin = "User not found.";
            break;
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<!-- Navbar -->
<nav class="navbar">
    <!-- Hamburger Button Icon on the left side -->
    <div class="hamburger-icon p-4 cursor-pointer" id="hamburgerIcon">
        <i class="bx bx-menu"></i> <!-- Hamburger icon -->
    </div>
    <div class="logo_item">
        <img src="http://localhost/capst/images/Logo.png" alt=""> ENROLLMENT SYSTEM - SCHOOL SYSTEM
    </div>
</nav>

<body class="bg-gray-100">
    <!-- Header -->
    <header class="section-header text-sm md:text-xl">
        <h1>LOGIN</h1>
    </header>

    <!-- Main Content -->
    <main class="flex items-center justify-center min-h-screen">
        <div class="form-container bg-white shadow-lg rounded-lg p-8">
            <form action="http://localhost/capst/handlers/authenticate.php" method="POST" class="space-y-6">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-800">Welcome Back</h2>
                    <p class="text-gray-600">Please enter your login details</p>
                </div>
                <!-- Username -->
                <div class="form-group">
                    <label for="username" class="block text-gray-700 font-semibold">Username</label>
                    <input type="text" name="username" placeholder="Enter your username" required class="w-full border border-gray-300 rounded-md p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="block text-gray-700 font-semibold">Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required class="w-full border border-gray-300 rounded-md p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <!-- Submit Button -->
                <div class="form-actions text-center mt-4">
                    <button type="submit" name="login" style=" background-color: #174069;" class="w-full text-white font-semibold py-3 rounded-md transition duration-200">Login</button>
                </div>
                <p><?php echo htmlspecialchars($messageLogin); ?></p>
            </form>

        </div>
    </main>


</body>

</html>
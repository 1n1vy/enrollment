<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../includes/auth.php';
redirectIfNotLoggedIn();

// Optionally, restrict access by role
checkRole(['Admin', 'Registrar']);

if (file_exists('../includes/db_connection.php')) {
    require_once '../includes/db_connection.php';
} else {
    die('Database connection file not found!');
}
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page
    header("Location: http://localhost/capst/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Move students/Section </title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <nav id="navbar-placeholder">
        <p>Loading navbar...</p>
    </nav>
    <div class="main-content" id="mainContent">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Enrollment</a></li>
                <li><a href="#">Change of Subject</a></li>
                <li class="active">Move students/Section </li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>Move students/Section Main page</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form action="#">
                <!-- Operation Links Section -->
                <div class="operation-section mt-6">
                    <p class="text-black font-bold" style="font-size: 30px;">Operation :</p>
                    <ul class="space-y-2">
                        <li><a href="ChangeStudent.php" class="underline hover:no-underline text-black">Move Student(s)</a></li>

                        <li><a href="MoveSection.php" class="underline hover:no-underline text-black">Move Section</a></li>

                        <li><a href="NSTP.php" class="underline hover:no-underline text-black">Change NSTP Component</a></li>

                    </ul>
                </div>


                <div id="table-container" class="mt-10"></div>
            </form>
        </div>

        <script>
            // Load navbar dynamically
            (function loadNavbar() {
                fetch('../Components/Navbar.php')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Navbar.html does not exist or is inaccessible');
                        }
                        return response.text();
                    })
                    .then(html => {
                        document.getElementById('navbar-placeholder').innerHTML = html;
                        // Dynamically load app.js if not already loaded
                        if (!document.querySelector('script[src="../../Components/app.js"]')) {
                            const script = document.createElement('script');
                            script.src = '../Components/app.js';
                            script.defer = true;
                            document.body.appendChild(script);
                        }
                    })
                    .catch(error => {
                        console.error('Error loading navbar:', error);
                        document.getElementById('navbar-placeholder').innerHTML =
                            '<p style="color: red; text-align: center;">Navbar could not be loaded.</p>';
                    });
            })();

            // Show the table when Proceed is clicked
            document.getElementById('proceed-btn').addEventListener('click', function() {
                document.getElementById('schedule-table').classList.remove('hidden');
            });
        </script>
</body>

</html>

<!-- CSS styling -->
<style scoped>
    /* Breadcrumb styles */
    .breadcrumb-nav {
        margin: 0;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .breadcrumb {
        list-style: none;
        display: flex;
        flex-wrap: wrap;
        padding: 0;
    }

    .breadcrumb li {
        margin-right: 10px;
    }

    .breadcrumb li a {
        color: #174069;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .breadcrumb li a:hover {
        color: #20568B;
    }

    .breadcrumb li.active {
        color: orange;
        pointer-events: none;
    }

    .breadcrumb li::after {
        content: ">";
        margin-left: 10px;
        color: #174069;
    }

    .breadcrumb li:last-child::after {
        content: "";
    }

    /* Section Header */
    .section-header {
        background-color: #174069;
        padding: 20px;
        text-align: center;
        margin-bottom: 20px;
    }

    .section-header h1 {
        color: white;
        margin: 0;
    }

    /* Form styles */
    .form-container {
        width: 80%;
        margin: 40px auto;
        background-color: #f4f8fc;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .form-group {
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .form-group input,
    .form-group select {
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    /* Adjust width for the student ID input */
    .short-input {
        width: 50%;
        /* Shorter input box for Student ID */
    }

    /* Inline form group for student ID and dropdown */
    .form-row {
        display: flex;
        align-items: center;
    }

    .short-dropdown {
        width: 10%;
        /* Shorter dropdown for semester */

    }

    /* Shorter text boxes for Offering SY */
    .form-row input {
        width: 30%;
        margin-right: 10px;
    }

    .form-row span {
        margin-right: 10px;
    }

    /* Button styles */
    .form-actions {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .form-actions button {
        padding: 12px 20px;
        font-size: 16px;
        background-color: #174069;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-actions button:hover {
        background-color: #20568B;
    }

    /* Save button in the bottom right corner */
    .save-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 12px 20px;
        font-size: 16px;
        background-color: #174069;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .save-button:hover {
        background-color: #20568B;
    }

    .bi-plus-square-fill {
        font-size: 24px;
        color: black;
        transition: color 0.3s, transform 0.3s;
        /* smooth transition */
    }

    /* Hover state */
    .bi-plus-square-fill:hover {
        color: blue;
        /* change color on hover */
        transform: rotate(90deg);
        /* rotate the icon */
    }

    selector {
        font-size: 50;
    }
</style>
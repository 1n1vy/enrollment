<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../../includes/auth.php';
redirectIfNotLoggedIn();

// Optionally, restrict access by role
checkRole(['Admin', 'Registrar']);

if (file_exists('../../includes/db_connection.php')) {
    require_once '../../includes/db_connection.php';
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
    <title>Hold/Unhold</title>
    <!-- Load Google Fonts Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar placeholder -->
    <nav id="navbar-placeholder">
        <p>Loading navbar...</p>
    </nav>

    <!-- Main content -->
    <div class="main-content p-6" id="mainContent">
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Enrollment</a></li>
                <li><a href="#">Override Parameters</a></li>
                <li class="active">Hold/Unhold</li>
            </ul>
        </nav>

        <section class="section-header mb-20">
            <h1 class="text-2xl font-semibold">SET PARAMETERS - HOLD AND UNHOLD STUDENT</h1>
        </section>

        <div class="form-container">

            <form action="#">
                <div class="grid grid-cols-2 md:grid-cols-2">

                    <!-- Left Column -->
                    <div class="left-column flex-1">
                        <div class="form-group mb-4">
                            <div class="form-group">
                                <label for="studID">STUDENT ID</label>
                                <input type="text" id="studID" placeholder="Enter">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="form-actions mt-8 flex justify-center">
                    <button type="button" id="show-PROCEED" class="px-6 py-2 bg-blue-900 text-white rounded-md" onclick="showAdditionalFields()">PROCEED</button>
                </div>

                <div class="border-b-4 border-black my-4"></div>

                <div id="additional-fields-status" class="mt-4" style="display: none; font-size: 14px;">
                    <div class="flex flex-wrap gap-4 justify-center">
                        <div class="info-container">
                            <div class="info-row">
                                <div class="info-label">Student Name</div>
                                <div class="info-value">EUMIRA SURAVILLA ENDRANO</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Last School Year/Term</div>
                                <div class="info-value">2nd Sem, 2023-2024></div>
                            </div>
                            <label>
                                <input type="radio" name="hold" value="Hold" style="margin-right: 5px;">
                                <span style="font-weight: bold;">Hold</span>
                            </label>
                            <div class="form-group mb-3">
                                <label for="notes" class="form-label">Reason</label>
                                <textarea id="notes" class="form-control custom-textarea" rows="5"></textarea>
                            </div>



                            <script>
                                // Load navbar dynamically
                                (function loadNavbar() {
                                    fetch('../../Components/Navbar.php')
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
                                                script.src = '../../Components/app.js';
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

                                function showAdditionalFields() {
                                    // Display the hidden information section
                                    document.getElementById("additional-fields-status").style.display = "block";
                                }
                            </script>

</body>

</html>

<style scoped>
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

    .section-header {
        background-color: #174069;
        padding: 20px;
        text-align: center;
        margin-bottom: 20px;
    }

    .section-header h1 {
        color: white;
        font-size: 24px;
        margin: 0;
    }

    .form-container {
        width: 100%;
        background-color: #f4f8fc;
        padding: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;

    }

    .form-group {
        margin: 20px 0 15px 0;
        /* Adjusted for top and bottom margins */
    }

    .form-group label {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
    }

    .form-group input,
    .form-group select {
        padding: 10px;
        font-size: 14px;
        border: 1px solid #000000;
        border-radius: 4px;
        margin-top: 5px;
    }

    .info-container {
        width: 100%;
        font-family: Arial, sans-serif;
        background-color: #fffdfd;
        padding: 10px;
        border-radius: 7px;
    }

    .info-row {
        display: flex;
        padding: 8px 0;
    }

    .info-label {
        font-weight: bold;
        color: #000000;
        width: 30%;
    }

    .info-value {
        color: #000000;
        width: 70%;
    }

    .custom-textarea {
        width: 60%;
        margin-top: 5px;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #000;
        border-radius: 4px;
        background-color: #ffffff;
    }
</style>
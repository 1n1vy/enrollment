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
    <title>Override</title>
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
                <li class="active">Allow Add/Drop</li>
            </ul>
        </nav>

        <section class="section-header mb-20">
            <h1 class="text-2xl font-semibold">Allow Add/Drop</h1>
        </section>

        <div class="form-container">

            <form action="#">
                <div class="grid grid-cols-2 md:grid-cols-2">

                    <!-- Left Column -->
                    <div class="left-column flex-1">
                        <div class="form-group mb-4">
                            <label for="schoolYear" class="block font-bold text-sm">SCHOOL YEAR</label>
                            <div class="flex space-x-2">
                                <input type="text" id="year" class="p-2 border border-gray-300 rounded" placeholder="Enter">
                                <select id="term">
                                    <option value="" disabled selected>Select Term</option>
                                    <option value="1st-Semester">1st Semester</option>
                                    <option value="2nd-Semester">2nd Semester</option>
                                    <option value="summer">Summer</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="studID">STUDENT ID</label>
                            <input type="text" id="studID" placeholder="Enter">
                        </div>
                        <div class="form-group">
                            <label for="allowdte">Allow Date</label>
                            <input type="date" id="allowdte" min="2017-01-01" max="2024-12-31">
                        </div>
                    </div>
                </div>
                <div class="form-actions mt-8 flex justify-center">
                    <button type="button" id="show-VIEW" class="px-6 py-2 bg-blue-900 text-white rounded-md" onclick="showAdditionalFields()">VIEW</button>
                    <button type="button" id="show-SAVE" class="ml-4 px-6 py-2 bg-blue-900 text-white rounded-md" onclick="showAdditionalFields()">SAVE</button>
                </div>

                <div class="border-b-4 border-black my-4"></div>


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
        width: 60%;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #000000;
        border-radius: 4px;
        margin-top: 5px;
    }
</style>
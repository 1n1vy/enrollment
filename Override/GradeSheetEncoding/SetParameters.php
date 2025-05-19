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
                <li><a href="#">Last Date of Grade Encoding</a></li>
                <li class="active">Grade Sheet Encoding</li>
            </ul>
        </nav>

        <section class="section-header mb-20">
            <h1 class="text-2xl font-semibold">GRADE SHEET ENCODING/SUBMISSION PAGE</h1>
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
                                <input type="text" id="year" class="p-2 border border-gray-300 rounded" placeholder="Enter">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="term">TERM</label>
                            <select id="term">
                                <option value="" disabled selected>Select Term</option>
                                <option value="1st-Semester">1st Semester</option>
                                <option value="2nd-Semester">2nd Semester</option>
                                <option value="summer">Summer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="start-date">Grade Encoding Date</label>
                            <div class="date-picker">
                                <input type="date" id="start-date" placeholder="Start Date">
                                <input type="date" id="end-date" placeholder="End Date">
                            </div>
                        </div>
                    </div>
                    <div class="right-column">
                        <div class="form-group">
                            <label for="set">Set Parameters for</label>
                            <select id="set">
                                <option value="" disabled selected>Select</option>
                                <option value="1st-Semester">Prelim</option>
                                <option value="2nd-Semester">Midterm</option>
                                <option value="summer">Finals</option>
                                <option value="1st-Semester">1st Grading Period</option>
                                <option value="2nd-Semester">2nd Grading Period</option>
                                <option value="1st-Semester">3rd Grading Period</option>
                                <option value="2nd-Semester">4th Grading Period</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="room-number">Allow Specific Faculty</label>
                            <input type="text" id="room-number" placeholder="Enter Faculty Employee ID">
                        </div>
                    </div>
                </div>
                <div class="form-actions mt-8 flex justify-center">
                    <button type="button" id="show-SAVE" class="px-6 py-2 bg-blue-900 text-white rounded-md" onclick="showAdditionalFields()">SAVE</button>
                </div>
                <div class="border-b-4 border-black my-4"></div>

                <!-- Additional input fields for No. of Students based on Status, initially hidden -->
                <div id="additional-fields-status" class="mt-4" style="display: none; font-size: 14px;">
                    <div class="flex flex-wrap gap-4 justify-center">
                        <div class="info-container">
                            <table class="min-w-full border-collapse border border-gray-300">
                                <thead>
                                    <tr>
                                        <th class="border border-gray-300 px-4 py-2">Exam Name</th>
                                        <th class="border border-gray-300 px-4 py-2">Grade Encoding Date Range</th>
                                        <th class="border border-gray-300 px-4 py-2">Faculty Employee ID</th>
                                        <th class="border border-gray-300 px-4 py-2">DELETE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-gray-300 px-4 py-2">Midterm Exam</td>
                                        <td class="border border-gray-300 px-4 py-2">1/12/2024 - 1/12/2024</td>
                                        <td class="border border-gray-300 px-4 py-2">123456</td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            <a href="#" class="bi bi-trash icon-link text-red-500" title="Delete" onclick="deleteRow(this)"></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 px-4 py-2">Final Exam</td>
                                        <td class="border border-gray-300 px-4 py-2">2/9/2024 - 2/13/2024</td>
                                        <td class="border border-gray-300 px-4 py-2">654321</td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            <a href="#" class="bi bi-trash icon-link text-red-500" title="Delete" onclick="deleteRow(this)"></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 px-4 py-2">2nd Grading Period</td>
                                        <td class="border border-gray-300 px-4 py-2">2/10/2024 - 2/13/2024</td>
                                        <td class="border border-gray-300 px-4 py-2">78910</td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            <a href="#" class="bi bi-trash icon-link text-red-500" title="Delete" onclick="deleteRow(this)"></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>


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
                                    document.getElementById("additional-fields-status").style.display = "block";
                                }

                                function deleteRow(link) {
                                    event.preventDefault();
                                    const row = link.closest('tr');
                                    row.remove();
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

    }

    .form-group {
        margin: 10px 0 15px 0;
    }

    .form-group label {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
        margin-left: 20px;
    }

    .form-group input,
    .form-group select {
        padding: 10px;
        font-size: 14px;
        border: 1px solid #000000;
        border-radius: 4px;
        margin-left: 20px;

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

    .highlight {
        color: #174069;
        font-weight: bold;
        text-align: left;
    }

    .grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .left-column,
    .center-column,
    .right-column {
        flex: 1;
        min-width: 200px;
    }

    .form-actions {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
</style>
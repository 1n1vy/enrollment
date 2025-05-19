<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../../includes/auth.php';
redirectIfNotLoggedIn();

// Optionally, restrict access by role
checkRole(['Admin', 'Faculty']);

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
    <title>Faculty</title>
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
                <li><a href="#">Faculty</a></li>
                <li class="active">Reports | Display Schedule per Section</li>
            </ul>

        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>FACULTY PAGE - CLASS TIME SCHEDULE</h1>
        </section>


        <!-- Form container -->
        <div class="form-container">
            <form>

                <!-- SY/Term Section -->
                <div class="flex justify-between items-center mb-6">
                    <label for="sy-term" class="custom-label">Class program for SY-Term*</label>
                    <div class="flex space-x-2">
                        <input type="text" id="sy-start" name="sy-start" class="custom-input w-1/4"
                            placeholder="Enter">
                        <span class="font-bold">-</span>
                        <input type="text" id="sy-end" name="sy-end" class="custom-input w-1/4"
                            placeholder="Enter">
                        <span class="font-bold">-</span>
                        <select id="term" name="term" class="custom-input w-1/4">
                            <option value="2nd-sem">2nd Sem</option>
                            <!-- Add more options here -->
                        </select>
                    </div>
                </div>

                <!-- Course Program Section -->
                <div class="flex justify-between items-center mb-6">
                    <label for="course-program" class="custom-label">Course Program (Optional to select)*</label>
                    <select id="course-program" name="course-program" class="custom-input w-3/4">
                        <option value="">Select</option>
                        <!-- Add more options here -->
                    </select>
                </div>

                <!-- Course and Curriculum Year Section -->
                <div class="flex justify-between items-center mb-6">
                    <label for="course" class="custom-label">Course*</label>
                    <select id="course" name="course" class="custom-input w-1/3">
                        <option value="any">Select Any</option>
                        <!-- Add more courses here -->
                    </select>

                    <label for="curriculum-year" class="custom-label ml-6">Curriculum Year*</label>
                    <div class="flex space-x-2 items-center">
                        <select id="curriculum-year-start" name="curriculum-year-start"
                            class="custom-input w-1/3">
                            <option value="">Select Year</option>
                            <!-- Add year options -->
                        </select>
                        <span>to</span>
                        <select id="curriculum-year-end" name="curriculum-year-end"
                            class="custom-input w-1/3">
                            <option value="">Select Year</option>
                            <!-- Add year options -->
                        </select>
                    </div>
                </div>

                <div class="button-container">
                    <button class="button">
                        <a href="Display2.php">Go to Class Time Schedule</a>
                    </button>
                    <button class="button">
                        <a href="Display4.php">Go to Class, Plotting</a>
                    </button>
                </div>

                <div class="border-b-4 border-black my-4"></div>


                <script>
                    // Load navbar dynamically
                    (function loadNavbar() {
                        fetch('../../Components/Navbar.php')
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Navbar.php does not exist or is inaccessible');
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
                </script>
        </div>
        <div class="fixed bottom-6 right-6">
            <a href="MainReports.php" type="submit" style="background-color: #aaa;" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Back
            </a>
        </div>
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
        margin-bottom: 15px;
    }

    .form-group label {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    /* Button styles */
    .form-actions button {
        padding: 12px 20px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #e8f0f8;
        margin: 0;
        padding: 20px;
    }

    .container {
        width: 80%;
        margin: 0 auto;
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .form-group label {
        width: 20%;
        text-align: right;
        margin-right: 10px;
        font-weight: bold;
    }

    .form-group input,
    .form-group select {
        width: 70%;
        padding: 5px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    .form-group input[type="text"] {
        width: 40%;
    }

    .form-group .inline-inputs {
        display: flex;
        gap: 10px;
        width: 70%;
    }

    .buttons {
        text-align: center;
        margin-top: 20px;
    }

    .buttons button {
        background-color: #0056b3;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .buttons button:hover {
        background-color: #004494;
    }

    /* Table Preview Section */
    .table-preview {
        margin-top: 40px;
        border: 1px solid #ccc;
        padding: 20px;
        text-align: center;
    }

    .table-preview table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-preview th,
    .table-preview td {
        border: 1px solid #000;
        padding: 8px;
    }

    .table-preview th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    /* Custom Container Style */
    .custom-container {
        max-width: 1000px;
        /* Fix the width of the container */
        margin: 40px auto;
        /* Center the container horizontally */
        background-color: #ffffff;
        /* Set a white background */
        padding: 20px;
        /* Add padding */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        /* Add shadow */
        border-radius: 10px;
        /* Round the corners */
    }

    .custom-label {
        font-weight: bold;
        font-size: 1rem;
    }

    .custom-input {
        border: 1px solid #d1d5db;
        padding: 10px;
        border-radius: 5px;
        width: 100%;
    }

    .custom-link {
        color: #2563eb;
        text-decoration: none;
    }

    .custom-link:hover {
        text-decoration: underline;
    }

    .button-container {
        display: flex;
        justify-content: center;
        /* Center the buttons horizontally */
        gap: 10px;
        /* Add space between the buttons */
    }

    .button {
        background-color: #b6b8e3;
        /* Light purple color */
        color: black;
        border: none;
        border-radius: 18px;
        /* Smaller radius for a more compact look */
        padding: 8px 12px;
        /* Reduced padding */
        font-size: 12px;
        /* Smaller font size */
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        /* Remove underline from the text inside the button */
    }

    .button:hover {
        background-color: #9b9de0;
        /* Darker purple on hover */
    }

    .button a {
        text-decoration: none;
        /* Ensure the link text inside the button has no underline */
        color: inherit;
        /* Inherit the color from the button */
    }
</style>
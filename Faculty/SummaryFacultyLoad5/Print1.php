<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../../includes/auth.php';
redirectIfNotLoggedIn();

// Optionally, restrict access by role
checkRole(['Admin', 'Faculty', 'Registrar']);

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
                <li class="active">Print Detailed Faculty Load | Per College/Department</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>FACULTY PAGE - PRINT DETAILED FACULTY LOAD (PER COLLEGE/DEPARTMENT)</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form>
                <div class="button-container">
                    <a href="Print2.php" class="button">
                        Next Page
                        <span class="arrow">→</span>
                    </a>
                </div>

                <!-- School Year and Term -->
                <div class="form-group">
                    <label for="school-year">School Year:</label>
                    <div class="inline-inputs">
                        <input type="text" id="school-year-start" name="school-year-start" placeholder="Enter">
                        <span>to</span>
                        <input type="text" id="school-year-end" name="school-year-end" placeholder="Enter">
                        <select id="term" name="term">
                            <option value="2nd-sem">2nd Sem</option>
                            <!-- You can add more terms here -->
                        </select>
                    </div>
                </div>

                <!-- College -->
                <div class="form-group">
                    <label for="college">College:</label>
                    <select id="college" name="college">
                        <option value="all">Select Any</option>
                        <!-- Add college options here -->
                    </select>
                </div>

                <!-- Dept / Offices -->
                <div class="form-group">
                    <label for="dept-offices">Dept/Offices:</label>
                    <select id="dept-offices" name="dept-offices">
                        <option value="all">All</option>
                        <!-- Add department options here -->
                    </select>
                </div>

                <!-- Instructor Count -->
                <div class="form-group">
                    <label>Total Instructors to be Printed:</label>
                    <p class="output-text">1</p> <!-- This can be dynamically updated as per your needs -->
                </div>

                <!-- Print Option 1 -->
                <div class="form-group">
                    <label for="print-option-1">Print Option 1:</label>
                    <input type="text" id="print-option-1" name="print-option-1" placeholder="1 to 1">
                </div>

                <!-- Print Option 2 -->
                <div class="form-group">
                    <label for="print-option-2">Print Option 2:</label>
                    <input type="text" id="print-option-2" name="print-option-2" placeholder="Enter page">
                </div>
                <p class="hint">(Enter page numbers and/or page ranges separated by commas. For ex: 1,3,5-12)</p>

                <!-- Search Button -->
                <div class="flex items-center justify-between mt-4">
                    <a href="#" class="same-btn text-white font-bold py-2 px-6 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" style="background-color: #174069;">
                        Search
                    </a>
                </div>

                <div class="border-b-4 border-black my-4"></div>
            </form>
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
        </script>
    </div>
    <div class="fixed bottom-6 right-6">
        <a href="FacSum.php" type="submit" style="background-color: #aaa;" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            Back
        </a>
    </div>
</body>

</html>

<!-- CSS styling -->
<style scoped>
    body {
        font-family: Arial, sans-serif;
        background-color: #e8f0f8;
        margin: 0;
        padding: 20px;
    }

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
        display: flex;
        align-items: center;
        /* Align items vertically center */
    }

    .form-group label {
        width: 25%;
        /* Adjusted width for consistent alignment */
        text-align: right;
        /* Align labels to the right */
        margin-right: 10px;
        font-weight: bold;
    }

    .form-group input,
    .form-group select {
        width: 70%;
        /* Adjusted width for better alignment */
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .form-group .inline-inputs {
        display: flex;
        gap: 10px;
        width: 70%;
        /* Keep the same width for inline inputs */
    }

    .output-text {
        width: 70%;
        /* Align output text with input fields */
        padding: 10px;
        margin-left: 10px;
        /* Add margin for spacing */
        font-size: 14px;
        /* Match font size */
        border: 1px solid #ccc;
        /* Optional: border for output text */
        border-radius: 4px;
        /* Optional: rounded corners */
        background-color: #f9f9f9;
        /* Optional: background color for output text */
        text-align: left;
        /* Align text to the left */
    }

    .button-container {
        display: flex;
        justify-content: flex-end;
        /* Align button to the right */
        margin-bottom: 20px;
        /* Added margin for spacing */
    }

    .button {
        background-color: #b6b8e3;
        /* Light purple color */
        color: black;
        border: none;
        border-radius: 18px;
        /* Smaller radius for a more compact look */
        padding: 8px 12px;
        /* Further reduced padding */
        text-align: left;
        font-size: 12px;
        /* Smaller font size */
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        gap: 6px;
        /* Reduce space between text and arrow */
    }

    .button:hover {
        background-color: #9b9de0;
        /* Darker purple on hover */
    }

    .arrow {
        font-size: 16px;
        /* Further reduced arrow size */
        font-weight: bold;
    }

    /* Additional Styles */
    .hint {
        margin-left: 35%;
        /* Align hint text below the input */
        font-size: 12px;
        color: #555;
        /* Optional: hint text color */
    }
</style>
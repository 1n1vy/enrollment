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
    <title>Course Schedule-Statistics</title>
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
                <li><a href="#">Statistics</a></li>
                <li class="active">Course Schedule</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>STATISTICS-COURSE SCHEDULE PAGE</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form action="#">
                <!-- School Year and Term -->
                <div class="form-group" style="display: flex; justify-content:left;">
                    <div>
                        <label for="year" class="text-sm font-medium text-gray-700">School Year</label>
                        <div style="display: flex; gap: 10px; text-align: left; width:400px">
                            <input type="number" id="schoolyear1" name="year1" placeholder="Enter Year" class="form-input px-3 py-2 border border-gray-300 rounded-md" min="1975" max="2026" step= "1" required>
                            <span class="mt-2">to</span>
                            <input type="number" id="schoolyear2" name="year2" placeholder="Enter Year" class="form-input px-3 py-2 border border-gray-300 rounded-md" min="1975" max="2026" step= "1" required>
                        </div>
                    </div>
                    <div>
                        <label for="term" class="text-sm font-medium text-gray-700" style="margin-left: 15px;">Semester</label>
                        <select id="term" name="term" class="form-select px-3 py-2 border border-gray-300 rounded-md" style="margin-left: 10px ;" required>
                            <option value="1st">1st Sem</option>
                            <option value="2nd">2nd Sem</option>
                        </select>
                    </div>
                </div>

                <!-- College Selection -->
                <div class="form-group mt-4">
                    <label for="college" class="text-sm font-medium text-gray-700">College</label>
                    <select id="college" name="college" class="form-select w-full px-3 py-2 border border-gray-300 rounded-md" required>
                        <option value="" disabled selected>Select College / Department</option>
                        <option value="cics">College of Informatics and Computing Studies</option>
                        <option value="coa">College of Accountancy</option>
                    </select>
                </div>

                <!-- Schedule Day -->
                <div class="form-group mt-4">
                    <label class="text-sm font-medium text-gray-700">Schedule Day</label>
                    <div class="flex flex-wrap gap-3 mt-2">
                        <label><input type="checkbox" name="days[]" value="M">M</label>
                        <label><input type="checkbox" name="days[]" value="T">T</label>
                        <label><input type="checkbox" name="days[]" value="W">W</label>
                        <label><input type="checkbox" name="days[]" value="Th">Th</label>
                        <label><input type="checkbox" name="days[]" value="F">F</label>
                        <label><input type="checkbox" name="days[]" value="Sat">Sat</label>
                        <label><input type="checkbox" name="days[]" value="S">S</label>
                    </div>
                </div>


                <!-- Schedule Time -->
                <div class="form-group mt-4">
                    <label for="scheduleTime" class="text-sm font-medium text-gray-700">Schedule Time</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="time" id="startTime" name="startTime" placeholder="hh" class="form-input w-16 px-3 py-2 border border-gray-300 rounded-md" required>
                        <span style="margin-top: 9px;">to</span>
                        <input type="time" id="endTime" name="endTime" placeholder="hh" class="form-input w-16 px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <span class="text-gray-500 text-xs">Please enter correct time format</span>
                </div>

                <!-- Proceed Button -->
                <div class="flex justify-center mt-4">
                    <button type="submit" onclick="redirectToCourseSched(event)" class="bg-blue-900 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700">Proceed</button>
                </div>
            </form>
        </div>


        <script>
            function redirectToCourseSched(event) {
                event.preventDefault(); // Prevent form from submitting

                let schoolYear1 = document.getElementById("schoolyear1").value;
                let schoolYear2 = document.getElementById("schoolyear2").value;
                let term = document.getElementById("term").value;
                let college = document.getElementById("college").value;
                let days = document.querySelectorAll('input[name="days[]"]:checked');
                let startTime = document.getElementById("startTime").value;
                let endTime = document.getElementById("endTime").value;

                // Check if required fields are empty
                if (
                    schoolYear1 === "" || schoolYear2 === "" || term === "" ||
                    college === "" || days.length === 0 || startTime === "" || endTime === ""
                ) {
                    alert("Please fill in all required fields before proceeding.");
                    return; // Stop execution if fields are missing
                }

                // If all fields are filled, proceed with redirection
                window.location.href = 'CoursesSchedule.php';
    }
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
        margin-top: 5px;
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
</style>
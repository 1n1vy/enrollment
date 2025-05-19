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
    <title>Course-Statistics</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="dropdown.js"></script>

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
                <li class="active">Course</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>STATISTICS-COURSE PAGE</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form action="CoursesPage.php">
                <!-- School Year and Term -->
                <div class="form-group" style="display: flex; justify-content:left;">
                    <div>
                        <label for="year" class="text-sm font-medium text-gray-700">School Year</label>
                        <div style="display: flex; gap: 10px; text-align: left; width:400px">
                            <input type="number" id="schoolyear1" name="schoolyear1" placeholder="Enter Year" class="form-input  border-gray-300 rounded-md" min="1975" max="2026" step= "1" required>
                            <span class="mt-2">to</span>
                            <input type="number" id="schoolyear2" name="schoolyear2" placeholder="Enter Year" class="form-input border-gray-300 rounded-md" min="1975" max="2026" step= "1"required>
                        </div>
                    </div>
                    <div>
                        <label for="term" class="text-sm font-medium text-gray-700" style="margin-left: 15px;">Semester</label>
                        <select id="term" name="term" class="form-select px-3 py-2 border border-gray-300 rounded-md" style="margin-left: 10px;" required>
                            <option value="1st">1st Sem</option>
                            <option value="2nd">2nd Sem</option>
                            <option value="Summer">Summer</option>

                        </select>
                    </div>
                </div>


                <div class="form-group mb-4">
                    <label for="subject_status" class="block text-sm font-medium text-gray-700">Course Status</label>
                    <select id="subject_status" name="subject_status" class="form-select w-full px-3 py-2 border border-gray-300 rounded-md" required>
                        <option value="all">All</option>
                        <option value="open">Open</option>
                        <option value="close">Close</option>
                        <option value="dissolved">Dissolved</option>
                    </select>
                </div>

                <div class="form-group mb-4 mx-auto">
                    <label for="college-offered" class="block text-sm font-medium text-black-700 mt-8">Show By :</label>
                    <div class="form-group mt-4">
                        <label for="year" class="text-sm font-medium text-gray-700">College Offered:</label>
                        <select id="department" name="department" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            <option value="cics">College of Informatics and Computing Studies</option>
                            <option value="ced">College of Education</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="course" class="text-sm font-medium text-gray-700 text-left">Course</label>
                        <div class="display: flex">
                            <select id="course" name="course" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md" required>
                                <option value="cCC112-18">Fundamentals of Programming</option>
                                <option value="cIT213-18">Human Computer Interaction</option>
                            </select>
                            <input type="text" id="sub_code" name="sub_code" placeholder="enter sub code" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md ml-4">
                        </div>
                    </div>
                </div>

                <div class="flex justify-center mt-4">
                <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700">Proceed</button>
                </div>

            </form>
        </div>
    </div>

    <!-- Navbar script -->
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

        function redirectToCourses(event) {
            event.preventDefault(); // Prevents form submission

            // Get input values
            const year1 = document.getElementById("schoolyear1").value.trim();
            const year2 = document.getElementById("schoolyear2").value.trim();
            const term = document.getElementById("term").value;
            const courseStatus = document.getElementById("course-status").value;
            const collegeOffered = document.getElementById("college-offered").value;
            const course = document.getElementById("course").value;

            // Check if any required field is empty
            if (!year1 || !year2 || !term || !courseStatus || !collegeOffered || !course) {
                alert("Please fill in all required fields before proceeding!");
                return; // Stop execution if validation fails
            }

            // Redirect if all fields are filled
            window.location.href = 'CoursesPage.php';
        }

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

</html>
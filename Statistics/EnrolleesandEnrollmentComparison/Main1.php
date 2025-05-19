<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    <title>Enrollees and Enrollment-Statistics</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- jQuery CDN (Required for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- External JavaScript File -->
    <script src="../../Custom/assets/dropdown.js"></script>
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
                <li class="active">Enrollees and Enrollment Comparison</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>STATISTICS-ENROLLEES AND ENROLLMENT COMPARISON PAGE</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form method="GET">
                <div class="form-group" style="align-items: center;">
                    <label for="student-status">STUDENT STATUS</label>
                    <select id="student-status" name="student_status"style="width: 250px;">
                        <option value="" disabled selected>Select Student Status</option>
                        <option value="all">All</option>
                        <option value="change program">Change Program</option>
                        <option value="cross enrolle">Cross Enrollee</option>
                        <option value="new student">New Student</option>
                        <option value="old student">Old Student</option>
                        <option value="second program">Second Program</option>
                        <option value="second program(old student)">Second Program(Old Student)</option>
                        <option value="transferee">Transferee</option>
                    </select>

                    <h4 style="margin-top: 25px; margin-bottom: 15px; margin-left: 50px;"><b>SHOW BY:</b></h4>
                    <div class="form-show" style="margin-left: 70px; margin-right: 70px;">
                        <label for="department">College / Department</label>
                        <select id="department-select" name="college" required>
                            <option value="" disabled selected>Select Department</option>
                        </select>

                        <label for="course">Program</label>
                        <select id="course-select" name="course" required>
                            <option value="" disabled selected>Select Program</option>
                        </select>

                        <label for="major">Major</label>
                        <select id="major-select" name="major">
                            <option value="" disabled selected>Select Major</option>
                        </select>

                        <label for="year-level">Year Level</label>
                        <select id="education-level-select" name="year_level" required>
                            <option value="" disabled selected>Select Year Level</option>
                        </select>

                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" required>
                            <option value="" disabled selected>Select Gender</option>
                            <option value="all">All</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>

                        <label for="age">Age</label>
                        <input type="number" id="age" name="age" placeholder="Enter Age" min="15">
                    </div>
                <!-- Buttons -->
                <div class="form-actions mt-8 flex justify-center">
                    <button type="button" id="show-student-status" class="bg-blue-900 text-white rounded-md">No. of Students based on Status</button>
                    <button type="button" id="show-enrollment-comparison" class="ml-4 bg-blue-900 text-white rounded-md">Enrollment Comparison</button>
                </div>

                <!-- Additional input fields for No. of Students based on Status, initially hidden -->
                <div id="additional-fields-status" class="mt-4" style="display: none; font-size: 14px;">
                    <div class="flex flex-wrap gap-4 justify-center">
                    <div>
                            <label for="sy-from">Offering SY From</label>
                            <input type="number" id="sy-from" name="sy_from" placeholder="Enter start school year" class="border p-2 rounded-md w-full" min="2000" max="2026" step= "1" >
                        </div>
                        <div>
                            <label for="sy-to">Offering SY To</label>
                            <input type="number" id="sy-to" name="sy_to" placeholder="Enter end school year" class="border p-2 rounded-md w-full" min="2000" max="2026" step= "1" >
                        </div>
                        <div>
                            <label for="semester">Semester</label>
                            <select id="semester-select" name="semester" class="border p-2 rounded-md w-full" >
                                <option value="" disabled selected>Select Semester</option>
                            </select>
                        </div>
                    </div>
                    <p class="text-gray-500 text-xs text-center mt-2">(Keep SY From or SY To empty to ignore SY offering info)</p>
                    <div class="flex justify-center mt-4">
                        <button type="submit" formaction="/capst/Statistics/EnrolleesandEnrollmentComparison/EnrolleesPage.php" class="bg-blue-900 text-white rounded-md p-2">Proceed</button>
                    </div>
                </div>

                <!-- Additional input fields for Enrollment Comparison, initially hidden -->
                <div id="additional-fields-comparison" class="mt-4" style="display: none; font-size: 14px;">
                    <div class="flex flex-wrap gap-4 justify-center">
                        <div>
                            <label for="previous-year">Previous Year</label>
                            <input type="number" id="previous-year" name= "prev_year" placeholder="Enter previous year" class="border p-2 rounded-md w-full" min="1975" max="2026" step= "1" >
                        </div>
                        <div>
                            <label for="previous-term">Previous Semester</label>
                            <select id="previous-semester-select" name="prev_term"class="border p-2 rounded-md w-full" >
                                <option value="" disabled selected>Select Previous Semester</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-4 justify-center">
                        <div>
                            <label for="current-year">Current Year</label>
                            <input type="number" id="current-year" name="curr_year" placeholder="Enter current year" class="border p-2 rounded-md w-full" min="1975" max="2026" step= "1" >
                        </div>
                        <div>
                            <label for="current-semester-select">Current Semester</label>
                            <select id="current-semester-select" name="curr_term" class="border p-2 rounded-md w-full" >
                                <option value="" disabled selected>Select Current Semester</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-center mt-4">
                        <button type="submit" formaction="EnrollmentComparison.php" class="bg-blue-900 text-white rounded-md p-2">Proceed</button>
                    </div>
                </div>
            </form>
        </div>
        <script>
            // Show/hide logic for additional input fields
            document.getElementById('show-student-status').addEventListener('click', function() {
                const additionalFieldsStatus = document.getElementById('additional-fields-status');
                const additionalFieldsComparison = document.getElementById('additional-fields-comparison');
                additionalFieldsStatus.style.display = additionalFieldsStatus.style.display === 'none' ? 'block' : 'none';
                additionalFieldsComparison.style.display = 'none'; // Hide the other section
            });

            document.getElementById('show-enrollment-comparison').addEventListener('click', function() {
                const additionalFieldsComparison = document.getElementById('additional-fields-comparison');
                const additionalFieldsStatus = document.getElementById('additional-fields-status');
                additionalFieldsComparison.style.display = additionalFieldsComparison.style.display === 'none' ? 'block' : 'none';
                additionalFieldsStatus.style.display = 'none'; // Hide the other section
            });

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
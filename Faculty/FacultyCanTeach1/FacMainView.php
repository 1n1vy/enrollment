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
                <li class="active">Courses Faculty Can Teach</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>FACULTY PAGE - COURSES FACULTY CAN TEACH</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form>
                <!-- Curriculum Year (Two Inputs for Range) -->
                <div class="mb-4 flex items-center space-x-2">
                    <div class="flex-1">
                        <label for="curriculum-year-from" class="block font-bold text-sm mb-2">EMPLOYEE ID</label>
                        <div class="flex items-center">
                            <input type="text" id="curriculum-year-from" placeholder="Enter" class="w-full p-2 border border-gray-300 rounded">
                        </div>

                        <label for="curriculum-year-from" class="block font-bold text-sm mb-2">LAST NAME</label>
                        <div class="flex items-center">
                            <input type="text" id="curriculum-year-from" placeholder="Enter" class="w-full p-2 border border-gray-300 rounded">
                        </div>

                        <label for="curriculum-year-from" class="block font-bold text-sm mb-2">FIRST NAME</label>
                        <div class="flex items-center">
                            <input type="text" id="curriculum-year-from" placeholder="Enter" class="w-full p-2 border border-gray-300 rounded">
                        </div>
                    </div>
                </div>
                <!-- School Offering Year (Two Inputs for Range) and Term -->
                <div class="mb-4 flex items-center space-x-2">
                    <div class="flex-1">
                        <label for="school-year-from" class="block font-bold text-sm mb-2">SCHOOL YEAR</label>
                        <div class="flex items-center">
                            <input type="text" id="school-year-from" placeholder="Enter" class="w-full p-2 border border-gray-300 rounded">
                            <span class="mx-2 font-bold">TO</span>
                            <input type="text" id="school-year-to" placeholder="Enter" class="w-full p-2 border border-gray-300 rounded">
                        </div>
                    </div>
                    <div class="flex-1">
                        <label for="term" class="block font-bold text-sm mb-2">TERM</label>
                        <select id="term" class="w-full p-2 border border-gray-300 rounded">
                            <option>Select Term</option>
                            <option>Term 1</option>
                            <option>Term 2</option>
                        </select>
                    </div>
                </div>

                <!-- Refresh Button -->
                <div class="flex justify-end">
                    <a href="FacMain.php" type="submit" style="background-color: #174069;"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Refresh
                    </a>
                </div>
            </form>
        </div>

        <div class="form-container">
            <form>
                <table>
                    <tr>
                        <td class="label">Employee Name</td>
                        <td>VAPT, CICS S.</td>
                        <td class="label">Employment Status</td>
                        <td>Casual</td>
                    </tr>
                    <tr>
                        <td class="label">College</td>
                        <td>-</td>
                        <td class="label">Employment Type</td>
                        <td>Administrative Official</td>
                    </tr>
                    <tr>
                        <td class="label">Department</td>
                        <td colspan="3">-</td>
                    </tr>
                </table>
            </form>

            <!-- Update and Print Buttons Side by Side -->
            <div class="flex justify-between items-center space-x-4 mt-4">
                <a href="#" title="Update" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <i class="bi bi-gear-fill text-lg mr-2"></i> UPDATE
                </a>
                <a href="#" title="Print" class="bg-red-600 text-white p-2 text-sm rounded-full flex items-center">
                    <i class="bi bi-printer mr-2 text-lg"></i> Print
                </a>
            </div>

            <div class="info mt-4">
                <p>Maximum Load Units/Hours: <span class="not-set">Not Set</span></p>
            </div>

            <table class="table-section">
                <thead>
                    <tr>
                        <th colspan="3">LIST OF SUBJECTS FACULTY CAN TEACH</th>
                    </tr>
                    <tr>
                        <th>SUBJECT GROUP</th>
                        <th>SUBJECT CODE</th>
                        <th>SUBJECT TITLE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" class="no-record">No record of subjects that the faculty can teach.</td>
                    </tr>
                </tbody>
            </table>

            <div class="form-container">
                <form>
                    <div class="section-title">LIST OF SUBJECTS CURRENTLY HANDLED</div>

                    <table class="table-section">
                        <thead>
                            <tr>
                                <th colspan="7">School Year: 2023 - 2024 | Term: 2nd | Current Load: 0.0</th>
                            </tr>
                            <tr>
                                <th>SUBJECT CODE</th>
                                <th>SUBJECT TITLE</th>
                                <th>LEC/LAB UNITS</th>
                                <th>SECTION</th>
                                <th>SCHEDULE</th>
                                <th>ROOM #</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Example of empty row (can be filled later) -->
                            <tr>
                                <td colspan="7" class="no-record">No subjects currently handled.</td>
                            </tr>
                        </tbody>
                    </table>
                </form>
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
                </script>
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

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px auto;
        background-color: #f4f4f4;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    td {
        padding: 10px;
        border: 1px solid #ccc;
        font-family: Arial, sans-serif;
    }

    .label {
        font-weight: bold;
        background-color: #eaeaea;
    }
</style>
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
    <title>Enrollment Comparison Page</title>
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
                <li><a href="Main1.php">Enrollees and Enrollment Comparison</a></li>
                <li class="active">Enrollment Comparison</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>STATISTICS-ENROLLMENT COMPARISON PAGE</h1>
        </section>

        <!-- Table -->
        <div class="container mx-auto mt-10" id="printableTable">
            <table class="min-w-full bg-white border border-gray-300 text-sm text-left">
                <!-- <thead>
                    <tr class="bg-gray-200">
                        <th colspan="2" class="py-3 px-6 text-left">COLLEGE: College of Informatics and Computing Studies</th>
                        <th colspan="3" class="py-3 px-6 text-center">2022 First Sem</th>
                        <th colspan="3" class="py-3 px-6 text-center">2024 First Sem</th>
                    </tr>
                    <tr class="bg-gray-100">
                        <th class="py-3 px-6 text-left">Course</th>
                        <th class="py-3 px-6 text-left">Major</th>
                        <th class="py-3 px-6 text-left">Male</th>
                        <th class="py-3 px-6 text-left">Female</th>
                        <th class="py-3 px-6 text-left">Total</th>
                        <th class="py-3 px-6 text-left">Male</th>
                        <th class="py-3 px-6 text-left">Female</th>
                        <th class="py-3 px-6 text-left">Total</th>
                    </tr>
                </thead> -->
                <!-- <tbody>
                    <tr class="border-b">
                        <td class="py-3 px-6">BSIT</td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-3 px-6">BSIS</td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-3 px-6">BSCS</td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                    </tr> -->
                    <!-- Additional rows for total -->
                    <!-- <tr class="font-bold">
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6 text-right">Total</td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                    </tr>
                    <tr class="font-bold">
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6 text-right">Grand Total</td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6"></td>
                    </tr> -->
                <!-- </tbody>
            </table> -->

            <?php
                require_once '../../Custom/Handlers/connection.php';
                require_once '../../Custom/Functions/Comparison/EnrollmentComparison.php';
                require_once '../../Custom/Functions/Comparison/EnrollmentQuery.php';

                $database = new Connection();
                $conn = $database->getConnection();

                $department_id = isset($_GET['college']) && $_GET['college'] !== '' ? (int) $_GET['college'] : 0;
                $course_id     = isset($_GET['course']) && $_GET['course'] !== '' ? (int) $_GET['course'] : 0;
                $major_id      = isset($_GET['major']) && $_GET['major'] !== '' ? (int) $_GET['major'] : 0;
                $gender        = isset($_GET['gender']) ? strtolower(trim($_GET['gender'])) : 'all';
                $year_level    = $_GET['year_level'] ?? 'all';
                $student_status = isset($_GET['student_status']) ? strtolower(trim($_GET['student_status'])) : 'all';
                $age_param     = isset($_GET['age']) && trim($_GET['age']) !== '' ? (int) trim($_GET['age']) : null;
                $prev_year     = $_GET['prev_year'] ?? '';
                $prev_term     = $_GET['prev_term'] ?? '';
                $curr_year     = $_GET['curr_year'] ?? '';
                $curr_term     = $_GET['curr_term'] ?? '';

                $prevResults = getEnrollmentCounts($conn, $prev_year, $prev_term, $gender, $department_id, $major_id);
                $currResults = getEnrollmentCounts($conn, $curr_year, $curr_term, $gender, $department_id, $major_id);

                $prevTitle = "$prev_year Term $prev_term";
                $currTitle = "$curr_year Term $curr_term";

                buildComparisonTable($prevResults, $currResults, $prevTitle, $currTitle, $conn, $department_id);
                ?>

        </div>
        <div class="flex justify-center mt-6">
            <a href="Main1.php" class="bg-blue-900 text-white rounded-md p-2">Back</a>
            <button onclick="printOnlyTable()" class="ml-4 bg-red-500 text-white rounded-md p-2">Print</button>
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

        <!-- Optional Print Function -->
        <script>
            function printOnlyTable() {
                // Get the content of the table
                var printContents = document.getElementById('printableTable').innerHTML;

                // Save the current page content
                var originalContents = document.body.innerHTML;

                // Replace the body content with the table
                document.body.innerHTML = printContents;

                // Print the page (now only the table is displayed)
                window.print();

                // Restore the original page content after printing
                document.body.innerHTML = originalContents;

                // Reload the page to restore the JavaScript functionality
                location.reload();
            }
        </script>
</body>

<style>
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

    /* 201 File Section */
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

    table,
    th,
    td {
        border: 1px solid black;
        /* Black border for all table cells */
    }

    th,
    td {
        padding: 10px;
        /* Adds padding for better readability */
        text-align: left;
    }
</style>

</html>
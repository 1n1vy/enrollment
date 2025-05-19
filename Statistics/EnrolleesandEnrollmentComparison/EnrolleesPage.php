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
    <title>Enrollees Page</title>
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
                <li class="active">No. of Enrollees</li>

            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>STATISTICS-ENROLLEES PAGE</h1>
        </section>

        <div class="container mx-auto mt-10">
            <div class="overflow-x-auto mt-9" id="printableTable">
            <?php 
                require_once '../../../capst/Custom/Handlers/connection.php';
                require_once '../../../capst/Custom/Functions/StudentBase/display.php';
                require_once '../../../capst/Custom/Functions/StudentBase/queries.php';

                $conn = new Connection();
                $mysqli = $conn->getConnection();

                // GET parameters
                $department_id   = isset($_GET['college']) ? (int) $_GET['college'] : 0;
                $course_id       = isset($_GET['course']) ? (int) $_GET['course'] : 0;
                $gender          = isset($_GET['gender']) ? strtolower(trim($_GET['gender'])) : 'all';
                $year_level      = isset($_GET['year_level']) ? trim($_GET['year_level']) : 'all';
                $student_status  = isset($_GET['student_status']) ? trim(strtolower($_GET['student_status'])) : 'all';
                $age_param       = isset($_GET['age']) && trim($_GET['age']) !== '' ? (int) trim($_GET['age']) : null;
                $syfrom          = isset($_GET['sy_from']) && trim($_GET['sy_from']) !== '' ? (int) trim($_GET['sy_from']) : null;
                $syto            = isset($_GET['sy_to']) && trim($_GET['sy_to']) !== '' ? (int) trim($_GET['sy_to']) : null;
                $semester        = isset($_GET['semester']) && trim($_GET['semester']) !== '' ? (int) trim($_GET['semester']) : null;

                // Level names
                $level_names = getLevelNamesById($mysqli, $year_level);
                $level_names = getLevelNames($mysqli, $level_names);
                $select_levels = buildSelectColumns($mysqli, $level_names, $gender);

                // Start building WHERE conditions
                $where_conditions = [];

                if ($gender !== 'all') {
                    $gender_escaped = $mysqli->real_escape_string($gender);
                    $where_conditions[] = "s.gender = '$gender_escaped'";
                }

                if ($department_id !== 0) {
                    $where_conditions[] = "d.id = $department_id";
                }

                if ($course_id !== 0) {
                    $where_conditions[] = "c.id = $course_id";
                }

                $escaped_levels = implode("','", array_map(function($v) use ($mysqli) {
                    return $mysqli->real_escape_string(trim($v));
                }, $level_names));
                $where_conditions[] = "e.level_name IN ('$escaped_levels')";

                if ($student_status !== 'all') {
                    $status_escaped = $mysqli->real_escape_string($student_status);
                    $where_conditions[] = "s.student_status = '$status_escaped'";
                }

                if ($age_param !== null) {
                    $where_conditions[] = "s.age = $age_param";
                }

                if ($syfrom !== null && $syto !== null) {
                    if ($syfrom > $syto) {
                        [$syfrom, $syto] = [$syto, $syfrom]; // auto-swap
                    }
                    $where_conditions[] = "yt.year BETWEEN $syfrom AND $syto";
                } elseif ($syfrom !== null) {
                    $where_conditions[] = "yt.year >= $syfrom";
                } elseif ($syto !== null) {
                    $where_conditions[] = "yt.year <= $syto";
                }

                // ✅ Handle Semester filter (only if not "All" / 0)
                if ($semester !== null && $semester !== 0) {
                    $where_conditions[] = "yt.term_id = $semester";
                }

                // Final WHERE clause
                $where = 'WHERE ' . implode(' AND ', $where_conditions);

                // Fetch the data
                $result = fetchStudentData($mysqli, $where, $select_levels);

                $data = [];
                $department_counts = [];

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                        $dept = $row['department'];
                        if (!isset($department_counts[$dept])) {
                            $department_counts[$dept] = 0;
                        }
                        $department_counts[$dept]++;
                    }
                }

                // Display the dynamically generated table
                displayTable($data, $department_counts, $level_names, $gender);
            ?>
            </div>
            <div class="flex justify-center mt-6">
            <a href="Main1.php" class="bg-blue-900 text-white rounded-md p-2">Back</a>
            <button onclick="printOnlyTable()" class="ml-4 bg-red-500 text-white rounded-md p-2">Print</button>
        </div>
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
        text-align: center;
    }
</style>
</body>

</html>
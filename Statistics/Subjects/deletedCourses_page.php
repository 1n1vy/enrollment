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
    <title>Deleted Courses Page</title>
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
                <li><a href="main2.php">Course</a></li>
                <li><a href="CoursesPage.php">Course</a></li>
                <li class="active">Deleted Courses</li>

            </ul>
        </nav>

        </head>
        <body>
            <section class="section-header text-sm md:text-xl">
                <h1>Deleted Courses</h1>
            </section>
            <div class="container mx-auto mt-10">
            <?php
            require_once '../../Custom/Handlers/connection.php';
            require_once 'viewDissolvedController.php';

                $conn = new Connection();
                $db = $conn->getConnection();

                $data = getDissolvedSubjects($db);

                include 'view_dissolved_table.php';
                ?>
            </div>


    <script>

        const deletedCourses = JSON.parse(localStorage.getItem('deletedCourses')) || [];

        const tableBody = document.querySelector('#deletedCoursesTable tbody');

        deletedCourses.forEach((course, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${course.college}</td>
                <td>${course.code}</td>
                <td>${course.section}</td>
                <td>${course.lecLab}</td>
                <td>${course.enrolled}</td>
                <td>${course.capacity}</td>
                <td>${course.roomCapacity}</td>
                <td>
                    <button class="recover-btn bg-green-500 text-white p-1 rounded mb-3" data-index="${index}">Recover</button>
                    <button class="delete-permanently-btn bg-red-500 text-white p-1 rounded" data-index="${index}">Delete Permanently</button>
                </td>
            `;
            tableBody.appendChild(row);
        });

        tableBody.addEventListener('click', function(e) {
            const target = e.target;
            const index = target.dataset.index;

            if (target.classList.contains('recover-btn')) {
                const recoveredCourse = deletedCourses.splice(index, 1)[0];
                localStorage.setItem('deletedCourses', JSON.stringify(deletedCourses));
                alert(`Recovered: ${recoveredCourse.code}`);
                location.reload();
            }

            if (target.classList.contains('delete-permanently-btn')) {
                deletedCourses.splice(index, 1);
                localStorage.setItem('deletedCourses', JSON.stringify(deletedCourses));
                alert('Deleted permanently.');
                location.reload();
            }
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
    
    .form-group input, .form-group select {
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
    table, th, td {
            border: 1px solid black; /* Black border for all rows and columns */
            border-collapse: collapse; /* Ensure borders don't overlap */
        }

        th, td {
            padding: 10px; /* Add padding for better readability */
            text-align: center; /* Center text within cells */
        }

        .header-row {
            background-color: #f2f2f2; /* Light background for header */
            font-weight: bold;
        }
    </style>
</html>

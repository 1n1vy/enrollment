<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../includes/auth.php';
redirectIfNotLoggedIn();

// Optionally, restrict access by role
checkRole(['Admin', 'Faculty']);

if (file_exists('../includes/db_connection.php')) {
    require_once '../includes/db_connection.php';
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
    <title>Advising Student</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
                <li><a href="#">Advising</a></li>
                <li class="active">Advise Student</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h5 class="text-light">ADVISING STUDENT</h5>
        </section>

        <div class="container mt-5">
            <form>

                <div class="mb-4">
                    <p id="student-warning" class="text-light d-flex justify-content-center border border-danger bg-danger d-none">Student not found</p>
                </div>
                <!-- Student ID -->
                <div class="mb-3">
                    <label for="student-id" class="form-label">Student ID:</label>
                    <input type="text" id="student_id" class="form-control" placeholder="Enter Student ID">
                </div>

                <!-- School Year: From and To -->
                <div class="mb-3">
                    <label for="school-year" class="form-label">School Year:</label>
                    <div class="d-flex align-items-center">
                        <input type="text" id="yearstart" class="form-control me-2" placeholder="From" readonly>
                        <span class="mx-2">-</span>
                        <input type="text" id="yearend" class="form-control ms-2" placeholder="To" readonly>
                    </div>
                </div>

                <!-- Term -->
                <div class="mb-4">
                    <label for="term" class="form-label">Term:</label>
                    <select id="term" class="form-select">
                        <option value="">Select Term</option>
                        <option value="1st">1st Semester</option>
                        <option value="2nd">2nd Semester</option>
                        <option value="3rd">3rd Semester</option>
                        <option value="Summer">Summer</option>
                    </select>
                </div>

                <!-- Proceed Button -->
                <div class="d-flex justify-content-center mb-4">
                    <button type="button" id="check-student" class="btn btn-primary px-4 py-2 shadow">Proceed</button>
                </div>

                <!-- Bottom Buttons -->
                <div class="row text-center mt-5">
                    <div class="col-md-6 mb-4">
                        <p class="text-muted fw-semibold">Can’t find Student ID?</p>
                        <a href="Student.php" class="btn btn-primary btn-lg rounded-pill px-5 shadow">Search Student</a>
                    </div>
                    <div class="col-md-6 mb-4">
                        <p class="text-muted fw-semibold">List of Subjects</p>
                        <a href="Subject.php" class="btn btn-primary btn-lg rounded-pill px-5 shadow">Search Subject</a>
                    </div>
                </div>
            </form>
        </div>
        </div>
    </div>
    <div class="border-b-4 border-black my-4"></div>

    <script src="advising.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const currentYear = new Date().getFullYear();
        document.getElementById('yearstart').value = currentYear;
        document.getElementById('yearend').value = currentYear + 1;
    });
    </script>
    <script>
        // Load navbar dynamically
        (function loadNavbar() {
            fetch('../Components/Navbar.php')
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
                        script.src = '../Components/app.js';
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
    body {
        font-family: Arial, sans-serif;
        background-color: #f7f8fa;
        margin: 0;
        padding: 0;
    }

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

    /* Form Container */
    .form-container {
        max-width: 900px;
        margin: 20px auto;
        background-color: #f4f8fc;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    /* Form Group Layout */
    .form-group {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .form-group label {
        width: 130px;
        font-weight: bold;
    }

    .form-group input {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 250px;
        margin-right: 10px;
    }

    .form-group span {
        margin-right: 10px;
        font-size: 18px;
    }

    /* Full Width for Student Preview */
    .full-width input {
        width: calc(100% - 150px);
        /* Full width minus label width */
    }

    /* Responsive */
    @media (max-width: 600px) {
        .form-group {
            flex-direction: column;
            align-items: flex-start;
        }

        .form-group input {
            width: 100%;
        }

        .bottom-btns {
            flex-direction: column;
            align-items: center;
        }

        .bottom-btn-group {
            margin-bottom: 15px;
        }
    }
</style>
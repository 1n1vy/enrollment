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
    <title>Not Enrolled Student Masterlist</title>
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
                <li><a href="#">Reports</a></li>
                <li><a href="#">Student Status Masterlist</a></li>
                <li class="active">Not Enrolled Student Masterlist</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>REPORTS- UNENROLLED STUDENT MASTERLIST</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form action="#">
                <!-- Student Status -->
                <div class="form-group">
                    <label for="student-status" class="block font-bold">STUDENT STATUS</label>
                    <input type="text" id="student-status" placeholder="Enrolled" class="border p-2 rounded-md w-full sm:w-64">
                </div>

                <!-- SY Term, To, and Semester Row -->
                <div class="flex flex-wrap gap-4 mt-4">
                    <div class="w-full sm:w-auto mb-4">
                        <label for="sy-from" class="block font-bold">SY Term</label>
                        <input type="text" id="sy-from" placeholder="2024" class="border p-2 rounded-md w-full sm:w-24">
                    </div>
                    <div class="w-full sm:w-auto mb-4">
                        <label for="sy-to" class="block font-bold">To</label>
                        <input type="text" id="sy-to" placeholder="2025" class="border p-2 rounded-md w-full sm:w-24">
                    </div>
                    <div class="w-full sm:w-auto mb-4">
                        <label for="semester" class="block font-bold">Semester</label>
                        <input type="text" id="semester" placeholder="1st Semester" class="border p-2 rounded-md w-full sm:w-32">
                    </div>
                </div>

                <!-- Show By Section -->
                <h4 class="font-bold mb-4" style="margin-top: 25px; margin-bottom: 15px; margin-left: -15px;">SHOW BY:</h4>
                <div class="form-show flex flex-col gap-4">
                    <div>
                        <label for="college" class="block font-bold">College</label>
                        <input type="text" id="college" placeholder="College of Informatics and Computing Studies" class="border p-2 rounded-md w-full">
                    </div>
                    <div>
                        <label for="course" class="block font-bold">Program</label>
                        <input type="text" id="course" placeholder="Bachelor of Science in Information Technology" class="border p-2 rounded-md w-full">
                    </div>
                </div>

                <!-- Buttons: Print and Back -->
                <div class="flex justify-between mt-6">
                    <button type="button" class="bg-blue-900 text-white rounded-md p-2">Print</button>
                    <button type="button" class="bg-blue-900 text-white rounded-md p-2">Back</button>
                </div>

                <!-- Responsive Table Section -->
                <!-- Entries Selection -->
                <div class="mt-6 flex items-center">
                    <label for="entries" class="mr-2">Show:</label>
                    <select id="entries" class="border p-2 rounded-md" onchange="updateTable()">
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>

                <!-- Responsive Table Section -->
                <div class="table-container mt-8 overflow-x-auto">
                    <table class="min-w-full border border-collapse">
                        <thead class="bg-gray-200">
                            <tr class="text-left">
                                <th class="p-2 border">Student Number</th>
                                <th class="p-2 border">Last Name</th>
                                <th class="p-2 border">First Name</th>
                                <th class="p-2 border">Middle Initial</th>
                                <th class="p-2 border">Suffix</th>
                            </tr>
                        </thead>
                        <tbody id="student-table-body">
                            <tr>
                                <td class="p-2 border">22-33333-123</td>
                                <td class="p-2 border">Heid</td>
                                <td class="p-2 border">Malia</td>
                                <td class="p-2 border">Q.</td>
                                <td class="p-2 border"></td>
                            </tr>
                            <tr>
                                <td class="p-2 border">96-78962-456</td>
                                <td class="p-2 border">Ramos</td>
                                <td class="p-2 border">Dwight</td>
                                <td class="p-2 border">P</td>
                                <td class="p-2 border">III</td>
                            </tr>
                            <!-- Additional rows can be added here -->
                        </tbody>
                    </table>
                </div>
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

            function showAdditionalFields() {
                // Display the hidden information section
                document.getElementById("additional-fields-status").style.display = "block";
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
        width: 50%;
        margin: 100px auto;
        background-color: #f4f8fc;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
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
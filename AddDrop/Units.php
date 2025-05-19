<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../includes/auth.php';
redirectIfNotLoggedIn();

// Optionally, restrict access by role
checkRole(['Admin', 'Registrar']);

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
    <title>Re-Assign units enrolled (or) Lec/Lab enrollment </title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
                <li><a href="#">Change of Subject</a></li>
                <li class="active">Re-Assign units enrolled (or) Lec/Lab enrollment </li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>CHANGE OF Re-Assign units enrolled (or) Lec/Lab enrollment </h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form action="#">
                <!-- Student ID Section with Dropdown -->
                <div class="form-group">
                    <label for="student-id">Enter Student ID</label>
                    <div class="form-row">
                        <input type="text" id="student-id" placeholder="Enter" class="short-input">
                        <!-- Dropdown for selecting the semester -->
                        <select id="semester-dropdown" class="ml-2 p-1 border rounded short-dropdown">
                            <option value="1st">1st sem</option>
                            <option value="2nd">2nd sem</option>
                        </select>
                    </div>
                </div>

                <!-- Offering SY Section -->
                <div class="form-group">
                    <label for="offering-sy">Offering SY</label>
                    <div class="form-row">
                        <input type="text" id="offering-sy" placeholder="Enter">
                        <span>to</span>
                        <input type="text" id="offering-sy-end" placeholder="Enter">
                    </div>
                </div>

                <!-- Proceed Button -->
                <div class="form-actions">
                    <button type="button" id="proceed-btn">Show List</button>
                </div>
                <!-- Table section (hidden by default) -->
                <div id="schedule-table" class="mt-6 hidden">


                    <div class="flex justify-between mb-4">
                        <p class="text-gray-700 font-semibold mr-10">Maximum units can take:#</p>

                    </div>

                    <div style="background-color: #174069;" class="text-white p-3 text-center font-bold text-xl rounded-t-md">
                        Subjects Enrolled
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-300">
                            <thead class="text-gray-700">
                                <tr>
                                    <th class="py-2 px-4 border">Subject Code</th>
                                    <th class="py-2 px-4 border">SUBJECT Title</th>
                                    <th class="py-2 px-4 border">Lec/Lab Units</th>
                                    <th class="py-2 px-4 border">Units taken</th>
                                    <th class="py-2 px-4 border">Section</th>
                                    <th class="py-2 px-4 border">ROOM #</th>
                                    <th class="py-2 px-4 border">Schedule</th>
                                    <th class="py-2 px-4 border">New Enrolled Unit</th>
                                    <th class="py-2 px-4 border">Enrolled In?</th>
                                    <th class="py-2 px-4 border">Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-gray-700">
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center">
                                        <input type="text" class="w-full text-center border border-black" value="1.0">
                                    </td>
                                    <td class="py-2 px-4 border text-center">
                                        <select class="w-full text-center border-gray-300">
                                            <option value="M 8:00AM-9:00AM">Lec</option>
                                            <option value="TH 10:00AM-12:00PM">Lab</option>
                                        </select>
                                    </td>

                                    <td class="py-2 px-4 border text-center">
                                        <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600">
                                    </td>
                            </tbody>
                            <tbody>
                                <tr class="text-gray-700">
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center">
                                        <input type="text" class="w-full text-center border border-black" value="2.0">
                                    </td>
                                    <td class="py-2 px-4 border text-center">
                                        <select class="w-full text-center border-gray-300">
                                            <option value="W 9:00AM-10:00AM">lec</option>
                                            <option value="M 8:00AM-9:00AM">Lab</option>
                                        </select>
                                    </td>

                                    <td class="py-2 px-4 border text-center">
                                        <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600">
                                    </td>
                            </tbody>
                            <tbody>
                                <tr class="text-gray-700">
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center"> </td>
                                    <td class="py-2 px-4 border text-center">
                                        <input type="text" class="w-full text-center border border-black" value="3.0">
                                    </td>
                                    <td class="py-2 px-4 border text-center">
                                        <select class="w-full text-center border-gray-300">
                                            <option value="W 9:00AM-10:00AM">Lec</option>
                                            <option value="F 2:00PM-4:00PM">Lab</option>
                                        </select>
                                    </td>

                                    <td class="py-2 px-4 border text-center">
                                        <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600">
                                    </td>
                            </tbody>
                        </table>
                        <div class="form-actions mt-4 flex justify-end">
                            <button id="save-btn" class="save-button">Save</button>
                        </div>
                    </div>
                </div>

                <div id="table-container" class="mt-10"></div>
            </form>
        </div>

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

            // Show the table when Proceed is clicked
            document.getElementById('proceed-btn').addEventListener('click', function() {
                document.getElementById('schedule-table').classList.remove('hidden');
            });
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
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .form-group input,
    .form-group select {
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    /* Adjust width for the student ID input */
    .short-input {
        width: 50%;
        /* Shorter input box for Student ID */
    }

    /* Inline form group for student ID and dropdown */
    .form-row {
        display: flex;
        align-items: center;
    }

    .short-dropdown {
        width: 10%;
        /* Shorter dropdown for semester */

    }

    /* Shorter text boxes for Offering SY */
    .form-row input {
        width: 30%;
        margin-right: 10px;
    }

    .form-row span {
        margin-right: 10px;
    }

    /* Button styles */
    .form-actions {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .form-actions button {
        padding: 12px 20px;
        font-size: 16px;
        background-color: #174069;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-actions button:hover {
        background-color: #20568B;
    }

    /* Save button in the bottom right corner */
    .save-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 12px 20px;
        font-size: 16px;
        background-color: #174069;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .bi-trash {
        font-size: 24px;
        color: black;
        transition: color 0.3s, transform 0.3s;
        /* smooth transition */
    }

    /* Hover state */
    .bi-trash:hover {
        color: red;
        /* change color on hover */
        transform: scale(1.2);
        /* slightly enlarge icon */
    }

    .save-button:hover {
        background-color: #20568B;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        .form-container {
            width: 90%;
        }

        .form-row {
            flex-direction: column;
        }

        .short-input,
        .short-dropdown {
            width: 100%;
        }

        .form-row input {
            width: 100%;
        }
    }
</style>
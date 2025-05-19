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
    <title>Drop/Withdraw</title>
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
                <li class="active">Change Schedule</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>Change Schedule</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form action="#">
                <!-- Student ID Section -->
                <div class="form-group">
                    <label for="student-id">Enter Student ID</label>
                    <div class="form-row">
                        <input type="text" id="student-id" placeholder="Enter" class="short-input">
                        <select id="semester-dropdown" class="ml-2 p-1 border rounded short-dropdown">
                            <option value="1st">1st sem</option>
                            <option value="2nd">2nd sem</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="offering-sy">Offering SY</label>
                    <div class="form-row">
                        <input type="text" id="offering-sy" placeholder="Enter">
                        <span>to</span>
                        <input type="text" id="offering-sy-end" placeholder="Enter">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" id="proceed-btn">Show List</button>
                </div>
                <div id="schedule-table" class="mt-6 hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-300">
                            <thead class="text-white" style="background-color: #20568B;">
                                <tr>
                                    <th class="py-2 px-4 border">Subject Code</th>
                                    <th class="py-2 px-4 border">SUBJECT Title</th>
                                    <th class="py-2 px-4 border">Lec Units</th>
                                    <th class="py-2 px-4 border">Lab Units</th>
                                    <th class="py-2 px-4 border">Total Units</th>
                                    <th class="py-2 px-4 border">Section</th>
                                    <th class="py-2 px-4 border">ROOM #</th>
                                    <th class="py-2 px-4 border">Schedule</th>
                                    <th class="py-2 px-4 border">Change Schedule</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-gray-700">
                                    <td class="py-2 px-4 border text-center">CIT321-18</td>
                                    <td class="py-2 px-4 border text-center">Capstone 1</td>
                                    <td class="py-2 px-4 border text-center">3.0</td>
                                    <td class="py-2 px-4 border text-center">0.0</td>
                                    <td class="py-2 px-4 border text-center">3.0</td>
                                    <td class="py-2 px-4 border text-center">4BSIT-3</td>
                                    <td class="py-2 px-4 border text-center">T.B.A</td>
                                    <td class="py-2 px-4 border text-center">W 9:00AM-10:00AM</td>
                                    <td class="py-2 px-4 border text-center">
                                        <button class="change-schedule-btn" onclick="openModal()">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-actions mt-4 flex justify-end">
                            <button id="save-btn" class="save-button">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <button id="save-btn" class="save-button">Save</button>
    </div>

    <!-- Modal Structure -->
    <div id="changeScheduleModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg w-1/2">
            <h2 class="text-lg font-semibold mb-4">Change Schedule</h2>
            <p class="mb-4">Select a new schedule for the subject.</p>
            <div class="overflow-x-auto mb-4">
                <table class="min-w-full border border-gray-300">
                    <thead class="text-white" style="background-color: #20568B;">
                        <tr>
                            <th class="py-2 px-4 border">Schedule ID</th>
                            <th class="py-2 px-4 border">Day</th>
                            <th class="py-2 px-4 border">Time</th>
                            <th class="py-2 px-4 border">Room</th>
                            <th class="py-2 px-4 border">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border">SCH001</td>
                            <td class="py-2 px-4 border">Monday</td>
                            <td class="py-2 px-4 border">9:00AM - 11:00AM</td>
                            <td class="py-2 px-4 border">Room 101</td>
                            <td class="py-2 px-4 border text-center">
                                <button class="p-2 bg-blue-500 text-white rounded">Select</button>
                            </td>
                        </tr>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border">SCH002</td>
                            <td class="py-2 px-4 border">TUESDAY</td>
                            <td class="py-2 px-4 border">9:00AM - 11:00AM</td>
                            <td class="py-2 px-4 border">Room 102</td>
                            <td class="py-2 px-4 border text-center">
                                <button class="p-2 bg-blue-500 text-white rounded">Select</button>
                            </td>
                        </tr>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border">SCH003</td>
                            <td class="py-2 px-4 border">WEDSNEDAY</td>
                            <td class="py-2 px-4 border">9:00AM - 11:00AM</td>
                            <td class="py-2 px-4 border">Room 103</td>
                            <td class="py-2 px-4 border text-center">
                                <button class="p-2 bg-blue-500 text-white rounded">Select</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end">
                <button onclick="closeModal()" class="p-2 bg-gray-500 text-white rounded mr-2">Cancel</button>
                <button class="p-2 bg-blue-500 text-white rounded">Confirm</button>
            </div>
        </div>
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

        // Open the modal
        function openModal() {
            document.getElementById('changeScheduleModal').classList.remove('hidden');
        }

        // Close the modal
        function closeModal() {
            document.getElementById('changeScheduleModal').classList.add('hidden');
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

    .bi-arrow-clockwise {
        font-size: 24px;
        color: black;
        transition: color 0.3s, transform 0.3s ease-in-out;
        /* smooth transition */
    }

    /* Hover state */
    .bi-arrow-clockwise:hover {
        color: green;
        /* change color on hover */
        transform: rotate(360deg);
        /* rotate 360 degrees */
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
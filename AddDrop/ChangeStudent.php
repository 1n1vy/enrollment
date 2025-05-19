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
    <title>Move Student</title>
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
                <li><a href="#">Change of Subject</a></li>
                <li class="active">Move Student</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>CHANGE OF SUBJECT-Move Student</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form action="#">
                <!-- Offering SY Section -->
                <div class="form-group">
                    <label for="offering-sy">Offering SY</label>
                    <div class="form-row">
                        <input type="text" id="offering-sy" placeholder="Enter">
                        <span>to</span>
                        <input type="text" id="offering-sy-end" placeholder="Enter">
                        <select id="semester-dropdown" class="ml-2 p-1 border rounded short-dropdown">
                            <option value="1st">1st sem</option>
                            <option value="2nd">2nd sem</option>
                        </select>
                    </div>
                </div>

                <!-- Proceed Button -->
                <div class="form-actions">
                    <button type="button" id="proceed-btn">Proceed</button>
                </div>

                <div id="schedule-table" class="mt-6 hidden">
                    <div class="flex space-x-4 mt-6">
                        <!-- Left Table -->
                        <div class="flex-1">
                            <!-- Section Dropdown -->
                            <div class="mb-4 form-group">
                                <label for="section-left" class="font-bold">Section:</label>
                                <select id="section-left" class="short-dropdown-small mt-2">
                                    <option>2BSIT-1</option>
                                    <option>6BSIT-7</option>
                                    <option>1BSIT-3</option>
                                </select>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full border border-Black-300">
                                    <tbody>
                                        <tr class="text-gray-700">
                                            <td class="py-2 px-4 border text-center">Room</td>
                                            <td class="py-2 px-4 border text-center">Location</td>
                                            <td class="py-2 px-4 border text-center">Schedule</td>
                                        </tr>
                                    </tbody>
                                    <tbody>
                                        <tr class="text-gray-700">
                                            <td class="py-2 px-4 border text-center">M123</td>
                                            <td class="py-2 px-4 border text-center">Main</td>
                                            <td class="py-2 px-4 border text-center">T 9:00Am-10:00Am</td>
                                        </tr>
                                        <tr class="text-gray-700">
                                            <td colspan="3" class="py-2 px-4 border text-center">Instructor 1</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Right Table -->
                        <div class="flex-1">
                            <!-- Section Dropdown -->
                            <div class="mb-4 form-group">
                                <label for="section-right" class="font-bold">Section:</label>
                                <select id="section-right" class="short-dropdown-small mt-2">
                                    <option>2BSIT-1</option>
                                    <option>6BSIT-7</option>
                                    <option>1BSIT-3</option>
                                </select>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full border border-black-300">
                                    <tbody>
                                        <tr class="text-gray-700">
                                            <td class="py-2 px-4 border text-center">Room</td>
                                            <td class="py-2 px-4 border text-center">Location</td>
                                            <td class="py-2 px-4 border text-center">Schedule</td>
                                        </tr>
                                    </tbody>
                                    <tbody>
                                        <tr class="text-gray-700">
                                            <td class="py-2 px-4 border text-center">B235</td>
                                            <td class="py-2 px-4 border text-center">Building B</td>
                                            <td class="py-2 px-4 border text-center">F 8:00Am-9:00Am</td>
                                        </tr>
                                        <tr class="text-gray-700">
                                            <td colspan="3" class="py-2 px-4 border text-center">Instructor 1</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <!-- Conflict Check Section -->
                    <div class="conflict-check-container mt-6 p-4 border rounded bg-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <input type="checkbox" id="bypass-conflict-check" class="mr-2">
                                <label for="bypass-conflict-check" class="text-sm">Click to bypass conflict check (NOTE: It is strongly recommended not to bypass conflict check)</label>
                            </div>
                            <button id="proceed-students-btn" class="px-4 py-2 bg-gray-400 text-white rounded">PROCEED</button>
                            <span class="text-sm">Click to show list of students under the specified subjects</span>
                        </div>

                        <div class="mt-2">
                            <p class="text-sm font-bold">NOTE:</p>
                            <ul class="text-xs ml-4">
                                <li>1. Students in <span class="text-red-500 font-bold">RED</span> are having conflict and should not be moved.</li>
                                <li>2. If all students are moved, remove faculty loaded to that section.</li>
                                <li>3. Students having final grades will not be moved.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- List of Students Table -->
                    <div class="student-list-container mt-4">
                        <div class="flex justify-between">
                            <!-- Left Table -->
                            <div class="flex-1 mr-4">
                                <table class="min-w-full border border-gray-300">
                                    <thead class="bg-gray-100">
                                        <tr class="text-center">
                                            <th colspan="3" class="py-2 px-4 border">LIST OF STUDENTS UNDER COURSE PROGRAM 1</th>
                                        </tr>
                                        <tr class="text-center">
                                            <th colspan="3" class="py-2 px-4 border">TOTAL NO. OF STUDENTS: 1</th>
                                        </tr>
                                        <tr class="text-gray-700">
                                            <th class="py-2 px-4 border">STUDENT ID</th>
                                            <th class="py-2 px-4 border">NAME</th>
                                            <th class="py-2 px-4 border">MOVE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-gray-700 text-center">
                                            <td class="py-2 px-4 border">11-22222-333</td>
                                            <td class="py-2 px-4 border">Name 1</td>
                                            <td class="py-2 px-4 border"><input type="checkbox"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Right Table -->
                            <div class="flex-1">
                                <table class="min-w-full border border-gray-300">
                                    <thead class="bg-gray-100">
                                        <tr class="text-center">
                                            <th colspan="3" class="py-2 px-4 border">LIST OF STUDENTS UNDER COURSE PROGRAM 1</th>
                                        </tr>
                                        <tr class="text-center">
                                            <th colspan="3" class="py-2 px-4 border">TOTAL NO. OF STUDENTS: 1</th>
                                        </tr>
                                        <tr class="text-gray-700">
                                            <th class="py-2 px-4 border">STUDENT ID</th>
                                            <th class="py-2 px-4 border">NAME</th>
                                            <th class="py-2 px-4 border">MOVE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-gray-700 text-center">
                                            <td class="py-2 px-4 border">11-22222-333</td>
                                            <td class="py-2 px-4 border">Name 1</td>
                                            <td class="py-2 px-4 border"><input type="checkbox"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="form-actions mt-4 flex justify-end">
                                    <button id="save-btn" class="save-button">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

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

    .form-row {
        display: flex;
        align-items: center;
    }

    .short-dropdown {
        width: 150px;
        margin-right: 10px;
    }

    .short-dropdown-small {
        width: 200px;
        /* Adjusted width to make it shorter */
        margin-right: 100px;
    }

    .form-row input {
        width: 30%;
        margin-right: 10px;
    }

    .dropdown {
        width: 80%;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
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

    table th {
        text-align: center;
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

    .save-button:hover {
        background-color: #20568B;
    }

    .bi-plus-square-fill {
        font-size: 24px;
        color: black;
        transition: color 0.3s, transform 0.3s;
    }

    .bi-plus-square-fill:hover {
        color: blue;
        transform: rotate(90deg);
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
        .short-dropdown,
        .short-dropdown-small,
        .dropdown {
            width: 100%;
        }

        .form-row input {
            width: 100%;
        }
    }
</style>
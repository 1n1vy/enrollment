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
    <title>Search Subject</title>
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
                <li><a href="#">Advising</a></li>
                <li class="active">Advise Student</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>Search Subject</h1>
        </section>

        <div class="form-container">
            <form>
                <div class="flex flex-col md:flex-row md:items-center space-x-4 mb-4">
                    <!-- School year row -->
                    <div class="flex items-center space-x-2">
                        <label class="text-gray-700 text-sm font-bold mb-1">School year:</label>
                        <input type="text" id="start-year" name="start-year" value="2023" maxlength="4" class="w-16 p-2 border border-gray-300 rounded">
                        <span>to</span>
                        <input type="text" id="end-year" name="end-year" value="2024" maxlength="4" class="w-16 p-2 border border-gray-300 rounded">
                    </div>

                    <!-- Term dropdown -->
                    <div class="flex items-center space-x-2">
                        <label for="term" class="text-gray-700 text-sm font-bold mb-1">Term:</label>
                        <select id="term" name="term" class="w-full p-2 border border-gray-300 rounded">
                            <option value="2nd-sem">2nd Sem</option>
                        </select>
                    </div>
                </div>
                <!-- Subject code input -->
                <div class="mb-4">
                    <label for="subject-code" class="block text-gray-700 text-sm font-bold mb-1">Subject Code:</label>
                    <input type="text" id="subject-code" name="subject-code" placeholder="(enter subject code to scroll the list)" class="w-full p-2 border border-gray-300 rounded">
                </div>

                <!-- Subject 1 dropdown -->
                <div class="mb-10">
                    <label for="subject-1" class="block text-gray-700 text-sm font-bold mb-1">Subject 1:</label>
                    <select id="subject-1" name="subject-1" class="w-full p-2 border border-gray-300 rounded">
                        <option value="subject1">Subject 1</option>
                        <!-- Add more subjects here -->
                    </select>
                </div>

                <!-- Submit button -->
                <div class="bottom-btn-group text-center">
                    <a href="#" id="proceed-btn" style="background-color: #174069;"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 shadow-lg rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Search Subject
                    </a>
                </div>
            </form>
            </form>
            <!-- Table section (hidden by default) -->
            <div id="schedule-table" class="mt-6 hidden">

                <p class="text-gray-700 font-semibold mb-2">Total Schedules Found: 1</p>

                <div style="background-color: #174069;" class="text-white p-3 text-center font-bold text-xl rounded-t-md">
                    Final Schedule of Class
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300">
                        <thead class="text-gray-700">
                            <tr>
                                <th class="py-2 px-4 border">OFFERED BY</th>
                                <th class="py-2 px-4 border">SUBJECT CODE</th>
                                <th class="py-2 px-4 border">DESCRIPTION</th>
                                <th class="py-2 px-4 border">TOTAL UNITS</th>
                                <th class="py-2 px-4 border">SECTION</th>
                                <th class="py-2 px-4 border">SCHEDULE</th>
                                <th class="py-2 px-4 border">ROOM #</th>
                                <th class="py-2 px-4 border"># ENROLLED</th>
                                <th class="py-2 px-4 border">MAX CAPACITY</th>
                                <th class="py-2 px-4 border">IS RESERVED?</th>
                                <th class="py-2 px-4 border">IS CLOSED?</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-gray-700">
                                <td class="py-2 px-4 border text-center">COAGRI</td>
                                <td class="py-2 px-4 border text-center">AGRES325-18</td>
                                <td class="py-2 px-4 border text-center">Thesis 2 - Experimental</td>
                                <td class="py-2 px-4 border text-center">2.0</td>
                                <td class="py-2 px-4 border text-center">3-AGRI</td>
                                <td class="py-2 px-4 border text-center">F 2:00PM-4:00PM</td>
                                <td class="py-2 px-4 border text-center">T.B.A</td>
                                <td class="py-2 px-4 border text-center">19</td>
                                <td class="py-2 px-4 border text-center">40</td>
                                <td class="py-2 px-4 border text-center">y</td> <!-- Value for "Is Reserved?" -->
                                <td class="py-2 px-4 border text-center"></td> <!-- Value for "Is Closed?" (blank) -->
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <div class="border-b-4 border-black my-4"></div>

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

            document.getElementById('proceed-btn').addEventListener('click', function() {
                // Show the schedule table on proceed click
                document.getElementById('schedule-table').classList.remove('hidden');
            });
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
        max-width: 1300px;
        margin: 20px auto;
        background-color: #f4f8fc;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
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
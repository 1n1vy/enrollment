<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../../includes/auth.php';
redirectIfNotLoggedIn();

// Optionally, restrict access by role
checkRole(['Admin', 'Faculty']);

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
    <div class="main-content p-4" id="mainContent">
        <nav aria-label="breadcrumb" class="breadcrumb-nav mb-4">
            <ul class="breadcrumb flex space-x-2 text-sm text-gray-700">
                <li><a href="#" class="hover:text-blue-600">Home</a></li>
                <li><a href="#" class="hover:text-blue-600">Enrollment</a></li>
                <li><a href="#" class="hover:text-blue-600">Faculty</a></li>
                <li class="active">Reports | Final Schedule of Classes</li>
            </ul>
        </nav>

        <section class="section-header text-center bg-blue-800 text-white py-4 mb-8 rounded-md shadow-md">
            <h1 class="text-xl md:text-2xl font-semibold">FACULTY PAGE - REPORTS FINAL SCHEDULE OF CLASSES</h1>
        </section>

        <!-- Form container -->
        <div class="form-container p-6 bg-gray-100 rounded-lg shadow-md mb-8 max-w-4xl mx-auto">
            <form class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="school-year" class="block font-semibold text-gray-700">School Year</label>
                    <div class="flex space-x-2">
                        <input type="text" id="school-year-start" placeholder="Enter" class="input-field" />
                        <span class="self-center">To</span>
                        <input type="text" id="school-year-end" placeholder="Enter" class="input-field" />
                    </div>
                </div>

                <div>
                    <label for="term" class="block font-semibold text-gray-700">Term</label>
                    <select id="term" class="input-field">
                        <option value="1st">1st</option>
                        <option value="2nd" selected>2nd</option>
                        <option value="3rd">3rd</option>
                    </select>
                </div>

                <div>
                    <label for="subject-code" class="block font-semibold text-gray-700">Subject Code</label>
                    <input type="text" id="subject-code" placeholder="Enter subject code" class="input-field" />
                </div>

                <div class="md:col-span-3">
                    <label for="subject-list" class="block font-semibold text-gray-700">Select Subject</label>
                    <select id="subject-list" class="input-field">
                        <option value="CCC211-18">CCC211-18 (Information Management 1)</option>
                    </select>
                </div>

                <!-- Button -->
                <div class="md:col-span-3 flex justify-center">
                    <a href="Final1.php" type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 rounded-md"
                        style="background-color: #174069;">
                        Refresh
                    </a>
                </div>
            </form>
        </div>

        <!-- Separator -->
        <div class="border-b-2 border-gray-400 my-6"></div>

        <!-- Table container -->
        <div class="table-container bg-white p-6 rounded-lg shadow-md max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Final Schedule of Classes</h2>
                <a href="#" class="bg-red-600 text-white p-2 text-xs rounded-full flex items-center">
                    <i class="bi bi-printer mr-2"></i> Print
                </a>
            </div>

            <table class="w-full border-collapse">
                <thead>
                    <tr class="text-white text-left" style="background-color: #174069;">
                        <th class="py-2 px-4 font-semibold">SUBJECT CODE</th>
                        <th class="py-2 px-4 font-semibold">DESCRIPTION</th>
                        <th class="py-2 px-4 font-semibold">SECTION</th>
                        <th class="py-2 px-4 font-semibold">TIME :: DAY</th>
                        <th class="py-2 px-4 font-semibold">ROOM #</th>
                        <th class="py-2 px-4 font-semibold">TEACHER</th>
                        <th class="py-2 px-4 font-semibold">NO. OF UNITS</th>
                        <th class="py-2 px-4 font-semibold">TOTAL NO. OF STUDS.</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="odd:bg-gray-50 even:bg-white">
                        <td class="py-3 px-4 border-b border-gray-200">CCC211-18</td>
                        <td class="py-3 px-4 border-b border-gray-200">Information Management 1</td>
                        <td class="py-3 px-4 border-b border-gray-200">1CICS_Pet</td>
                        <td class="py-3 px-4 border-b border-gray-200">F 4:00PM-6:00PM</td>
                        <td class="py-3 px-4 border-b border-gray-200">B232</td>
                        <td class="py-3 px-4 border-b border-gray-200">PASIOL, YUMIE M.</td>
                        <td class="py-3 px-4 border-b border-gray-200">2.0</td>
                        <td class="py-3 px-4 border-b border-gray-200">35</td>
                    </tr>
                    <tr class="odd:bg-gray-50 even:bg-white">
                        <td class="py-3 px-4 border-b border-gray-200">CCC211-18</td>
                        <td class="py-3 px-4 border-b border-gray-200">Information Management 1</td>
                        <td class="py-3 px-4 border-b border-gray-200">1CICS_Pet2</td>
                        <td class="py-3 px-4 border-b border-gray-200">M 8:00AM-10:00AM</td>
                        <td class="py-3 px-4 border-b border-gray-200">B233</td>
                        <td class="py-3 px-4 border-b border-gray-200">PASIOL, YUMIE M.</td>
                        <td class="py-3 px-4 border-b border-gray-200">2.0</td>
                        <td class="py-3 px-4 border-b border-gray-200">29</td>
                    </tr>
                </tbody>
            </table>

            <div class="text-right text-gray-600 font-semibold mt-4">
                Total Schedules Found: 2
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
        </script>
    </div>
    <div class="fixed bottom-6 right-6">
        <a href="MainReports.php" type="submit" style="background-color: #aaa;" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            Back
        </a>
    </div>
</body>

</html>

<!-- CSS styling -->
<style scoped>
    .breadcrumb-nav .breadcrumb li::after {
        content: ">";
        margin-left: 10px;
        color: #174069;
    }

    .breadcrumb-nav .breadcrumb li:last-child::after {
        content: "";
    }

    .breadcrumb li.active {
        color: orange;
        pointer-events: none;
    }

    /* Input field styling */
    .input-field {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        transition: border-color 0.3s;
    }

    .input-field:focus {
        border-color: #174069;
    }

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

    .buttons {
        text-align: center;
        margin-top: 20px;
    }

    .buttons button {
        background-color: #0056b3;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .buttons button:hover {
        background-color: #004494;
    }
</style>
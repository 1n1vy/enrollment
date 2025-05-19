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
    <div class="main-content" id="mainContent">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Enrollment</a></li>
                <li><a href="#">Faculty</a></li>
                <li class="active">Reports | Display Schedule per Section</li>
            </ul>

        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>FACULTY PAGE - CLASS TIME SCHEDULE</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form>
                <div class="mb-4 flex items-center space-x-2">
                    <div class="flex-1">
                        <label for="school-year-start" class="block font-bold text-sm mb-2">School Year</label>
                        <div class="flex items-center">
                            <input type="text" id="school-year-start" name="school-year-start" placeholder="Enter"
                                class="w-full p-2 border border-gray-300 rounded">
                            <span class="mx-2 font-bold">to</span>
                            <input type="text" id="school-year-end" name="school-year-end" placeholder="Enter"
                                class="w-full p-2 border border-gray-300 rounded">
                        </div>
                    </div>
                    <div class="flex-1">
                        <label for="term" class="block font-bold text-sm mb-2">Term</label>
                        <select id="term" name="term" class="w-full p-2 border border-gray-300 rounded">
                            <option value="2nd-sem">2nd Sem</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4 flex items-center space-x-2">
                    <div class="flex-1">
                        <label for="course" class="block font-bold text-sm mb-2">Course</label>
                        <select id="course" name="course" class="w-full p-2 border border-gray-300 rounded">
                            <option value="all">All</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="major" class="block font-bold text-sm mb-2">Major</label>
                        <select id="major" name="major" class="w-full p-2 border border-gray-300 rounded">
                            <option value="all">All</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4 flex items-center space-x-2">
                    <div class="flex-1">
                        <label for="yearlevel" class="block font-bold text-sm mb-2">Year Level</label>
                        <select id="yearlevel" name="yearlevel" class="w-full p-2 border border-gray-300 rounded">
                            <option value="all">Select</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="currentyear" class="block font-bold text-sm mb-2">Current Year</label>
                        <select id="currentyear" name="currentyear" class="w-full p-2 border border-gray-300 rounded">
                            <option value="all">All</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end">
                    <a href="Display4.php" type="submit" style="background-color: #174069;"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Back
                    </a>
                </div>
            </form>
        </div>

        <div class="border-b-4 border-black my-4"></div>

        <div class="form-container">
            <h1 class="text-lg font-bold mb-2">Subject Offerings</h1>
            <h2 class="text-md font-semibold mb-4">2nd Semester, SY 2023-2024</h2>
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead class="bg-#174069">
                    <tr>
                        <th class="border border-blue-300 px-4 py-2">Count</th>
                        <th class="border border-gray-300 px-4 py-2">Select</th>
                        <th class="border border-gray-300 px-4 py-2">Print One</th>
                        <th class="border border-gray-300 px-4 py-2">View One (MWF)</th>
                        <th class="border border-gray-300 px-4 py-2">View One</th>
                        <th class="border border-gray-300 px-4 py-2">Section</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">1</td>
                        <td class="border border-gray-300 px-4 py-2"><input type="checkbox"></td>
                        <td class="border border-gray-300 px-4 py-2"><button class="bg-red-500 text-white px-3 py-1 rounded">PRINT</button></td>
                        <td class="border border-gray-300 px-4 py-2"><button class="bg-green-500 text-white px-3 py-1 rounded">VIEW</button></td>
                        <td class="border border-gray-300 px-4 py-2"><button class="bg-green-500 text-white px-3 py-1 rounded">VIEW</button></td>
                        <td class="border border-gray-300 px-4 py-2">12ECE-1 (STEM)</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">2</td>
                        <td class="border border-gray-300 px-4 py-2"><input type="checkbox"></td>
                        <td class="border border-gray-300 px-4 py-2"><button class="bg-red-500 text-white px-3 py-1 rounded">PRINT</button></td>
                        <td class="border border-gray-300 px-4 py-2"><button class="bg-green-500 text-white px-3 py-1 rounded">VIEW</button></td>
                        <td class="border border-gray-300 px-4 py-2"><button class="bg-green-500 text-white px-3 py-1 rounded">VIEW</button></td>
                        <td class="border border-gray-300 px-4 py-2">12EE-1 (STEM)</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">3</td>
                        <td class="border border-gray-300 px-4 py-2"><input type="checkbox"></td>
                        <td class="border border-gray-300 px-4 py-2"><button class="bg-red-500 text-white px-3 py-1 rounded">PRINT</button></td>
                        <td class="border border-gray-300 px-4 py-2"><button class="bg-green-500 text-white px-3 py-1 rounded">VIEW</button></td>
                        <td class="border border-gray-300 px-4 py-2"><button class="bg-green-500 text-white px-3 py-1 rounded">VIEW</button></td>
                        <td class="border border-gray-300 px-4 py-2">1-AGRI</td>
                    </tr>
                </tbody>
            </table>
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

    .form-group input,
    .form-group select {
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

    body {
        font-family: Arial, sans-serif;
        background-color: #e8f0f8;
        margin: 0;
        padding: 20px;
    }

    .container {
        width: 80%;
        margin: 0 auto;
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .form-group label {
        width: 20%;
        text-align: right;
        margin-right: 10px;
        font-weight: bold;
    }

    .form-group input,
    .form-group select {
        width: 70%;
        padding: 5px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    .form-group input[type="text"] {
        width: 40%;
    }

    .form-group .inline-inputs {
        display: flex;
        gap: 10px;
        width: 70%;
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

    h1,
    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background-color: #007bff;
        color: #fff;
    }

    thead th {
        padding: 15px;
        text-align: left;
    }

    tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tbody td {
        padding: 15px;
        text-align: center;
    }

    .print-btn,
    .view-btn {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 15px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .print-btn:hover,
    .view-btn:hover {
        background-color: #0056b3;
    }

    input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    @media screen and (max-width: 768px) {
        table {
            display: block;
            overflow-x: auto;
        }
    }
</style>
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
                <li class="active">Reports | List of Subjects not yet Assigned to Faculty</li>
            </ul>

        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>FACULTY PAGE - REPORTS LOADING/SCHEDULING</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form>
                <!-- School Year and Term -->
                <div class="form-group">
                    <label for="school-year">School Year:</label>
                    <div class="inline-inputs">
                        <input type="text" id="school-year-start" name="school-year-start" placeholder="Enter">
                        <span>to</span>
                        <input type="text" id="school-year-end" name="school-year-end" placeholder="Enter">
                        <select id="term" name="term">
                            <option value="2nd-sem">2nd Sem</option>
                            <!-- You can add more terms here -->
                        </select>
                    </div>
                </div>

                <label for="term">Term:</label>
                <select id="term">
                    <option value="1st">1st</option>
                    <option value="2nd">2nd</option>
                    <option value="summer">Summer</option>
                </select>

                <div class="checkbox-container">
                    <input type="checkbox" id="reserved-international">
                    <label for="reserved-international">Check to view only the Reserved/International Subjects</label>
                </div>

                <label for="college">College:</label>
                <select id="college">
                    <option value="medical">Center for Medical and Allied Health Sciences</option>
                    <!-- Add more options as needed -->
                </select>

                <label for="course">Course:</label>
                <select id="course">
                    <option>Select a Course</option>
                    <!-- Add course options here -->
                </select>

                <label for="major">Major:</label>
                <select id="major">
                    <option>All</option>
                    <!-- Add major options if needed -->
                </select>

                <!-- Refresh Button -->
                <div class="flex justify-end">
                    <a href="ListSubs1.php" type="submit" style="background-color: #174069;"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Refresh
                    </a>
                </div>
            </form>
        </div>

        <div class="border-b-4 border-black my-4"></div>

        <div class="container">
            <div class="header">
                LIST OF SUBJECTS NOT YET ASSIGNED TO FACULTIES
            </div>
            <div class="total-sections">Total Sections Found: 10</div>

            <div class="flex items-center justify-start">
                <a href=""
                    class="bg-red-600 text-white p-1 text-xs rounded-full flex items-center print-icon">
                    <i class="bi bi-printer mr-2 text-sm"></i>
                    Print
                </a>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>SUBJECT CODE</th>
                            <th>SUBJECT TITLE</th>
                            <th>SECTION</th>
                            <th>SCHEDULE (Days::Time)</th>
                            <th>ROOM #</th>
                            <th>(LEC/LAB)</th>
                            <th>TOTAL UNITS</th>
                            <th>TOTAL NO. OF STUDS.</th>
                            <th>COLLEGE OFFERING</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>NSTPCWTS2</td>
                            <td>Civic Welfare Training Service 2</td>
                            <td>1CMT-1</td>
                            <td>SAT 8:00AM-11:00AM</td>
                            <td>MField</td>
                            <td>LEC</td>
                            <td>3</td>
                            <td>0</td>
                            <td>College of Medical Technology</td>
                        </tr>
                        <tr>
                            <td>NSTPCWTS2</td>
                            <td>Civic Welfare Training Service 2</td>
                            <td>1CMT-2</td>
                            <td>SAT 8:00AM-11:00AM</td>
                            <td>MField</td>
                            <td>LEC</td>
                            <td>3</td>
                            <td>0</td>
                            <td>College of Medical Technology</td>
                        </tr>
                        <!-- Add more rows as needed -->
                        <tr>
                            <td>NSTP2_Varsity1</td>
                            <td>Civic Welfare Training Service 2</td>
                            <td>NSTP2_Varsity1</td>
                            <td>SAT 8:00AM-12:00PM</td>
                            <td>MField</td>
                            <td>LEC</td>
                            <td>3</td>
                            <td>42</td>
                            <td>National Service Training Program</td>
                        </tr>
                    </tbody>
                </table>
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

    h2 {
        color: #1e88e5;
        text-align: center;
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-weight: bold;
        color: #333;
        margin: 10px 0 5px;
    }

    select,
    input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border-radius: 4px;
        border: 1px solid #ccc;
        font-size: 16px;
    }

    .checkbox-container {
        display: flex;
        align-items: center;
        margin-top: 10px;
        font-size: 14px;
        color: #333;
    }

    .checkbox-container input {
        margin-right: 10px;
    }

    .buttons {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
    }

    .buttons button {
        background-color: #1e88e5;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .buttons button:hover {
        background-color: #1565c0;
    }

    .back-link {
        color: #1e88e5;
        text-decoration: none;
        font-size: 14px;
        display: inline-block;
        margin-bottom: 10px;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    th,
    td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ccc;
    }

    th {
        background-color: #1e88e5;
        color: white;
    }

    td {
        background-color: #f9f9f9;
    }

    .total-sections {
        font-weight: bold;
        padding: 10px;
        text-align: right;
        color: #333;
    }

    .print-link {
        text-align: right;
        padding: 10px;
        color: #1e88e5;
        text-decoration: none;
        font-size: 14px;
        display: inline-block;
    }

    .print-link:hover {
        text-decoration: underline;
    }

    .table-container {
        overflow-x: auto;
        padding: 20px;
    }

    .container {
        width: 100%;
        max-width: 1200px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .header {
        background-color: #1e88e5;
        color: white;
        padding: 10px;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
    }
</style>
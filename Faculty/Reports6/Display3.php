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
                <h2>Select Course and Section</h2>

                <div class="form-group">
                    <label for="sy-term">SY-Term:</label>
                    <input type="text" id="sy-start" placeholder="Enter" />
                    <input type="text" id="sy-end" placeholder="Enter" />
                    <select id="semester">
                        <option value="1st Sem">1st Sem</option>
                        <option value="2nd Sem" selected>2nd Sem</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="course">Course:</label>
                    <select id="course">
                        <option value="">Select Any</option>
                        <option value="BSIT">BSIT</option>
                        <option value="BSA">BSA</option>
                        <option value="AGRI">AGRI</option>
                        <!-- Add more courses as needed -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="year">Year:</label>
                    <select id="year">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="section-name">Section Name (Optional):</label>
                    <input type="text" id="section-name" placeholder="Enter Section Name">
                </div>

                <!-- Refresh Button -->
                <div class="flex justify-center">
                    <a href="Display2.php" type="submit" style="background-color: #174069;"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Refresh
                    </a>
                </div>

                <!-- Section List and Print Buttons -->
                <table>
                    <thead>
                        <tr>
                            <th style="background-color: #174069; color:whitesmoke;">Section</th>
                            <th style="background-color: #174069; color:whitesmoke;">Select</th>
                            <th style="background-color: #174069; color:whitesmoke;">Print</th>
                        </tr>
                    </thead>
                    <tbody id="sections-list">
                        <tr>
                            <td>1-AGRI</td>
                            <td><input type="checkbox"></td>
                            <td><button onclick="printSection('1-AGRI')">Print</button></td>
                        </tr>
                        <tr>
                            <td>1-AR-1</td>
                            <td><input type="checkbox"></td>
                            <td><button onclick="printSection('1-AR-1')">Print</button></td>
                        </tr>
                        <tr>
                            <td>1-BSIT-1</td>
                            <td><input type="checkbox"></td>
                            <td><button onclick="printSection('1-BSIT-1')">Print</button></td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>

                <div class="print-buttons">
                    <button style="background-color: #174069; color: whitesmoke;" onclick="printAll()">Print All</button>
                    <button style="background-color: #174069; color: whitesmoke;" onclick="printSelected()">Print Selected</button>
                </div>
        </div>

        </form>

        <div class="border-b-4 border-black my-4"></div>

        <div class="fixed bottom-6 right-6">
            <a href="Display2.php" type="submit" style="background-color: #aaa;" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Back
            </a>
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

            function proceed() {
                const syStart = document.getElementById('sy-start').value;
                const syEnd = document.getElementById('sy-end').value;
                const semester = document.getElementById('semester').value;
                const course = document.getElementById('course').value;
                const year = document.getElementById('year').value;
                const sectionName = document.getElementById('section-name').value;

                console.log('Proceed clicked');
                console.log('SY-Term:', syStart, 'to', syEnd, semester);
                console.log('Course:', course);
                console.log('Year:', year);
                console.log('Section Name:', sectionName);
            }

            function printSection(section) {
                console.log('Printing section:', section);
            }

            function printAll() {
                console.log('Printing all sections');
            }

            function printSelected() {
                const selectedSections = [];
                const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
                checkboxes.forEach((checkbox, index) => {
                    const section = checkbox.parentElement.previousElementSibling.textContent;
                    selectedSections.push(section);
                });
                console.log('Printing selected sections:', selectedSections);
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

    select,
    input[type="text"],
    input[type="number"],
    button {
        padding: 8px;
        width: 100%;
        margin-bottom: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .print-buttons {
        text-align: right;
        margin-top: 10px;
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

    /* Button styles */
    #proceed-button {
        background-color: #007bff;
        transition: background-color 0.3s ease;
    }

    #proceed-button:hover {
        background-color: #0056b3;
    }

    /* Print buttons */
    .bg-red-500 {
        background-color: #dc2626;
    }

    .bg-red-500:hover {
        background-color: #b91c1c;
    }

    /* Table styles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table th,
    table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    table th {
        background-color: #f4f4f4;
        font-weight: bold;
    }
</style>
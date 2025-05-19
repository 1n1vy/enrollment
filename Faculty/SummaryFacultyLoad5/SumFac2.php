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
                <li class="active">Summary Faculty Load | Per College/Department</li>
            </ul>

        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>FACULTY PAGE - SUMMARY FACULTY LOAD (PER COLLEGE/DEPARTMENT)</h1>
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

                <!-- College -->
                <div class="form-group">
                    <label for="college">College:</label>
                    <select id="college" name="college">
                        <option value="all">All</option>
                        <!-- Add college options here -->
                    </select>
                </div>

                <!-- Offices -->
                <div class="form-group">
                    <label for="offices">Offices:</label>
                    <select id="offices" name="offices">
                        <option value="all">All</option>
                        <!-- Add office options here -->
                    </select>
                </div>

                <!-- Employment Status -->
                <div class="form-group">
                    <label for="employment-status">Emp. Status:</label>
                    <select id="employment-status" name="employment-status">
                        <option value="all">All</option>
                        <!-- Add employment status options here -->
                    </select>
                </div>

                <!-- Sorting -->
                <div class="form-group">
                    <label for="sort">Sort:</label>
                    <select id="sort1" name="sort1">
                        <option value="na">N/A</option>
                    </select>
                    <select id="order1" name="order1">
                        <option value="ascending">Ascending</option>
                        <option value="descending">Descending</option>
                    </select>

                    <select id="sort2" name="sort2">
                        <option value="na">N/A</option>
                    </select>
                    <select id="order2" name="order2">
                        <option value="ascending">Ascending</option>
                        <option value="descending">Descending</option>
                    </select>

                    <select id="sort3" name="sort3">
                        <option value="na">N/A</option>
                    </select>
                    <select id="order3" name="order3">
                        <option value="ascending">Ascending</option>
                        <option value="descending">Descending</option>
                    </select>
                </div>

                <!-- Print and Proceed Buttons -->
                <div class="flex items-center justify-start">
                    <a href="#" class="bg-red-600 text-white p-1 text-sm rounded-full flex items-center print-icon">
                        <i class="bi bi-printer mr-2 text-xl"></i>
                        Print
                    </a>
                </div>

                <div class="flex items-center justify-end">
                    <a href="#" type="submit" style="background-color: #174069;"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Proceed
                    </a>
                </div>

                <!-- Table Preview Section -->
                <div class="table-preview">
                    <h3>NEW ERA UNIVERSITY</h3>
                    <p>No. 9 Central Ave, New Era, Quezon City</p>
                    <p>Second Semester, 2023-2024</p>

                    <table>
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>Total Load Units</th>
                                <th>Total Load Hour</th>
                                <th>Subjects Handled</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>***</td>
                                <td>***</td>
                                <td>***</td>
                                <td>***</td>
                                <td>***</td>
                            </tr>
                            <tr>
                                <td>***</td>
                                <td>***</td>
                                <td>***</td>
                                <td>***</td>
                                <td>***</td>
                            </tr>
                            <!-- Add more rows as necessary -->
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
    </form>
    </div>




    <div class="border-b-4 border-black my-4"></div>

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
        <a href="SumFac1.php" type="submit" style="background-color: #aaa;" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
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

    #print-button {
        background-color: red;
        margin-right: 20px;
    }

    /* Table Preview Section */
    .table-preview {
        margin-top: 40px;
        border: 1px solid #ccc;
        padding: 20px;
        text-align: center;
    }

    .table-preview table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-preview th,
    .table-preview td {
        border: 1px solid #000;
        padding: 8px;
    }

    .table-preview th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
</style>
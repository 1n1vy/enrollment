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
    <title>Summary from Previous School</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
                <li><a href="#">Student from Previous School</a></li>
                <li class="active">Summary from Previous School</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>REPORTS - SUMMARY FROM PREVIOUS SCHOOL</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <div class="flex flex-wrap gap-4 mt-4">
                <div class="w-full sm:w-auto mb-4">
                    <label class="block font-bold">SY Term</label>
                    <div class="flex items-center">
                        <span class="w-full p-2 border border-gray-300 rounded">2024</span>
                        <span class="mx-2 font-bold">TO</span>
                        <span class="w-full p-2 border border-gray-300 rounded">2025</span>
                    </div>
                </div>
                <div class="w-full sm:w-auto mb-4">
                    <label class="block font-bold">Semester</label>
                    <div class="border p-2 rounded-md w-full sm:w-32">1st Semester</div>
                </div>
            </div>

            <!-- Show By Section -->
            <h4 class="font-bold mb-4 mt-6">SHOW BY:</h4>
            <div class="form-show flex flex-col gap-4">
                <div>
                    <label class="block font-bold">College</label>
                    <div class="border p-2 rounded-md w-full">College of Informatics and Computing Studies</div>
                </div>


            </div>

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
                            <th class="p-2 border">SCHOOLS</th>
                            <th class="p-2 border">BSIT</th>
                            <th class="p-2 border">BSCS</th>
                            <th class="p-2 border">BSIS</th>
                            <th class="p-2 border">BSEMC</th>
                        </tr>
                    </thead>
                    <tbody id="student-table-body">
                        <tr>
                            <td class="p-2 border">AMA Computer University - Fairview Campus</td>
                            <td class="p-2 border">2</td>
                            <td class="p-2 border">9</td>
                            <td class="p-2 border">4</td>
                            <td class="p-2 border">11</td>
                        </tr>

                        <tr>
                            <td class="p-2 border">Bestlink College of the Philippines</td>
                            <td class="p-2 border">21</td>
                            <td class="p-2 border">6</td>
                            <td class="p-2 border">8</td>
                            <td class="p-2 border">3</td>
                        </tr>

                        <tr>
                            <td class="p-2 border">Informatics College Eastwood</td>
                            <td class="p-2 border">14</td>
                            <td class="p-2 border">6</td>
                            <td class="p-2 border">7</td>
                            <td class="p-2 border">4</td>
                        </tr>
                        <!-- Additional rows can be added here -->
                    </tbody>
                </table>
            </div>

            <!-- Buttons: Print and Back -->
            <div class="flex justify-between mt-6">
                <a type="button" class="bg-blue-900 text-white rounded-md p-2">Print</a>
                <a href="studFr_PrevSchool.php" type="button" class="bg-blue-900 text-white rounded-md p-2">View Detailed Report</a>
            </div>
        </div>
    </div>

    <div class="fixed bottom-6 right-6">
        <a href="prevSchool_detailedReport.php" type="submit" style="background-color: #aaa;" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            Back
        </a>
    </div>

    <script>
        const entriesSelect = document.getElementById('entries');
        const tableBody = document.getElementById('student-table-body');

        const originalRows = Array.from(tableBody.rows); // Store original rows

        function updateTable() {
            const selectedValue = parseInt(entriesSelect.value);
            // Clear the table
            tableBody.innerHTML = '';

            // Add the selected number of rows
            for (let i = 0; i < selectedValue && i < originalRows.length; i++) {
                tableBody.appendChild(originalRows[i]);
            }
        }

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

<!-- CSS Styling -->
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

    /* Table styles */
    .table-container {
        width: 100%;
        overflow-x: auto;
    }

    table {
        width: 100%;
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
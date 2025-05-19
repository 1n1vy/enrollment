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
    <title>Move Section</title>
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
                <li class="active">Move Section</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>CHANGE OF SUBJECT-Move Section</h1>
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

                <!-- Course, Major, Year Level, and Section Section -->
                <div class="form-group">
                    <label for="course">Course</label>
                    <select id="course" class="dropdown">
                        <option>Bachelor of Science in Information Technology</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="major">Major</label>
                    <select id="major" class="short-dropdown-small">
                        <option>N/A</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="year-level">Year Level</label>
                    <select id="year-level" class="short-dropdown-small">
                        <option>2nd</option>
                    </select>
                </div>



                <!-- Proceed Button -->
                <div class="form-actions">
                    <button type="button" id="proceed-btn">Show List</button>
                </div>

                <!-- Table section (hidden by default) -->
                <div id="schedule-table" class="mt-6 hidden">
                    <div class="mb-6">
                        <div class="flex flex-col items-start space-y-2">
                            <p class="text-gray-700 font-semibold">NOTE:</p>
                            <p class="text-gray-700 font-semibold">1. Students in RED are having conflict and should not be moved.</p>
                            <p class="text-gray-700 font-semibold">2. If all students are moved, Remove faculty to that section.</p>
                            <p class="text-gray-700 font-semibold">3. Students having final grades will not be moved.</p>
                        </div>
                    </div>



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

                            <div style="background-color: #174069;" class="text-white p-3 text-center font-bold text-xl rounded-t-md">
                                List of students under 3BSIT-5
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full border border-gray-300">
                                    <thead class="text-gray-700">
                                        <tr>
                                            <td class="py-2 px-4 border text-center">total No. of students: 2</td>
                                            <td class="py-2 px-4 border text-center">Select all</td>
                                            <td class="py-2 px-4 border text-center"><i class="bi bi-toggle-off"></i></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-gray-700">
                                            <td class="py-2 px-4 border text-center">Student ID</td>
                                            <td class="py-2 px-4 border text-center">Name</td>
                                            <td class="py-2 px-4 border text-center"></td>
                                        </tr>
                                        <!-- Add more rows as needed -->
                                    </tbody>
                                    <tbody>
                                        <tr class="text-gray-700">
                                            <td class="py-2 px-4 border text-center">17-11504-255</td>
                                            <td class="py-2 px-4 border text-center">Arjay Padilla</td>
                                            <td class="py-2 px-4 border text-center"><i class="bi bi-toggle-off"></i></td>
                                        </tr>
                                        <!-- Add more rows as needed -->
                                    </tbody>
                                    <tbody>
                                        <tr class="text-gray-700">
                                            <td class="py-2 px-4 border text-center">17-22345-355</td>
                                            <td class="py-2 px-4 border text-center">Princess Simbulan</td>
                                            <td class="py-2 px-4 border text-center"><i class="bi bi-toggle-off"></i></td>
                                        </tr>
                                        <!-- Add more rows as needed -->
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

                            <div style="background-color: #174069;" class="text-white p-3 text-center font-bold text-xl rounded-t-md">
                                List of students under 3BSIT-4
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full border border-gray-300">
                                    <thead class="text-gray-700">
                                        <tr>
                                            <td class="py-2 px-4 border text-center">total No. of students: 2</td>
                                            <td class="py-2 px-4 border text-center"></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-gray-700">
                                            <td class="py-2 px-4 border text-center">Student ID</td>
                                            <td class="py-2 px-4 border text-center">Name</td>

                                        </tr>
                                        <!-- Add more rows as needed -->
                                    </tbody>
                                    <tbody>
                                        <tr class="text-gray-700">
                                            <td class="py-2 px-4 border text-center">17-45322-455</td>
                                            <td class="py-2 px-4 border text-center">Ronbern Buna</td>

                                        </tr>
                                    </tbody>
                                    <tbody>
                                        <tr class="text-gray-700">
                                            <td class="py-2 px-4 border text-center">17-12345-555</td>
                                            <td class="py-2 px-4 border text-center">justine Mariano</td>

                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Save Button in the bottom right corner -->
                                <button id="save-btn" class="save-button">Save</button>
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
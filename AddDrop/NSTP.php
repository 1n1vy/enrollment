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
    <title>NSTP components</title>
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
                <li class="active">NSTP components</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>CHANGE OF SUBJECT-NSTP components</h1>
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
                        <div class="dropdown-and-button">
                            <select id="semester-dropdown" class="ml-2 p-1 border rounded short-dropdown">
                                <option value="1st">1st sem</option>
                                <option value="2nd">2nd sem</option>
                            </select>
                            <button type="button" id="proceed-btn">Show List</button>
                        </div>
                    </div>
                </div>

                <!-- College, Course, Major, and Subject Code Section -->
                <div class="subject-filter-container">
                    <div class="subject-filter-row">
                        <label for="college">College</label>
                        <select id="college" class="filter-dropdown">
                            <option value="all">All</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>
                    <div class="subject-filter-row">
                        <label for="course">Course</label>
                        <select id="course" class="filter-dropdown">
                            <option value="all">All</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>
                    <div class="subject-filter-row">
                        <label for="major">Major</label>
                        <select id="major" class="filter-dropdown">
                            <option value="all">All</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>
                    <div class="subject-filter-row">
                        <label for="subject-code">Subject Code</label>
                        <div class="subject-code-container">
                            <select id="subject-code" class="filter-dropdown">
                                <option value="all">Select Subject</option>
                                <!-- Add more options as needed -->
                            </select>
                            <button type="button" class="refresh-btn">
                                <i class="bi bi-arrow-clockwise"></i> REFRESH
                            </button>
                            <span class="helper-text">click to show list of students under the specified subjects</span>
                        </div>
                    </div>
                </div>




                <div id="schedule-table" class="mt-6 hidden">




                    <div style="background-color: #174069;" class="text-white p-3 text-center font-bold text-xl rounded-t-md">
                        STUDENT LIST ENROLLED IN National Service Training Program 1
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-300">
                            <thead class="text-gray-700">
                                <tr>
                                    <th class="py-2 px-4 border">Count</th>
                                    <th class="py-2 px-4 border">Student ID</th>
                                    <th class="py-2 px-4 border">Student Name</th>
                                    <th class="py-2 px-4 border">Course-Major/year Level</th>
                                    <th class="py-2 px-4 border">Enrolled NSTP</th>
                                    <th class="py-2 px-4 border">Select all<input type="checkbox" name="select"></th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-gray-700">
                                    <td class="py-2 px-4 border text-center">1</td>
                                    <td class="py-2 px-4 border text-center">12-12455-322</td>
                                    <td class="py-2 px-4 border text-center">Arjay Buna</td>
                                    <td class="py-2 px-4 border text-center">2BSIT/1</td>
                                    <td class="py-2 px-4 border text-center">CWTS</td>
                                    <td class="py-2 px-4 border text-center"><input type="checkbox" name="select"></td>

                                </tr>
                            </tbody>
                            <tbody>
                                <tr class="text-gray-700">
                                    <td class="py-2 px-4 border text-center">2</td>
                                    <td class="py-2 px-4 border text-center">18-11504-544</td>
                                    <td class="py-2 px-4 border text-center">Arjay Mariano</td>
                                    <td class="py-2 px-4 border text-center">1BSIT/1</td>
                                    <td class="py-2 px-4 border text-center">ROTC</td>
                                    <td class="py-2 px-4 border text-center"><input type="checkbox" name="select"></td>

                                </tr>
                            </tbody>
                            <tbody>
                                <tr class="text-gray-700">
                                    <td class="py-2 px-4 border text-center">3</td>
                                    <td class="py-2 px-4 border text-center">19-22504-115</td>
                                    <td class="py-2 px-4 border text-center">Arjay Padilla</td>
                                    <td class="py-2 px-4 border text-center">1BSIT/1</td>
                                    <td class="py-2 px-4 border text-center">LTS</td>
                                    <td class="py-2 px-4 border text-center"><input type="checkbox" name="select"></td>


                                </tr>
                            </tbody>
                        </table>

                    </div>
                    <!-- Change to Component Section -->
                    <div class="component-change-container">
                        <label for="component-dropdown">Change to component</label>
                        <select id="component-dropdown" class="component-dropdown">
                            <option value="CWTS">CWTS</option>
                            <option value="NSTP">NSTP</option>
                            <option value="ROTC">ROTC</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>
                    <div class="form-actions mt-4 flex justify-end">
                        <button id="save-btn" class="save-button">Save</button>
                    </div>

                </div>

                <div id="table-container" class="mt-10"></div>
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
        width: 40%;
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

    /* Aligning dropdown and button with more space on the right */
    .dropdown-and-button {
        display: flex;
        align-items: center;
    }

    .dropdown-and-button select {
        margin-right: 50px;
        /* Adds extra space between dropdown and button */
    }

    .dropdown-and-button button {
        padding: 10px 16px;
        font-size: 14px;
        background-color: #174069;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .dropdown-and-button button:hover {
        background-color: #20568B;
    }


    .save-button:hover {
        background-color: #20568B;
    }

    /* Container for the subject filters */
    .subject-filter-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin: 30px auto;
        width: 80%;
        background-color: #f4f8fc;
        /* Matches your form background color */
        padding: 20px;
        border-radius: 8px;
    }

    /* Row styles for each label and dropdown */
    .subject-filter-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Label styles */
    .subject-filter-row label {
        font-weight: bold;
        width: 100px;
        font-size: 14px;
        color: #333;
        /* Matches general text color */
    }

    /* Dropdown styles */
    .filter-dropdown {
        width: 100%;
        padding: 8px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #fff;
    }

    /* Subject Code container for dropdown, refresh button, and helper text */
    .subject-code-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Refresh button */
    .refresh-btn {
        display: flex;
        align-items: center;
        padding: 6px 12px;
        font-size: 14px;
        background-color: #174069;
        /* Matches primary color */
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .refresh-btn:hover {
        background-color: #20568B;
        /* Darker shade for hover */
    }

    /* Helper text */
    .helper-text {
        font-size: 12px;
        color: #666;
    }

    /* Container for Change to Component */
    .component-change-container {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 20px auto;
        width: fit-content;
        padding: 8px 12px;
        background-color: #f4f8fc;
        /* Matches form background */
        border: 1px solid #ccc;
        border-radius: 8px;
        /* Consistent border-radius */
        font-size: 14px;
        color: #333;
    }

    /* Label styling to match other form elements */
    .component-change-container label {
        font-weight: bold;
        color: #333;
        /* Dark text color */
    }

    /* Dropdown styling to match other form elements */
    .component-dropdown {
        padding: 8px 12px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #fff;
        color: #333;
        cursor: pointer;
        transition: border-color 0.3s ease;
    }

    /* Hover effect for dropdown */
    .component-dropdown:hover {
        border-color: #174069;
        /* Dark blue border on hover */
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
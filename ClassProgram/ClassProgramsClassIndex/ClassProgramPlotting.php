<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../../includes/auth.php';
redirectIfNotLoggedIn();

// Optionally, restrict access by role
checkRole(['Admin', 'Faculty', 'Registrar']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Program Plotting</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div id="navbar-placeholder"></div>
    <div class="main-content" id="mainContent">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Enrollment</a></li>
                <li><a href="#">Class Programs</a></li>
                <li class="active">Class Program Plotting</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>CLASS PROGRAM PLOTTING</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form action="#">
                <!--- SY & Term -->
                <div class="form-group">
                    <label for="schoolyear">School Year & Term</label>
                    <div class="form-row">
                        <input type="text" id="offering-sy" placeholder="Enter">
                        <span>to</span>
                        <input type="text" id="offering-sy-end" placeholder="Enter">
                        <select id="semester" class="ml-2 p-1 border rounded w-20">
                            <option value="1st">1st sem</option>
                            <option value="2nd">2nd sem</option>
                            <option value="3nd">Summer</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="course">Course</label>
                    <select id="course">
                        <option value="" disabled selected>Select course</option>
                        <option value="1">BS Computer Science (Bachelor of Science in Computer Science)</option>
                        <option value="2">BSIS (Bachelor of Science in Information Systems)</option>
                        <option value="3">BSIT (Bachelor of Science in Information Technology)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="year">Year</label>
                    <select id="year">
                        <option value="" disabled selected>Select</option>
                        <option value="A">1st</option>
                        <option value="B">2nd</option>
                        <option value="C">3rd</option>
                        <option value="D">4th</option>
                        <option value="E">5th</option>
                        <option value="F">6th</option>
                        <option value="G">7th</option>
                        <option value="H">8th</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="major">Major</label>
                    <select id="major" required>
                        <option value="" disabled selected>Select</option>
                        <option value="A">1st Major</option>
                        <option value="B">2nd Major</option>
                        <option value="C">3rd Major</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="select4">Current Year</label>
                    <select id="select4" name="select1">
                        <option value="" disabled selected>Select</option>
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                    </select>
                </div>
                <div class="checkbox-container flex items-center">
                    <input type="checkbox" id="mixedoffering">
                    <label for="mixedoffering">Include Mixed Offering</label>
                </div>

                <!-- Proceed Button -->
                <div class="form-actions">
                    <button type="button" onclick="loadTable()">Proceed</button>
                </div>
                <div class="empty-row"></div>
                <hr class="thick-separator">
                <div class="overflow-x-auto">
                    <section class="section-header text-sm mt-6">
                        <h1>SUBJECT OFFERINGS 2ND SEMESTER SY 2023-2024</h1>
                    </section>
                    <div class="form-row">
                        <button class="bi bi-printer"> Print All</button>
                        <span></span>
                        <button class="bi bi-printer"> Print Selected</button>
                    </div>
                    <table class="min-w-full border border-gray-300">
                        <thead style="background-color: #174069;" class="text-white">
                            <tr>
                                <th class="py-2 px-4 border">COUNT</th>
                                <th class="py-2 px-4 border">SECTION</th>
                                <th class="py-2 px-4 border">SELECT <input type="checkbox"> ALL <input type="checkbox"> BLOCK</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-gray-700 bg-white">
                                <td class="py-2 px-4 border text-center">1.</td>
                                <td class="py-2 px-4 border text-center">1BSIT-1</td>
                                <td class="py-2 px-4 border text-center"><input type="checkbox"></td>
                            </tr>
                            <tr class="text-gray-700 bg-white">
                                <td class="py-2 px-4 border text-center">2.</td>
                                <td class="py-2 px-4 border text-center">1BSIT-2</td>
                                <td class="py-2 px-4 border text-center"><input type="checkbox"></td>
                            </tr>
                            <tr class="text-gray-700 bg-white">
                                <td class="py-2 px-4 border text-center">3.</td>
                                <td class="py-2 px-4 border text-center">1BSIT-3</td>
                                <td class="py-2 px-4 border text-center"><input type="checkbox"></td>
                            </tr>
                        </tbody>
                    </table>
            </form>
        </div>

        <!--HALF FUNCTIONAL-->
        <script>
            // Function to fetch and insert the table from table-page.html
            function loadTable() {
                console.log('Proceed button clicked, attempting to fetch the table...'); // Debug log

                fetch('table-page.php')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok'); // Handle fetch failure
                        }
                        return response.text();
                    })
                    .then(data => {
                        console.log('Table content successfully fetched'); // Debug log

                        const parser = new DOMParser();
                        const doc = parser.parseFromString(data, 'text/html');
                        const table = doc.querySelector('table'); // Get the table element

                        if (table) {
                            document.getElementById('table-container').innerHTML = table.outerHTML;
                            console.log('Table successfully inserted into the page'); // Debug log
                        } else {
                            console.error('No table found in table-page.php'); // Handle missing table element
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching table:', error); // Handle any errors
                    });
            }

            // Fetch the navbar component
            fetch('../../Components/Navbar.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('navbar-placeholder').innerHTML = data;
                    // Load navbar script after inserting HTML
                    var script = document.createElement('script');
                    script.src = '../../Components/app.js';
                    document.body.appendChild(script);
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
        width: 100%;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    /* Adjust width for the student ID input */
    #student-id {
        width: 50%;
        /* This will make the Student ID input box shorter */
    }

    /* Inline form group for Offering SY */
    .form-row {
        display: flex;
        align-items: center;
    }

    /* Shorter text boxes for Offering SY */
    .form-row input {
        width: 30%;
        /* Shorter text boxes */
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
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-actions button:hover {
        background-color: #20568B;
    }

    .empty-row {
        height: 20px;
        /* Adjust the height as needed */
    }

    /* General table styling */
    .custom-table {
        width: 100%;
        /* Make the table full width */
        border-collapse: collapse;
        /* Remove spacing between cells */
        margin: 20px 0;
        /* Space around the table */
    }

    /* Header styling */
    .custom-table th,
    .custom-table td {
        border: 1px solid #ccc;
        /* Light border for cells */
        padding: 8px;
        /* Padding for cell content */
        text-align: left;
    }

    /* Hover effect for rows */
    .custom-table tbody tr:hover {
        background-color: #f4f8fc;
        /* Light background on hover */
    }

    /* Optional: Responsive design for smaller screens */
    @media (max-width: 600px) {
        .custom-table {
            font-size: 14px;
            /* Smaller font size on mobile */
        }
    }

    /* Checkbox alignment */
    .custom-table input[type="checkbox"] {
        transform: scale(1.2);
        /* Enlarge checkboxes */
        margin: 0 auto;
        /* Center the checkboxes */
        cursor: pointer;
        /* Change cursor to pointer */
    }

    /* Expandable heading styling - matching the table headers */
    .custom-table .expandable-heading {
        cursor: pointer;
        background-color: #f2f2f2;
        font-weight: bold;
        padding: 8px;
        border: 1px solid #ccc;
        /* Same border as other headers */
        text-align: left;
    }

    /* Remove arrows */
    .custom-table .expandable-heading:after {
        content: '';
        /* Remove arrow */
    }

    .custom-table.expanded .expandable-heading:after {
        content: '';
        /* Remove arrow */
    }

    /* Expandable content hidden by default */
    .custom-table .expandable-content {
        display: none;
    }

    /* Expandable content when table is expanded */
    .custom-table.expanded .expandable-content {
        display: table-row-group;
    }

    .headerbody {
        text-align: center;
        /* Center text horizontally */
        padding: 20px;
        font-size: 24px;
        width: 100%;
        /* Make sure it takes full width */
        margin: 0 auto;
        /* Center the container if it has a defined width */
    }
</style>
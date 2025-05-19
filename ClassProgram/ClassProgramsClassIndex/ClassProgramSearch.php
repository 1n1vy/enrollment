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
    <title>Class Program Search</title>
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
                <li class="active">Class Programs</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>CLASS PROGRAM SEARCH</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form action="#">
                <!-- Two-column layout for form fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left column inputs -->
                    <div>
                        <div class="flex">
                            <div class="form-group" style="flex-grow: 1;">
                                <label for="classprogramsy1">Class Program for SY</label>
                                <input type="text" id="classprogramsy1" placeholder="Enter" required>
                            </div>
                            <div class="label label-input" style="flex-grow: 0.2;">
                                <span>-</span>
                                <input type="text" id="classprogramsy2" placeholder="Enter" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="courseProg">Course Program (Optional)</label>
                            <select id="courseProg">
                                <option value="" disabled selected>Select a Program</option>
                                <option value="A">Baccalaureate</option>
                                <option value="B">Doctoral</option>
                                <option value="C">Expanded Tertiary Education Equivalency & Accreditation Program</option>
                                <option value="D">Masteral</option>
                                <option value="E">Open/Online University</option>
                                <option value="F">Post Baccalaureate</option>
                                <option value="G">Pre-Baccalaureate</option>
                                <option value="H">Technical Education and Skills Development Center</option>
                            </select>
                        </div>

                        <div class="flex">
                            <div class="form-group" style="flex-grow: 1;">
                                <label for="currYear">Curriculum Year</label>
                                <select id="currYear" onchange="updateEndYear()" required>
                                    <option value="" disabled selected>Select</option>
                                    <option value="A">2015</option>
                                    <option value="B">2019</option>
                                    <option value="C">2020</option>
                                </select>
                            </div>
                            <div class="form-group" style="flex-grow: 0.2;">
                                <label for="curryear-end">Curr Year End</label>
                                <input type="text" id="curryear-end" readonly />
                            </div>
                        </div>
                        <div class="form-group" style="flex-grow: 0.2;">
                            <label for="major">Major</label>
                            <select id="major" required>
                                <option value="" disabled selected>Select</option>
                                <option value="A">1st Major</option>
                                <option value="B">2nd Major</option>
                                <option value="C">3rd Major</option>
                            </select>
                        </div>
                    </div>
                    <!-- Right column inputs -->
                    <div>
                        <div class="form-group" style="flex-grow: 0.4;">
                            <label for="semester">Semester</label>
                            <select id="semester" required>
                                <option value="" disabled selected>Select</option>
                                <option value="1">1st Semester</option>
                                <option value="2">2nd Semester</option>
                            </select>
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

                        <div class="flex">
                            <div class="form-group" style="flex-grow: 1;">
                                <label for="year">Year</label>
                                <select id="year" required>
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
                            <div class="form-group" style="flex-grow: 0.2;">
                                <label for="term">Term</label>
                                <select id="term" required>
                                    <option value="" disabled selected>Select</option>
                                    <option value="A">1st</option>
                                    <option value="B">2nd</option>
                                    <option value="C">3rd</option>
                                    <option value="D">Summer</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ADD Button -->
                <div class="form-actions mt-8 flex justify-center">
                    <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded-md">ADD</button>
                </div>

                <!-- Table below the ADD Button -->
                <hr class="thick-separator mt-6">
                <div class="overflow-x-auto">
                    <section class="section-header text-sm mt-6">
                        <h1>SUBJECT LIST ALLOWED TO ADD</h1>
                    </section>
                    <table class="min-w-full border border-gray-300">
                        <thead style="background-color: #174069;" class="text-white">
                            <tr>
                                <th class="py-2 px-4 border">YEAR</th>
                                <th class="py-2 px-4 border">TERM</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-gray-700 bg-white">
                                <td class="py-2 px-4 border text-center">1BSIT-1</td>
                                <td class="py-2 px-4 border text-center"><a href="#">Print Class Program</a></td>
                            </tr>
                            <tr class="text-gray-700 bg-white">
                                <td class="py-2 px-4 border text-center">1BSIT-2</td>
                                <td class="py-2 px-4 border text-center"><a href="#">Print Class Program</a></td>
                            </tr>
                            <tr class="text-gray-700 bg-white">
                                <td class="py-2 px-4 border text-center">1BSIT-1</td>
                                <td class="py-2 px-4 border text-center"><a href="#">Print Class Program</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </form>
        </div>



        <script>
            fetch('../../Components/Navbar.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('navbar-placeholder').innerHTML = data;
                    // Load navbar script after inserting HTML
                    var script = document.createElement('script');
                    script.src = '../../Components/app.js';
                    document.body.appendChild(script);
                });

            function updateEndYear() {
                const currYearSelect = document.getElementById('currYear');
                const endYearInput = document.getElementById('curryear-end');
                const selectedOption = currYearSelect.options[currYearSelect.selectedIndex];

                if (selectedOption) {
                    // Extract the year from the text content
                    const startYear = parseInt(selectedOption.textContent);

                    if (!isNaN(startYear)) {
                        endYearInput.value = startYear + 1; // Set end year as start year + 1
                    } else {
                        endYearInput.value = ""; // Clear if the year is not a number
                    }
                } else {
                    endYearInput.value = ""; // Clear if no selection
                }
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

    .label input,
    .label select {
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 100%;
        /* Ensure inputs take up full width inside their containers */
    }

    .flex {
        font-weight: bold;
        display: flex;
        gap: 5px;
        /* Maintain close proximity between elements */
        flex-wrap: nowrap;
        /* Prevent wrapping */
        width: 100%;
        /* Ensure the flex container is responsive */
    }


    .ml-1 {
        margin-left: 0;
        /* Remove any additional left margin */
        flex-grow: 1;
        /* Allow input fields to grow as needed */
    }

    .flex-grow {
        flex-grow: 1;
    }

    .empty-row {
        height: 75px;
        /* Adjust the height as needed */
    }

    /* Checkboxes */
    .checkbox-wrapper {
        display: flex;
        justify-content: space-between;
    }

    .checkbox-container {
        display: flex;
        align-items: center;
    }

    .checkbox-container input {
        margin-right: 5px;
    }

    /* Button styles */
    .form-actions button {
        padding: 12px 20px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
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

    /* Centered header style */
    .headerbody {
        text-align: center;
        /* Center text horizontally */
        padding: 20px;
        /* Padding around header */
        font-size: 24px;
        /* Header font size */
        width: 100%;
        /* Full width */
        margin: 0 auto;
        /* Center the container */
    }

    /* Remove margin from section header to combine with table */
    .section-header {
        margin-bottom: 0;
        /* Remove margin below section header */
    }

    /* Optional: Space above the table for better separation */
    .custom-table {
        margin-top: 10px;
        /* Adjust margin for separation */
    }
</style>
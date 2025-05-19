<?php
session_start();
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
    <title>Per Subject Edit</title>
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
                <li><a href="#">Class Programs</a></li>
                <li class="active">Per Subject Edit Page</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>PER SUBJECT - VIEW/EDIT/DELETE/PRINT PAGE</h1>
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
                                <label for="sy">School Year</label>
                                <input type="text" id="sy" placeholder="Enter" required>
                            </div>
                            <div class="label label-input" style="flex-grow: 0.2;">
                                <span>-</span>
                                <input type="text" id="sy2" placeholder="Enter" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sectionoffered">Section Offered</label>
                            <select id="sectionoffered">
                                <option value="" disabled selected>Select</option>
                                <option value="A">1BSIT-1</option>
                                <option value="B">1BSIT-2</option>
                                <option value="C">1BSIT-3</option>
                                <option value="D">1BSIT-4</option>
                            </select>
                        </div>
                    </div>
                    <!-- Right column inputs -->
                    <div>
                        <div class="form-group" style="flex-grow: 0.4;">
                            <label for="term">Term</label>
                            <select id="term" required>
                                <option value="" disabled selected>Select</option>
                                <option value="1">1st</option>
                                <option value="2">2nd</option>
                                <option value="3">3rd</option>
                                <option value="4">4th</option>
                                <option value="5">5th</option>
                                <option value="6">Summer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="subjectcodeselect">Subject Code</label>
                            <select id="subjectcodeselect">
                                <option value="" disabled selected>Select</option>
                                <option value="A">CCL112-18 (Fundamentals of Programming)</option>
                                <option value="B">CCL121-18 (Intermediate Programming)</option>
                                <option value="C">CCL112-18 (Fundamentals of Programming Lab)</option>
                                <option value="D">CCL121-18 (Intermediate Programming Lab)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- ADD Button -->
                <div class="form-actions mt-8 flex justify-center">
                    <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded-md">Proceed</button>
                </div>

                <!-- Table below the ADD Button -->
                <div class="table-responsive mt-8">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>OFFERED BY COLLEGE DEPT</th>
                                <th>SUBJECT CODE</th>
                                <th>DESCRIPTION</th>
                                <th>TOTAL UNITS</th>
                                <th>SECTION</th>
                                <th>SCHEDULE</th>
                                <th>ROOM #</th>
                                <th># ENROLLED</th>
                                <th>DO NOT CHECK CONFLICT</th>
                                <th>Force Close</th>
                                <th>Adjust Capacity</th>
                                <th>EDIT</th>
                                <th>DELETE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>CICS</td>
                                <td>CCL121-18</td>
                                <td>Intermediate Programming</td>
                                <td>2.0</td>
                                <td>1BSIT-1</td>
                                <td>F 4:00PM-6:00PM</td>
                                <td>B322</td>
                                <td>35</td>
                                <td></td>
                                <td></td>
                                <td>35</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                            </tr>
                            <tr>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                            </tr>
                        </tbody>
                    </table>
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

            function updateEndYear() {
                const currYearSelect = document.getElementById('currYear');
                const endYearInput = document.getElementById('curryear-end');
                const selectedOption = currYearSelect.options[currYearSelect.selectedIndex];

                if (selectedOption) {
                    const startYear = parseInt(selectedOption.textContent);

                    if (!isNaN(startYear)) {
                        endYearInput.value = startYear + 1;
                    } else {
                        endYearInput.value = "";
                    }
                } else {
                    endYearInput.value = "";
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

    .table-responsive {
        overflow-x: auto;
        /* Allow horizontal scrolling */
    }

    .custom-table {
        width: 100%;
        /* Make the table take the full width of the container */
        border-collapse: collapse;
        /* Collapse borders for a cleaner look */
    }

    .custom-table th,
    .custom-table td {
        padding: 10px;
        /* Add padding for better readability */
        border: 1px solid #ddd;
        /* Add a light border to table cells */
        text-align: left;
        /* Align text to the left */
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
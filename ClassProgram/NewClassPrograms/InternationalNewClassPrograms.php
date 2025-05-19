<?php
session_start();
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
    <title>International New Class Programs</title>
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
                <li><a href="#">Class Program</a></li>
                <li class="active">New Class Programs/Sections Page</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>NEW CLASS PROGRAMS/SECTIONS</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <div class="checkbox-container flex items-center">
                <input type="checkbox" id="international" class="mr-2">
                <label for="international">Check for International/Additional subject offerings/schedules (offerings for all courses)</label>
            </div>
            <form action="#" id="classForm"> <!-- Added an ID to the form -->
                <!-- Two-column layout for form fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left column inputs -->
                    <div>
                        <div class="form-group">
                            <label for="courseProg">Course Program (Optional to Select)</label>
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

                        <div class="form-group">
                            <label for="curryear">Curriculum Year</label>
                            <select id="curryear" onchange="updateEndYear()">
                                <option value="" disabled selected>Select</option>
                                <option value="2015">2015</option>
                                <option value="2019">2019</option>
                                <option value="2020">2020</option>
                            </select>
                        </div>

                        <div class="form-group">
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

                        <div class="form-group">
                            <label for="major">Major</label>
                            <select id="major">
                                <option value="" disabled selected>Select</option>
                                <option value="A">Major 1</option>
                                <option value="B">Major 2</option>
                            </select>
                        </div>

                        <div class="flex">
                            <div class="label label-input" style="flex-grow: 1;">
                                <label for="classprogramsy1">Class Program for SY</label>
                                <input type="text" id="classprogramsy1" placeholder="Enter" required>
                            </div>
                            <div class="label label-input" style="flex-grow: 0.2;">
                                <span>-</span>
                                <input type="text" id="classprogramsy2" placeholder="Enter" required>
                            </div>
                            <div class="label label-input" style="flex-grow: 0.4;">
                                <label for="semester">Semester</label>
                                <select id="semester" required>
                                    <option value="" disabled selected>Select</option>
                                    <option value="1">1st Semester</option>
                                    <option value="2">2nd Semester</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="label label-input" style="flex-grow: 1;">
                                <label for="filtersub" style="font-size: 12px;">Filter Sub Code</label>
                                <input type="text" id="classprogramsy1" placeholder="Enter">
                            </div>
                            <div class="label label-input" style="flex-grow: 0.1; display: flex; flex-direction: column;">
                                <label style="font-size: 8px;">Click to show Subjects</label>
                                <a href="#" class="showsub text-center bg-blue-500 text-white px-2 py-1 rounded" style="margin-top: 8px; font-size: 13px;">
                                    Show Subjects
                                </a>
                            </div>
                            <div class="label label-input" style="flex-grow: 0.1; display: flex; flex-direction: column;">
                                <label style="font-size: 8px;">Show Class Prgms With Details</label>
                                <a href="#" onclick="openPopup1()" class="showsub text-center bg-blue-500 text-white px-2 py-1 rounded" style="margin-top: 8px; font-size: 14px;">
                                    Show Details
                                </a>
                            </div>
                        </div>

                        <!-- Align Subject -->
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <select id="subject" required>
                                <option value="" disabled selected>Select Subject Code</option>
                                <option value="1">IT 111L</option>
                                <option value="2">IT 112L</option>
                                <option value="3">PE 1</option>
                                <option value="4">NSTP 1</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="subjectttle">Subject Title:</label>
                        </div>
                        <div class="form-group">
                            <label for="subjectofferingtype">Subject Offering Type</label>
                            <select id="subjectofferingtype" required>
                                <option value="" disabled selected>Select</option>
                                <option value="a">Regular Subject</option>
                                <option value="b">Irregular Subject</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="subjectcreatedby">Subject created by College:</label>
                            <span>College of</span>
                        </div>

                        <div class="form-group">
                            <label for="schedulecreated">Schedule Created by Department</label>
                            <span>All Departments</span>
                        </div>
                        <div class="form-group">
                            <label for="schedulewk">Schedule (M-T-W-TH-F-SAT-SUN)</label>
                            <input type="text" id="schedulewk" placeholder="Enter" required>
                        </div>
                        <div class="form-group">
                            <label for="roomno">Room No</label>
                            <select id="roomno">
                                <option value="" disabled selected>Optional to Select</option>
                                <option value="1">M109</option>
                                <option value="2">M108</option>
                                <option value="3">M107</option>
                                <option value="4">M106</option>
                            </select>
                        </div>
                    </div>

                    <!-- Right column inputs -->
                    <div>
                        <div class="form-group">
                            <label for="offeringscourses">Offerings for Course</label>
                            <select id="offeringscourses" required>
                                <option value="" disabled selected>Select Course Offering</option>
                                <option value="1">BS Computer Science (Bachelor of Science in Computer Science)</option>
                                <option value="2">BSIS (Bachelor of Science in Information Systems)</option>
                                <option value="3">BSIT (Bachelor of Science in Information Technology)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="curryear-end">Curriculum Year End</label>
                            <input type="text" id="curryear-end" readonly />
                        </div>
                        <div class="form-group">
                            <label for="term">Term</label>
                            <select id="term" required>
                                <option value="" disabled selected>Select</option>
                                <option value="A">1st</option>
                                <option value="B">2nd</option>
                                <option value="C">3rd</option>
                                <option value="D">Summer</option>
                            </select>
                        </div>

                        <div class="empty-row"></div>
                        <div class="empty-row"></div>
                        <div class="empty-row"></div>

                        <!-- Align Section directly below Term, right-aligned -->
                        <div class="form-group">
                            <label for="section">Lecture or Laboratory</label>
                            <select id="lec-lab" required>
                                <option value="" disabled selected>Select</option>
                                <option value="A">Lecture</option>
                                <option value="B">Laboratory</option>
                            </select>
                        </div>
                        <div class="checkbox-container flex items-center">
                            <input type="checkbox" id="requestedsub" class="mr-2" style="margin-top: 5px;">
                            <label for="requestedsub">Is Requested Subject?</label>
                        </div>
                        <div class="form-group" style="margin-top: 10px;">
                            <label for="section">Section</label>
                            <select id="section" required>
                                <option value="" disabled selected>Select Section</option>
                                <option value="A">1BSIT-1</option>
                                <option value="B">1BSIT-2</option>
                                <option value="C">1BSIT-3</option>
                                <option value="D">1BSIT-4</option>
                            </select>
                            <div class="flex-container" style="flex-grow: 0.2; display: flex; flex-direction: row; gap: 10px">
                                <a href="#" onclick="openPopup()" class="bi bi-pencil-square" style="margin-top: 7px;">
                                    -Click to edit sections
                                </a>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="subjectcategory">Subject Category:</label>
                            <span>CCS Computer Laboratory</span>
                        </div>
                        <div class="form-group" style="display: flex; gap: 10px; align-items: center;">
                            <div>
                                <label for="time-input-1" style="margin-top: 23px;">Time From</label>
                                <input type="time" id="time-input-1" name="time-input-1" required>
                            </div>
                            <div>
                                <label for="time-input-2" style="margin-top: 23px;">Time To</label>
                                <input type="time" id="time-input-2" name="time-input-2" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end">
                    <a href="#" style="background-color: #174069; width: 120px;"
                        class="bg-blue-900 text-white px-3 py-3 text-sm flex items-center justify-center">+ Add
                    </a>
                </div>
            </form>


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

                // Function to handle checkbox state and redirect accordingly
                document.getElementById('international').addEventListener('change', function() {
                    if (this.checked) {
                        // Redirect to International New Class Programs page when checked
                        window.location.href = "NewClassPrograms.php";
                    } else {
                        // Redirect back to New Class Programs page when unchecked
                        window.location.href = "InternationalNewClassPrograms.php";
                    }
                });

                // Function to validate form fields
                function validateForm(event) {
                    event.preventDefault(); // Prevent form submission

                    // Get all form inputs
                    const courseProg = document.getElementById('building').value;
                    const currYear = document.getElementById('curryear').value;
                    const year = document.getElementById('year').value;
                    const classProgramSY1 = document.getElementById('classprogramsy1').value;
                    const classProgramSY2 = document.getElementById('classprogramsy2').value;
                    const semester = document.getElementById('semester').value;
                    const term = document.getElementById('term').value;
                    const subject = document.getElementById('subject').value;
                    const subjectOfferingType = document.getElementById('subjectofferingtype').value;
                    const scheduleWK = document.getElementById('schedulewk').value;
                    const timeInput1 = document.getElementById('time-input-1').value;
                    const timeInput2 = document.getElementById('time-input-2').value;

                    let errorMessage = '';

                    // Validate required fields
                    if (!currYear || !term || !year || !classProgramSY1 || !classProgramSY2 || !semester || !subject || !subjectOfferingType) {
                        errorMessage += 'Please fill in all required fields.\n';
                    }

                    // Separate validation for schedule and time inputs
                    if (!scheduleWK || !timeInput1 || !timeInput2) {
                        errorMessage += 'Please provide the schedule and time details.\n';
                    }

                    // Show error message if validation fails
                    if (errorMessage) {
                        alert(errorMessage);
                    } else {
                        alert('Form submitted successfully!'); // Notify successful validation
                        // Here, you can submit the form or perform further actions
                        // Uncomment the next line to submit the form
                        // document.querySelector('.form-container form').submit(); 
                        document.querySelector('.form-container form').reset(); // Reset the form after submission
                    }
                }

                // Function to update end year based on curriculum year selection
                function updateEndYear() {
                    const currYearSelect = document.getElementById('curryear');
                    const endYearInput = document.getElementById('curryear-end');
                    const startYear = parseInt(currYearSelect.value);
                    if (startYear) {
                        endYearInput.value = startYear + 1; // Set end year as start year + 1
                    } else {
                        endYearInput.value = ""; // Clear if no selection
                    }
                }

                // Attach validation function to the "Add" button click event
                document.getElementById('add-btn').addEventListener('click', validateForm);

                function openPopup() {
                    // URL of the page you want to open
                    const url = 'SectionEditor.php'; // Replace with your popup page URL
                    const options = 'width=1200,height=800,resizable=yes,scrollbars=yes'; // Customize the dimensions
                    window.open(url, 'PopupWindow', options);
                }

                function openPopup1() {
                    // URL of the page you want to open
                    const url = 'ClassProgramsWithDetails.php'; // Replace with your popup page URL
                    const options = 'width=1200,height=800,resizable=yes,scrollbars=yes'; // Customize the dimensions
                    window.open(url, 'PopupWindow', options);
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

    .form-group half {
        width: 50%;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .label {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 5px;
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



    input[readonly] {
        background-color: #aaa7a7;
        /* Light gray background for readonly input */
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

    .showsub {
        padding: 12px 20px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        background-color: #174069;
        /* Blue background */
        color: white;
        /* White text */
        width: 140px;
        /* Adjust button width */
        height: 45px;
        /* Adjust button height */
    }

    .showsub:hover {
        background-color: #0056b3;
        /* Darker blue on hover */
    }

    .flex-container {
        display: flex;
        align-items: left;
        justify-content: flex-start;
        margin-top: 10px;
    }

    /* Make the time inputs stack vertically */
    .form-group#time-group {
        display: flex;
        flex-direction: column;
        /* Stack items vertically */
        gap: 10px;
        /* Space between Time From and Time To */
    }

    .form-group#time-group div {
        display: flex;
        flex-direction: column;
        /* Ensure each label and input is stacked vertically */
    }

    .form-group#time-group label {
        margin-bottom: 5px;
    }

    .form-group#time-group input {
        width: 100%;
    }
</style>
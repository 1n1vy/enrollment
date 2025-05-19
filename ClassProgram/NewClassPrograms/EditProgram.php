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
    <title>EDIT Class Programs</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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
                <li><a href="#">Class Program</a></li>
                <li class="active">EDIT Class Programs/Sections Page</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h5 class="text-light">EDIT CLASS PROGRAMS/SECTIONS</h5>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form action="../programAPI.php" method="POST">
                <div class="row g-4">
                    <!-- Left column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="progDept" class="form-label">Department</label>
                            <select id="progDept" class="form-select" required>
                            <option value="" disabled selected>Select a Department</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="progProgram" class="form-label">Course Program</label>
                            <select id="progProgram" class="form-select" required>
                            <option value="" disabled selected>Select a Program</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="progMajor" class="form-label">Major (If applicable)</label>
                            <select id="progMajor" class="form-select" required>
                            <option value="" disabled selected>Select Major</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="progSection" class="form-label">Section *</label>
                            <select id="progSection" class="form-select" required>
                            <option value="" disabled selected>Select Section</option>
                            </select>
                        </div>

                        <div class="row g-2 align-items-end mb-3">
                            <div class="col">
                            <label for="progAcadstart" class="form-label">Class Program for SY *</label>
                            <input type="text" id="progAcadstart" class="form-control text-light" required readonly>
                            </div>
                            <div class="col-2 text-center">
                            <span>-</span>
                            </div>
                            <div class="col">
                            <input type="text" id="progAcadend" class="form-control text-light" required readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="subjectofferingtype" class="form-label">Subject Offering Type</label>
                            <select id="subjectofferingtype" class="form-select" required>
                            <option value="" disabled selected>Select</option>
                            <option value="Reg">Regular Subject</option>
                            <option value="Irreg">Irregular Subject</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="schedulewk" class="form-label">Schedule (M,T,W,TH,F,SAT,SUN)</label>
                            <input type="text" id="schedulewk" class="form-control" placeholder="e.g. M" required>
                        </div>
                    </div>

                    <!-- Right column -->
                    <div class="col-md-6">
                    <div class="mb-3">
                        <label for="progCourses" class="form-label">Course *</label>
                        <select id="progCourses" class="form-select" required>
                        <option value="" disabled selected>Select Course</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="subject_autocomp" class="form-label">Subject *</label>
                        <input list="options" id="subject_autocomp" name="combinedInput" class="form-control" placeholder="subject code or subject name" required>
                    </div>

                    <div class="mb-3">
                        <label for="progCurr" class="form-label">Curriculum Year *</label>
                        <select id="progCurr" class="form-select" required>
                        <option value="" disabled selected>Select Curriculum</option>
                        </select>
                    </div>

                    <div class="mb-3">
                            <label for="progYear" class="form-label">Year *</label>
                            <input type="text" id="progYear" class="text-light form-control" readonly>
                        </div>

                    <div class="mb-3">
                        <label for="progTerm" class="form-label">Term *</label>
                        <input type="text" id="progTerm" class="text-light form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="subcomponent" class="form-label">Subject Component *</label>
                        <select id="subcomponent" class="form-select" required>
                            <option value="" disabled selected>Select Subject Component</option>
                            <option value="Lec">Lecture</option>
                            <option value="Lab">Laboratory</option>
                        </select>
                    </div>

                    <div class="row g-2 align-items-end">
                        <div class="col">
                        <label for="time_input1" class="form-label">Time from *</label>
                        <input type="time" id="time_input1" class="form-control" required>
                        </div>
                        <div class="col">
                        <label for="time_input2" class="form-label">Time to *</label>
                        <input type="time" id="time_input2" class="form-control" required>
                        </div>
                    </div>
                    </div>

                    
                    <input type="hidden" id="progSubjectid" class="text-light form-control" readonly>
                    
                    <div class="checkbox-container flex items-center mt-2 mb-4">
                        <input type="checkbox" id="international" class="mr-2">
                        <label for="international">Check for International/Additional subject offerings/schedules (offerings for all courses)</label>
                    </div>
                </div>
            </form>

            <div class="form-actions d-flex justify-content-center">
                <button type="button" onclick="submitProgramForm()" class="btn btn-primary">UPDATE</button>
            </div>
        </div>

    <script>


function submitProgramForm() {
    if (!$('#progDept').val()) {
        alert('Department is required.');
        return false;
    }
    if (!$('#progCourses').val()) {
        alert('Course is required.');
        return false;
    }
    if (!$('#progProgram').val()) {
        alert('Program is required.');
        return false;
    }
    if (!$('#progSubjectid').val()) {
        alert('Subject ID is required.');
        return false;
    }
    if (!$('#progMajor').val()) {
        alert('Major is required.');
        return false;
    }
    if (!$('#progCurr').val()) {
        alert('Curriculum is required.');
        return false;
    }
    if (!$('#progSection').val()) {
        alert('Section is required.');
        return false;
    }
    if (!$('#progYear').val()) {
        alert('Year is required.');
        return false;
    }
    if (!$('#progTerm').val()) {
        alert('Term is required.');
        return false;
    }
    if (!$('#progAcadstart').val()) {
        alert('Start Year is required.');
        return false;
    }
    if (!$('#progAcadend').val()) {
        alert('End Year is required.');
        return false;
    }
    if (!$('#subjectofferingtype').val()) {
        alert('Subject Offering Type is required.');
        return false;
    }
    if (!$('#subcomponent').val()) {
        alert('Subject Component is required.');
        return false;
    }
    if (!$('#schedulewk').val()) {
        alert('Schedule Week is required.');
        return false;
    }
    if (!$('#time_input1').val()) {
        alert('Time Input start is required.');
        return false;
    }
    if (!$('#time_input2').val()) {
        alert('Time Input end is required.');
        return false;
    }

    $.ajax({
        url: '../programAPI.php',
        type: 'POST',
        data: {
            action: 'updateProgram',
            progDept: $('#progDept').val(),
            progCourses: $('#progCourses').val(),
            progProgram: $('#progProgram').val(),
            subject_autocomp: $('#progSubjectid').val(),
            progMajor: $('#progMajor').val(),
            progCurr: $('#progCurr').val(),
            progSection: $('#progSection').val(),
            progYear: $('#progYear').val(),
            progTerm: $('#progTerm').val(),
            progAcadstart: $('#progAcadstart').val(),
            progAcadend: $('#progAcadend').val(),
            subjectofferingtype: $('#subjectofferingtype').val(),
            subcomponent: $('#subcomponent').val(),
            schedulewk: $('#schedulewk').val(),
            time_input1: $('#time_input1').val(),
            time_input2: $('#time_input2').val(),
            program_id: <?php echo $_GET['program_id'] ?>,
            international: $('#international').is(':checked') ? 1 : 0
        },
        success: function (response) {
            console.log('Server Response:', response);
            alert('Program successfully updated!');
            $('form')[0].reset();
            window.location.href = "../ClassProgramPerSubject.php";
        },
        error: function (xhr, status, error) {
            console.error('Error:', error);
            console.log('Response:', xhr.responseText);
            alert('Failed to submit program. Check console for details.');
        }
    });
}


    </script>

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

            function openPopup() {
                // URL of the page you want to open
                const url = 'SectionEditor.php'; // Replace with your popup page URL
                const options = 'width=1200,height=800,resizable=yes,scrollbars=yes'; // Customize the dimensions
                window.open(url, 'PopupWindow', options);
            }
        </script>

</body>

</html>

<script src="../classprograms.js"></script>

<script>

        fetchDepartments(<?php echo $_GET['program_id'] ?>);
        

</script>

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
        width: 150px;
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
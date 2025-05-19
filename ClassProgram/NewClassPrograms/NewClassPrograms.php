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
    <title>New Class Programs</title>
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
                <li class="active">New Class Programs/Sections Page</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>NEW CLASS PROGRAMS/SECTIONS</h1>
        </section>

       <!-- Form container -->
<div class="form-container">
    <form action="../programAPI.php" method="POST">
        <!-- ROW 1: Major & Section -->
        <div class="row g-4">
            <!-- Left column  -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="progCurr" class="form-label">Curriculum Year</label>
                    <select id="progCurr" class="form-select" required>
                        <option value="" disabled selected>Select Curriculum</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="progCourses" class="form-label">Program</label>
                    <select id="progCourses" class="form-select" required>
                        <option value="" disabled selected>Select Course</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="progProgram" class="form-label">Academic Level</label>
                    <select id="progProgram" class="form-select">
                        <option value="" disabled selected>Select a Program</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="progMajor" class="form-label">Major (If applicable)</label>
                    <select id="progMajor" class="form-select">
                        <option value="" disabled selected>Select a Major</option>
                    </select>
                </div>
            </div>

            <!-- Right column -->
            <div class="col-md-6">
                <div class="d-flex gap-3 mb-3">
                    <div class="d-flex flex-fill" style="width: 235px;">
                        <div class="flex-fill">
                            <label for="progAcadstart" class="form-label">Academic Year</label>
                            <div class="d-flex">
                                <input type="number" id="progAcadstart" class="form-control text-dark" placeholder="Start Year" required>
                                <span class="d-flex align-items-center mx-2">-</span>
                                <input type="number" id="progAcadend" class="form-control text-dark" placeholder="End Year" required readonly>
                            </div>
                        </div>
                    </div>

                    <div class="flex-fill">
                        <label for="progTerm" class="form-label">Term</label>
                        <select id="progTerm" class="form-select" required>
                            <option value="" disabled selected>Select Term</option>
                            <option value="1st">1st Semester</option>
                            <option value="2nd">2nd Semester</option>
                            <option value="SUMMER">Summer</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-3 mb-3">
                    <div class="flex-fill">
                        <label for="subject_autocomp" class="form-label">Course</label>
                        <input list="subjectOptions" id="subject_autocomp" name="combinedInput" class="form-control" placeholder="subject code or subject name" required>
                    </div>

                    <div class="flex-shrink-0" style="width: 120px;">
                        <label for="progYear" class="form-label">Year</label>
                        <input type="text" id="progYear" class="form-control text-light" readonly>
                    </div>
                </div>

                <div class="d-flex gap-3 mb-3">
                    <div class="text-center">
                        <label class="form-label fw-bold d-block">Units</label>
                        <div id="subjectUnits" class="form-control-plaintext text-center">0.0</div>
                    </div>
                    <div class="text-center">
                        <label class="form-label fw-bold d-block">Hours</label>
                        <div id="subjectHours" class="form-control-plaintext text-center">0.0</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="progSection" class="form-label">Section</label>
                    <input type="text" id="progSection" class="text-dark form-control">
                </div>
            </div>
        </div>

        <!-- Full-width Divider -->
        <hr class="my-4" style="border-top: 1px solid #1c1c1c;">

        <!-- ROW 2: Remaining Fields -->
        <div class="row g-4">
            <!-- Left column -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="schedulewk" class="form-label">Schedule (M,T,W,TH,F,SAT,SUN)</label>
                    <input type="text" id="schedulewk" class="form-control" placeholder="e.g. M" required>
                </div>

                <div class="row g-2 align-items-end">
                    <div class="col">
                        <label for="time_input1" class="form-label">Time from</label>
                        <input type="time" id="time_input1" class="form-control" required>
                    </div>
                    <div class="col">
                        <label for="time_input2" class="form-label">Time to</label>
                        <input type="time" id="time_input2" class="form-control" disabled required>
                    </div>
                </div>
            </div>

            <!-- Right column -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="room" class="form-label">Room (<i class="text-muted fs-7">Optional</i>)</label>
                    <select id="room" class="form-select" required>
                        <option value="" disabled selected>Select Room</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Hidden Inputs -->
        <input type="hidden" id="progSubjectid" class="text-light form-control">
        <input type="hidden" id="progDept" class="text-dark form-control">
        <input type="hidden" id="subjectCompo" class="text-dark form-control">

        <!-- Checkbox -->
        <div class="checkbox-container flex items-center mt-2 mb-4">
            <input type="checkbox" id="international" class="mr-2">
            <label for="international">Check for International/Additional subject offerings/schedules (offerings for all courses)</label>
        </div>

        <!-- Submit -->
        <div class="form-actions d-flex justify-content-center">
            <button type="button" onclick="submitProgramForm()" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>


        <script>
document.getElementById('progAcadstart').addEventListener('input', function () {
    const startYear = parseInt(this.value);
    const currentYear = new Date().getFullYear();
    const endYearInput = document.getElementById('progAcadend');

    if (!isNaN(startYear)) {
        if (startYear > currentYear) {
            this.value = currentYear;
            endYearInput.value = currentYear + 1;
        } else {
            endYearInput.value = startYear + 1;
        }
    } else {
        endYearInput.value = '';
    }
});
</script>
    <script>

   function submitProgramForm() {
    if (!$('#progCourses').val()) {
        showGenericModal('Program is required.');
        return false;
    }
    if (!$('#progProgram').val()) {
        showGenericModal('Academic Year is required.');
        return false;
    }
    if (!$('#progSubjectid').val()) {
        showGenericModal('Course Name or Course ID is required.');
        return false;
    }
    if (!$('#progCurr').val()) {
        showGenericModal('Curriculum is required.');
        return false;
    }
    if (!$('#progSection').val()) {
        showGenericModal('Section is required.');
        return false;
    }
    if (!$('#progYear').val()) {
        showGenericModal('Year is required.');
        return false;
    }
    if (!$('#progTerm').val()) {
        showGenericModal('Term is required.');
        return false;
    }
    if (!$('#progAcadstart').val()) {
        showGenericModal('Start Year is required.');
        return false;
    }
    if (!$('#progAcadend').val()) {
        showGenericModal('End Year is required.');
        return false;
    }
    if (!$('#schedulewk').val()) {
        showGenericModal('Schedule Week is required.');
        return false;
    }
    if (!$('#time_input1').val()) {
        showGenericModal('Time Input start is required.');
        return false;
    }
    if (!$('#time_input2').val()) {
        showGenericModal('Time Input end is required.');
        return false;
    }

        $.ajax({
            url: '../programAPI.php',
            type: 'POST',
            data: {
                action: 'insertProgram',
                progDept: $('#progDept').val(),
                progCourses: $('#progCourses').val(),
                progProgram: $('#progProgram').val(),
                subject_autocomp: $('#progSubjectid').val(),
                progMajor: $('#progMajor').val() ? 1 : 0,
                progCurr: $('#progCurr').val(),
                progSection: $('#progSection').val(),
                progYear: $('#progYear').val(),
                progTerm: $('#progTerm').val(),
                progAcadstart: $('#progAcadstart').val(),
                progAcadend: $('#progAcadend').val(),
                subjectcompo: $('#subjectCompo').val(),
                schedulewk: $('#schedulewk').val(),
                room: $('#room').val(),
                time_input1: $('#time_input1').val(),
                time_input2: $('#time_input2').val(),
                international: $('#international').is(':checked') ? 1 : 0
            },
            success: function (response) {
                    console.log('Server Response:', response);

                    try {
                        const jsonResponse = typeof response === 'string' ? JSON.parse(response) : response;

                        if (jsonResponse.status === 'success') {
                            showSuccessModal(jsonResponse.message);
                            $('form')[0].reset();
                        }
                            else if (jsonResponse.status === 'error') {
                            showConflictModal(jsonResponse.message);
                            } else {
                            alert('Unexpected server response. Please try again.');
                        }
                    } catch (e) {
                        console.error('Error parsing server response:', e);
                        alert('Failed to process server response. Check console for details.');
                    }
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

        <div id="conflictModal" class="fixed inset-0 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg p-6 max-w-sm w-full text-center shadow-lg">
    <h2 class="text-lg font-semibold mb-4">Schedule Conflict</h2>
    <p id="conflictMessage" class="mb-6 text-gray-700">Conflict message here</p>
    <button id="closeConflictModal" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
      OK
    </button>
  </div>
</div>

<div id="genericModal" class="fixed inset-0 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg p-6 max-w-sm w-full text-center shadow-lg">
    <h2 class="text-lg font-semibold mb-4">Validation Error</h2>
    <p id="genericMessage" class="mb-6 text-gray-700">Message goes here</p>
    <button id="closeGenericModal" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
      OK
    </button>
  </div>
</div>


<!-- Success Modal -->
<div id="successModal"  class="fixed inset-0 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg p-6 max-w-sm w-full text-center shadow-lg">
    <h2 class="text-lg font-semibold text-green-600 mb-4">Success</h2>
    <p id="successMessage" class="mb-6 text-gray-700">Substitution added successfully!</p>
    <button id="closeSuccessModal" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
      OK
    </button>
  </div>
</div>


</body>

</html>

<script src="../classprograms.js"></script>

<script>

        fetchDepartments();

</script>


<script>
  // Success Modal
  function showSuccessModal(message) {
    $('#successMessage').text(message);
    $('#successModal')
      .removeClass('hidden')
      .css('display', 'none')
      .addClass('flex')
      .fadeIn(200);
  }

  $('#closeSuccessModal').on('click', function () {
    $('#successModal').fadeOut(200, function () {
      $(this).removeClass('flex').addClass('hidden');
      location.reload(); // Reload after success
    });
  });

  // Conflict Modal
  function showConflictModal(message) {
    $('#conflictMessage').text(message);
    $('#conflictModal')
      .removeClass('hidden')
      .css('display', 'none')
      .addClass('flex')
      .fadeIn(200);
  }

  $('#closeConflictModal').on('click', function () {
    $('#conflictModal').fadeOut(200, function () {
      $(this).removeClass('flex').addClass('hidden');
    });
  });

  // Generic Modal (for validation)
  function showGenericModal(message) {
    $('#genericMessage').text(message);
    $('#genericModal')
      .removeClass('hidden')
      .css('display', 'none')
      .addClass('flex')
      .fadeIn(200);
  }

  $('#closeGenericModal').on('click', function () {
    $('#genericModal').fadeOut(200, function () {
      $(this).removeClass('flex').addClass('hidden');
    });
  });
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

    input {
        text-transform: uppercase;
    }

    #subject_autocomp {
        text-transform: none;
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
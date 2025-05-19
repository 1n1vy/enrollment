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

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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
                                <label for="classprogramsy1">Academic Year <b class="text-danger">*</b></label>
                                <input type="number" id="progAcadstart" placeholder="Enter" required>
                            </div>
                            <div class="label label-input" style="flex-grow: 0.2;">
                                <span>-</span>
                                <input type="number" id="progAcadend" placeholder="Enter" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="progDept">Department <b class="text-danger">*</b></label>
                            <select id="progDept" required>
                                <option value="" disabled selected>Select a Department</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex-grow: 0.2;">
                            
                            <div class="form-group" style="flex-grow: 1;">
                                <label for="year">Year</label>
                                <select id="year" required>
                                    <option value="" disabled selected>Select</option>
                                    <option value="1">1st</option>
                                    <option value="2">2nd</option>
                                    <option value="3">3rd</option>
                                    <option value="4">4th</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Right column inputs -->
                    <div>
                        <div class="form-group" style="flex-grow: 0.4;">
                            <label for="progCurr">Term</label>
                            <select id="progCurr" required>
                                <option value="" disabled selected>Select</option>
                                <option value="1st">1st Semester</option>
                                <option value="2nd">2nd Semester</option>
                                <option value="Summer">Summer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="progCourses">Program</label>
                            <select id="progCourses">
                                <option value="" disabled selected>Select Course</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- ADD Button -->
                <div class="form-actions mt-8 flex justify-center">
                    <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded-md">FILTER</button>
                </div>

                <!-- Table below the ADD Button -->
                <hr class="thick-separator mt-6">
                
                <div class="d-flex justify-end">
                    <a href="#" id="btnPrint2" title="Print" class="bg-blue-900 text-white rounded-md p-2">
                        <i class="bi bi-printer mr-2 text-lg"></i> Print
                    </a>
                </div>
                <div id="form_table_programs" class="overflow-x-auto">
                    <section class="section-header text-sm mt-6">
                        <h5 class="headerbody text-light">CLASS PROGRAM LIST</h5>
                        <p class="text-light" id="depttb"><b>College of Computer Studies</b></p>
                        <p class="text-light"><b id="coursetb">BSIT</b>(<i><b id="leveltb">4th Year</b></i>)</p>
                        <p class="text-light">S.Y. <b id="sytb">2025-2026</b>, <b id="termtb"></b></p>
                    </section>
                    <table class="min-w-full border border-gray-300">
                        <thead style="background-color: #174069;" class="text-white">
                            <tr>
                                <th class="py-2 px-4 border">Subject Code</th>
                                <th class="py-2 px-4 border">Subject</th>
                                <th class="py-2 px-4 border">Section</th>
                                <th class="py-2 px-4 border">Component</th>
                                <th class="py-2 px-4 border">Day</th>
                                <th class="py-2 px-4 border">Time</th>
                                <th class="py-2 px-4 border">Room</th>
                            </tr>
                        </thead>
                        <tbody>
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
                    var script = document.createElement('script');
                    script.src = '../../Components/app.js';
                    document.body.appendChild(script);
                });

                
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

            function updateEndYear() {
                const currYearSelect = document.getElementById('curryear');
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

<script>

$(document).ready(function () {

    function fetchDepartments() {
        $.ajax({
            url: 'http://localhost/capst/ClassProgram/programAPI.php',
            type: 'GET',
            data: {
                action: 'getDepartments'
            },
            success: function (response) {
                console.log('Departments Response:', response);

                var data = typeof response === 'string' ? JSON.parse(response) : response;

                var progDept = $('#progDept');
                progDept.empty();
                progDept.append('<option value="" disabled selected>Select a Department</option>');

                if (data.length > 0) {
                    data.forEach(function (department) {
                        progDept.append(
                            `<option value="${department.id}">${department.department_name}</option>`
                        );
                    });
                } else {
                    progDept.append('<option value="">No Departments Available</option>');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching departments:', xhr.responseText);
                alert('Failed to load departments. Please try again.');
            }
        });
    }

    function fetchCourses(departmentId) {
        $.ajax({
            url: 'http://localhost/capst/ClassProgram/programAPI.php',
            type: 'GET',
            data: {
                action: 'getCourses',
                id: departmentId
            },
            success: function (response) {
                console.log('Courses Response:', response);

                var data = typeof response === 'string' ? JSON.parse(response) : response;

                var progCourses = $('#progCourses');
                progCourses.empty();
                progCourses.append('<option value="" disabled selected>Select a Course</option>');

                if (data.length > 0) {
                    data.forEach(function (course) {
                        progCourses.append(
                            `<option value="${course.id}">${course.course_name}</option>`
                        );
                    });
                } else {
                    progCourses.append('<option value="">No Courses Available</option>');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching courses:', xhr.responseText);
                alert('Failed to load courses. Please try again.');
            }
        });
    }

    $('#progDept').on('change', function () {
        var departmentId = $(this).val();
        if (departmentId) {
            fetchCourses(departmentId);
        }
    });

    fetchDepartments();
});

$(document).ready(function () {
    function updateClassProgramDetails() {
        const department = $('#progDept option:selected').text();
        const course = $('#progCourses option:selected').text();
        const year = $('#year option:selected').text() || "All Level";
        const term = $('#progCurr option:selected').text() || "All Terms";
        const acadStart = $('#progAcadstart').val();
        const acadEnd = $('#progAcadend').val();

        $('#depttb').html(`<b>${department !== "" ? department : ""}</b>`);
        $('#coursetb').html(`<b>${course !== "" ? course : ""}</b>`);
        $('#leveltb').html(`<b>${year} Year</b>`);
        $('#termtb').html(`<b>${term} Semester</b>`);
        $('#sytb').html(`<b>${acadStart && acadEnd ? `${acadStart}-${acadEnd}` : ""}</b>`);
    }

    $('#progDept, #progCourses, #year, #progCurr, #progAcadstart, #progAcadend').on('change input', function () {
        updateClassProgramDetails();
    });

    updateClassProgramDetails();
});

$(document).ready(function () {

    $('form').on('submit', function (e) {
        e.preventDefault(); 

        const acadStart = $('#progAcadstart').val();
        const acadEnd = $('#progAcadend').val();
        const department = $('#progDept').val();
        const course = $('#progCourses').val();
        const year = $('#year').val();
        const term = $('#progCurr').val();

        if (!acadStart || !acadEnd || !department || !course) {
            alert('Please fill in all required fields.');
            return;
        }

        $.ajax({
            url: 'http://localhost/capst/ClassProgram/programAPI.php',
            type: 'GET',
            data: {
                action: 'getClassPrograms',
                acadStart: acadStart,
                acadEnd: acadEnd,
                department: department,
                course: course,
                year: year || 'All Level',
                term: term || 'All Terms'
            },
            success: function (response) {
                console.log('Class Programs Response:', response);

                const data = typeof response === 'string' ? JSON.parse(response) : response;

                const tableBody = $('table tbody');
                tableBody.empty();

                if (data.length > 0) {
                    data.forEach(function (program) {
                        tableBody.append(`
                            <tr>
                                <td class="py-2 px-4 border">${program.subject_code}</td>
                                <td class="py-2 px-4 border">${program.subject_name}</td>
                                <td class="py-2 px-4 border">${program.section}</td>
                                <td class="py-2 px-4 border">${program.subject_component}</td>
                                <td class="py-2 px-4 border">${program.schedule_day}</td>
                                <td class="py-2 px-4 border">${program.schedule_time}</td>
                                <td class="py-2 px-4 border">${program.room_number}</td>
                            </tr>
                        `);
                    });
                } else {
                    tableBody.append('<tr><td colspan="7" class="text-center py-2">No class programs found.</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching class programs:', xhr.responseText);
                alert('Failed to load class programs. Please try again.');
            }
        });
    });
});

$(document).ready(function () {
    $('#btnPrint2').on('click', function (e) {
        e.preventDefault();

        const content = document.getElementById('form_table_programs').innerHTML;

        const printWindow = window.open('', '_blank', 'width=800,height=600');

        printWindow.document.open();
        printWindow.document.write(`
            <html>
                <head>
                    <title>Print Class Program</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 20px;
                        }
                        .section-header {
                            text-align: center;
                            margin-bottom: 20px;
                        }
                        .section-header h5 {
                            font-size: 20px;
                            font-weight: bold;
                            margin-bottom: 10px;
                        }
                        .section-header p {
                            margin: 0;
                            font-size: 16px;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 20px;
                        }
                        th, td {
                            border: 1px solid #ccc;
                            padding: 8px;
                            text-align: left;
                        }
                        th {
                            background-color: #174069;
                            color: white;
                        }
                        .text-light {
                            color: #555 !important;
                        }
                    </style>
                </head>
                <body>
                    ${content}
                </body>
            </html>
        `);
        printWindow.document.close();

        printWindow.print();

        printWindow.onafterprint = function () {
            printWindow.close();
        };
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
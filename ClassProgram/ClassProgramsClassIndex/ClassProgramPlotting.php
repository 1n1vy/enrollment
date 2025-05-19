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
                <div class="form-container">
                    <div class="form-group">
                        <label for="classprogramsy1">Academic Year<b class="text-danger">*</b></label>
                        <div class="d-flex align-items-center">
                            <input type="number" id="progAcadstart" class="form-control me-2" placeholder="Enter" required>
                            <span class="mx-2">-</span>
                            <input type="number" id="progAcadend" class="form-control" placeholder="Enter" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="progDept">Department <b class="text-danger">*</b></label>
                        <select id="progDept" class="form-control" required>
                            <option value="" disabled selected>Select a Department</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="progCourses">Program</label>
                        <select id="progCourses" class="form-control">
                            <option value="" disabled selected>Select Program</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="year">Year</label>
                        <select id="year" class="form-control" required>
                            <option value="" disabled selected>Select</option>
                            <option value="1">1st</option>
                            <option value="2">2nd</option>
                            <option value="3">3rd</option>
                            <option value="4">4th</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="progCurr">Term</label>
                        <select id="progCurr" class="form-control" required>
                            <option value="" disabled selected>Select</option>
                            <option value="1st">1st Semester</option>
                            <option value="2nd">2nd Semester</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions mt-8 flex justify-center">
                    <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded-md">FILTER</button>
                </div>

                <hr class="thick-separator mt-6">
                
                <div class="d-flex justify-end">
                    <a href="#" id="btnPrint2" title="Print" class="bg-blue-900 text-white rounded-md p-2">
                        <i class="bi bi-printer mr-2 text-lg"></i> Print
                    </a>
                </div>
                <div id="form_table_programs" class="overflow-x-auto">
                    <section class="section-header text-sm mt-6">
                        <h5 class="headerbody text-light">CLASS PROGRAM PLOTTING</h5>
                        <p class="text-light" id="depttb"><b>College of Computer Studies</b></p>
                        <p class="text-light"><b id="coursetb">BSIT</b>(<i><b id="leveltb">4th Year</b></i>)</p>
                        <p class="text-light">S.Y. <b id="sytb">2025-2026</b>, <b id="termtb"></b></p>
                    </section>
                    <table class="min-w-full border border-gray-300">
                        <thead style="background-color: #174069;" class="text-white">
                            <tr>
                                <th class="py-2 px-4 border">Time</th>
                                <th class="py-2 px-4 border">Monday</th>
                                <th class="py-2 px-4 border">Tuesday</th>
                                <th class="py-2 px-4 border">Wednesday</th>
                                <th class="py-2 px-4 border">Thursday</th>
                                <th class="py-2 px-4 border">Friday</th>
                                <th class="py-2 px-4 border">Saturday</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

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

        if (!acadStart || !acadEnd || !department || !course || !year || !term) {
            alert('Please fill in all required fields.');
            return;
        }

        $.ajax({
            url: 'http://localhost/capst/ClassProgram/programAPI.php',
            type: 'GET',
            data: {
                action: 'getClassSchedule',
                acadStart: acadStart,
                acadEnd: acadEnd,
                department: department,
                course: course,
                year: year,
                term: term
            },
            success: function (response) {
                console.log('Schedule Response:', response);

                const data = typeof response === 'string' ? JSON.parse(response) : response;

                const tableBody = $('table tbody');
                tableBody.empty();

                const timeSlots = [
                    '7:00 AM - 8:00 AM', '8:00 AM - 9:00 AM', '9:00 AM - 10:00 AM', '10:00 AM - 11:00 AM',
                    '11:00 AM - 12:00 PM', '12:00 PM - 1:00 PM', '1:00 PM - 2:00 PM', '2:00 PM - 3:00 PM',
                    '3:00 PM - 4:00 PM', '4:00 PM - 5:00 PM', '5:00 PM - 6:00 PM', '6:00 PM - 7:00 PM',
                    '7:00 PM - 8:00 PM', '8:00 PM - 9:00 PM', '9:00 PM - 10:00 PM'
                ];

                const dayMapping = {
                    M: 'Monday',
                    T: 'Tuesday',
                    W: 'Wednesday',
                    TH: 'Thursday',
                    F: 'Friday',
                    SAT: 'Saturday',
                };

                timeSlots.forEach(time => {
                    const row = $('<tr>');
                    row.append(`<td class="py-2 px-4 border">${time}</td>`);

                    ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'].forEach(day => {
                        const cell = $('<td class="py-2 px-4 border"></td>');

                        // Find matching schedules for this time and day
                        const schedules = data.filter(item => {
                            const days = item.schedule_day.split(','); // Split "M,T,SAT" into ["M", "T", "SAT"]
                            return days.some(d => dayMapping[d.trim()] === day) && isTimeInSlot(item.schedule_time, time);
                        });

                        if (schedules.length > 0) {
                            // Append all matching schedules into the cell
                            schedules.forEach(schedule => {
                                cell.append(`${schedule.subject_code}<br>${schedule.section}<br>`);
                            });
                        }

                        row.append(cell);
                    });

                    tableBody.append(row);
                });
            },
            error: function (xhr, status, error) {
                console.error('Error fetching schedule:', xhr.responseText);
                alert('Failed to load schedule. Please try again.');
            }
        });
    });

    function isTimeInSlot(scheduleTime, slotTime) {
        const [scheduleStart, scheduleEnd] = scheduleTime.split(' - ').map(t => t.trim());
        const [slotStart, slotEnd] = slotTime.split(' - ').map(t => t.trim());

        const scheduleStartTime = parseTime(scheduleStart);
        const scheduleEndTime = parseTime(scheduleEnd);
        const slotStartTime = parseTime(slotStart);
        const slotEndTime = parseTime(slotEnd);

        return (
            (slotStartTime >= scheduleStartTime && slotStartTime < scheduleEndTime) ||
            (slotEndTime > scheduleStartTime && slotEndTime <= scheduleEndTime) ||
            (scheduleStartTime >= slotStartTime && scheduleEndTime <= slotEndTime)
        );
    }

    function parseTime(timeStr) {
        const [time, modifier] = timeStr.split(' ');
        let [hours, minutes] = time.split(':').map(Number);

        if (modifier === 'PM' && hours !== 12) {
            hours += 12;
        } else if (modifier === 'AM' && hours === 12) {
            hours = 0;
        }

        return new Date(0, 0, 0, hours, minutes);
    }
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
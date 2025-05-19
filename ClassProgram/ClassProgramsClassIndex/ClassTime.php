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
    <title>Class Time</title>
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
                <li class="active">Class Time</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>CLASS TIME</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form action="#">
                <div class="form-container">
                    <div class="form-group">
                        <label for="classprogramsy1">Academic Year <b class="text-danger">*</b></label>
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
                        <label for="progCourses">Program <b class="text-danger">*</b></label>
                        <select id="progCourses" class="form-control">
                            <option value="" disabled selected>Select Program</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="progSection">Section <b class="text-danger">*</b></label>
                        <select id="progSection" class="form-control" required>
                            <option value="" disabled selected>Select Section</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="progCurr">Term <b class="text-danger">*</b></label>
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
                        <h5 class="headerbody text-light">CLASS TIME SCHEDULE</h5>
                        <p class="text-light" id="depttb"><b>College of Computer Studies</b></p>
                        <p class="text-light"><b id="coursetb">BSIT</b>(<i><b id="leveltb"></b></i>)</p>
                        <p class="text-light">A.Y. <b id="sytb">2025-2026</b>, <b id="termtb"></b></p>
                    </section>
                    <table class="min-w-full border border-gray-300">
                        <thead style="background-color: #174069;" class="text-white">
                            <tr>
                                <th class="py-2 px-4 border">SUBJECT CODE</th>
                                <th class="py-2 px-4 border">DESCRIPTION</th>
                                <th class="py-2 px-4 border">UNIT</th>
                                <th class="py-2 px-4 border">SCHEDULE</th>
                                <th class="py-2 px-4 border">ROOM</th>
                                <th class="py-2 px-4 border">FACULTY</th>
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
                progCourses.append('<option value="" disabled selected>Select a Program</option>');

                if (data.length > 0) {
                    data.forEach(function (course) {
                        progCourses.append(
                            `<option value="${course.id}">${course.course_name}</option>`
                        );
                    });
                } else {
                    progCourses.append('<option value="">No Programs Available</option>');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching courses:', xhr.responseText);
                alert('Failed to load courses. Please try again.');
            }
        });
    }
    function fetchSections(courseId, departmentId) {
        $.ajax({
            url: 'http://localhost/capst/ClassProgram/programAPI.php',
            type: 'GET',
            data: {
                action: 'getSections2',
                course_id: courseId,
                department_id: departmentId
            },
            success: function (response) {
                console.log('Courses Response:', response);

                var data = typeof response === 'string' ? JSON.parse(response) : response;

                var progCourses = $('#progSection');
                progCourses.empty();
                progCourses.append('<option value="" disabled selected>Select a Section</option>');

                if (data.length > 0) {
                    data.forEach(function (course) {
                        progCourses.append(
                            `<option value="${course.section}">${course.section}</option>`
                        );
                    });
                } else {
                    progCourses.append('<option value="">No Section Available</option>');
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
    

    $('#progCourses').on('change', function () {
        var courseId = $(this).val();
        var deptId = $('#progDept').val();
        if (courseId) {
            fetchSections(courseId, deptId);
        }
    });

    fetchDepartments();
});

$(document).ready(function () {
    function updateClassProgramDetails() {
        const department = $('#progDept option:selected').text();
        const course = $('#progCourses option:selected').text();
        const year = $('#progSection option:selected').text() || "Section";
        const term = $('#progCurr option:selected').text() || "All Terms";
        const acadStart = $('#progAcadstart').val();
        const acadEnd = $('#progAcadend').val();

        $('#depttb').html(`<b>${department !== "" ? department : ""}</b>`);
        $('#coursetb').html(`<b>${course !== "" ? course : ""}</b>`);
        $('#leveltb').html(`<b>${year}</b>`);
        $('#termtb').html(`<b>${term} Semester</b>`);
        $('#sytb').html(`<b>${acadStart && acadEnd ? `${acadStart}-${acadEnd}` : ""}</b>`);
    }

    $('#progDept, #progCourses, #progSection, #progCurr, #progAcadstart, #progAcadend').on('change input', function () {
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
        const section = $('#progSection').val();
        const term = $('#progCurr').val();

        console.log('sy:', acadStart+'-'+acadEnd);
        console.log('department:', department);
        console.log('course:', course);
        console.log('section:', section);
        console.log('term:', term);
        

        if (!acadStart || !acadEnd || !department || !course) {
            alert('Please fill in all required fields.');
            return;
        }

        $.ajax({
            url: 'http://localhost/capst/ClassProgram/programAPI.php',
            type: 'GET',
            data: {
                action: 'getClassPrograms2',
                acadStart: acadStart,
                acadEnd: acadEnd,
                department: department,
                course: course,
                section: section,
                term: term
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
                                <td class="py-2 px-4 border">${program.units}.00</td>
                                <td class="py-2 px-4 border">${program.schedule_day} - ${program.schedule_time}</td>
                                <td class="py-2 px-4 border">${program.room_number ? program.room_number : 'TBA'}</td>
                                <td class="py-2 px-4 border">${program.faculty_name ? program.faculty_name : 'TBA'}</td>
                            </tr>
                        `);
                    });
                } else {
                    tableBody.append('<tr><td colspan="6" class="py-2 px-4 border text-center">No data available</td></tr>');
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
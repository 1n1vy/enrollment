<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../includes/auth.php';
redirectIfNotLoggedIn();

// Optionally, restrict access by role
checkRole(['Admin', 'Faculty', 'Registrar', 'Building Manager']);

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
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
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
                <li><a href="#">Class Programs</a></li>
                <li class="active">Per Subject Edit Page</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h5 class="text-light">PER SUBJECT - VIEW/EDIT/DELETE/PRINT PAGE</h5>
        </section>

        <!-- Form container -->
        <div class="form-container">
        <form action="#" id="filter-form">
            <div class="d-flex flex-row w-100 justify-content-between">
                <!-- Department -->
                <div class="form-group flex-grow-1 mx-2">
                    <label for="progDept" class="form-label">Department</label>
                    <select id="progDept" class="form-select" required>
                        <option value="" disabled selected>Select a Department</option>
                    </select>
                </div>

                <!-- Course -->
                <div class="form-group flex-grow-1 mx-2">
                    <label for="progCourses" class="form-label">Course</label>
                    <select id="progCourses" class="form-select" required>
                        <option value="" disabled selected>Select a Course</option>
                    </select>
                </div>

                <!-- Subject -->
                <div class="form-group flex-grow-1 mx-2">
                    <label for="subject_autocomp" class="form-label">Subject</label>
                    <input list="options" id="subject_autocomp" name="combinedInput" class="form-control" placeholder="subject code or subject name" required>
                    
                    <input type="hidden" id="subject_id" name="combinedInput" class="form-control" placeholder="subject code or subject name">
                </div>
            </div>

            <!-- ADD Button -->
            <div class="form-actions mt-8 flex justify-content-center mb-5">
                <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded-md btn btn-success">Proceed</button>
            </div>
        </form>

        
        <div class="form-actions mt-8 flex justify-content-end mb-5">
                <a href="#" id="btnPrint2" title="Print" class="btn btn-primary">
                    <i class="bi bi-printer mr-2 text-lg"></i> Print
                </a>
            </div>
        <section class="section-header text-sm mt-6">
            <h5 class="text-light">SUBJECT OFFERED BY COLLEGES</h5>
        </section>
        <table id="program-table" class="min-w-full border border-gray-300 w-100">
            <thead style="background-color: #174069;" class="text-white">
                <tr>
                    <th class="p-1 border">COLLEGE</th>
                    <th class="p-1 border">SUBJECT</th>
                    <th class="p-1 border">SUBJECT CODE</th>
                    <th class="p-1 border">SEMESTER</th>
                    <th class="p-1 border">COMPONENT</th>
                    <th class="p-1 border">YEAR LEVEL</th>
                    <th class="p-1 border">TOTAL UNITS</th>
                    <th class="p-1 border">SECTION</th>
                    <th class="p-1 border">SCHEDULE</th>
                    <th class="p-1 border">ACTION</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <p class="modal-title" id="exampleModalCenterTitle"><b>Delete Program</b></p>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="deleteProgram">
                            <div class="col-12">
                                <p id="course-error" class="text-light w-100 badge badge-danger mt-2 mb-2"></p>
                            </div>
                            <div class="col-12">
                                <p>Are you sure you want to delete this Program? this process is irreversible proceed?</p>
                                <input type="hidden" class="form-control mb-1 text-dark" name="program_id" id="program_id" readonly >
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="deleteProgram" id="encodeButton" class="btn btn-danger">Delete</button>
                        <button class="btn btn-muted" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End -->



        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>

        fetchDepartments();

$(document).ready(function () {

    $('#deleteProgram').on('submit', function (e) {
    
        e.preventDefault();

        var programId = $('#program_id').val();

        $.ajax({
            url: 'programAPI.php',
            type: 'POST',
            data: { action: 'deleteProgram',program_id: programId },
            success: function (response) {
                location.reload();

                alert('Program deleted successfully!');
            },
            error: function () {
                alert('Error deleting program.');
            }
        });
    });
});

$(document).ready(function() {
    $('#filter-form').on('submit', function(e) {
        e.preventDefault();

        console.log("Ok");

        let department = $('#progDept').val();
        let course = $('#progCourses').val();
        let subject = $('#subject_id').val();

        console.log("dept: "+department);
        console.log("course: "+course);
        console.log("subject: "+subject);
        

        $.ajax({
            url: 'programAPI.php',
            method: 'GET',
            data: {
                action: 'getFilteredPrograms',
                department_id: department,
                course_id: course,
                subject_id: subject 
            },
            success: function(response) {
                let tableBody = $('#program-table tbody');
                tableBody.empty(); 

                if(response.length === 0) {
                    let row = `<tr class="text-gray-700 bg-white">
                        <td class="p-1 border text-center" colspan="10">No Data Found</td>
                    </tr>`;
                    tableBody.append(row);
                    
                }

                response.forEach(function(program) {
                    let row = `<tr class="text-gray-700 bg-white">
                        <td class="p-1 border text-center">${program.department_name}</td>
                        <td class="p-1 border text-center">${program.subject_name}</td>
                        <td class="p-1 border text-center">${program.subject_code}</td>
                        <td class="p-1 border text-center">${program.term} Semester</td>
                        <td class="p-1 border text-center">${program.subject_component}</td>
                        <td class="p-1 border text-center">${program.year_level} Year</td>
                        <td class="p-1 border text-center">${program.units}.00</td>
                        <td class="p-1 border text-center">${program.section_name}</td>
                        <td class="p-1 border text-center">${program.schedule_day} - ${program.schedule_time}</td>
                        <td class="p-1 border text-center">
                            <a href="NewClassPrograms/EditProgram.php?program_id=${program.program_id}"><i class="bi bi-pencil-square" style="cursor: pointer;" title="Edit"></i></a>
                            <i class="bi bi-trash" style="cursor: pointer;" title="Delete" data-bs-toggle="modal" data-id="${program.program_id}" data-bs-target="#deleteModal"></i>
                        </td>
                    </tr>`;
                    tableBody.append(row);
                });
            },
            error: function(xhr, status, error) {
                console.log('Error:', error);
            }
        });
    });

});

document.addEventListener('DOMContentLoaded', function () {
        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;

            var programId = button.getAttribute('data-id');

            var input = deleteModal.querySelector('#program_id');
            input.value = programId;
        });
    });
    $(document).on("click", "#btnPrint2", function (e) {
    e.preventDefault();

    let content = $("#program-table tbody").html();

    let printWindow = window.open("", "", "width=1200,height=800");

    printWindow.document.write(`
        <html>
        <head>
            <title>SUBJECT OFFERED</title>
            <style>
                @media print {
                    @page {
                        size: landscape;
                        margin: 1cm;
                    }
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        padding: 8px;
                        border: 1px solid #ccc;
                        text-align: center;
                    }
                    th {
                        background-color: #174069;
                        color: white;
                    }
                    .bg-gray-200 {
                        background-color: #f2f2f2;
                    }

                    /* 👇 Hide last column */
                    thead th:last-child,
                    tbody td:last-child {
                        display: none;
                    }
                }
            </style>
        </head>
        <body>
            <h5>SUBJECT OFFERED BY COLLEGE</h5>
            <table class="min-w-full border border-gray-300 w-100">
                <thead>
                    ${$("#program-table thead").html()} <!-- Include thead properly -->
                </thead>
                <tbody>
                    ${content}
                </tbody>
            </table>
        </body>
        </html>
    `);

    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
});

function fetchDepartments() {
    $.ajax({
        url: './programAPI.php',
        type: 'GET',
        data: {
            action: 'getDepartments'
        },
        success: function (response) {

            console.log('Response:', response);
            console.log('Type:', typeof response);

            var data = typeof response === 'string' ? JSON.parse(response) : response;

            var collegeSelect = $('#progDept');
            collegeSelect.empty();
            collegeSelect.append('<option value="">Select College</option>');

            if (data.length > 0) {
                data.forEach(function (department) {
                    collegeSelect.append(
                        `<option value="${department.id}">${department.department_name}</option>`
                    );
                });
            } else {
                collegeSelect.append('<option value="">No Departments Available</option>');
            }
        },
        error: function (xhr, status, error) {
            console.log('Error Status:', status);
            console.log('XHR Response:', xhr.responseText);
            console.log('Error:', error);
            alert('Error loading departments. Check console for details.');
        }
    });
    
    $('#progDept').on('change', function () {

        var selectedValue = $(this).val();
        var selectedText = $('#college option:selected').text();
        console.log('Selected College ID:', selectedValue);
        console.log('Selected College Name:', selectedText);
        $('#subject_autocomp').val('');
        $('#professor_autocomp').val('');

        $.ajax({ 
            url: './programAPI.php',
            type: 'GET',
            data: {
                action: 'getCourses',
                id: selectedValue
            },
            success: function (response) {

                console.log('Response:', response);
                console.log('Type:', typeof response);

                var data = typeof response === 'string' ? JSON.parse(response) : response;

                var collegeSelect = $('#progCourses');
                collegeSelect.empty();
                collegeSelect.append('<option value="">Select Course</option>');

                if (data.length > 0) {
                    data.forEach(function (course) {
                        collegeSelect.append(
                            `<option value="${course.id}">${course.course_name}</option>`
                        );
                    });
                } else {
                    collegeSelect.append('<option value="">No Course Available</option>');
                }
            },
            error: function (xhr, status, error) {
                console.log('Error Status:', status);
                console.log('XHR Response:', xhr.responseText);
                console.log('Error:', error);
                alert('Error loading departments. Check console for details.');
            }
        });
        $.ajax({ 
            url: './programAPI.php',
            type: 'GET',
            data: {
                action: 'getProgram',
            },
            success: function (response) {

                console.log('Response:', response);
                console.log('Type:', typeof response);

                var data = typeof response === 'string' ? JSON.parse(response) : response;

                var collegeSelect = $('#progProgram');
                collegeSelect.empty();
                collegeSelect.append('<option value="">Select Program</option>');

                if (data.length > 0) {
                    data.forEach(function (course) {
                        collegeSelect.append(
                            `<option value="${course.id}">${course.level_name}</option>`
                        );
                    });
                } else {
                    collegeSelect.append('<option value="">No Program Available</option>');
                }
            },
            error: function (xhr, status, error) {
                console.log('Error Status:', status);
                console.log('XHR Response:', xhr.responseText);
                console.log('Error:', error);
                alert('Error loading departments. Check console for details.');
            }
        });
        
        $('#progCourses').on('change', function () {

            console.log($('#progCourses').val());
            
            $.ajax({ 
                url: './programAPI.php',
                type: 'GET',
                data: {
                    action: 'getCurriculum',
                    course_id: $('#progCourses').val(),
                },
                success: function (response) {
    
                    console.log('Response:', response);
                    console.log('Type:', typeof response);
    
                    var data = typeof response === 'string' ? JSON.parse(response) : response;
    
                    var collegeSelect = $('#progCurr');
                    collegeSelect.empty();
                    collegeSelect.append('<option value="">Select Curriculum</option>');
    
                    if (data.length > 0) {
                        data.forEach(function (course) {
                            collegeSelect.append(
                                `<option value="${course.id}">${course.curriculum_year_start} - ${course.curriculum_year_end}</option>`
                            );
                        });
                    } else {
                        collegeSelect.append('<option value="">No Curriculum Available</option>');
                    }
                },
                error: function (xhr, status, error) {
                    console.log('Error Status:', status);
                    console.log('XHR Response:', xhr.responseText);
                    console.log('Error:', error);
                    alert('Error loading departments. Check console for details.');
                }
            });

            $.ajax({ 
                url: './programAPI.php',
                type: 'GET',
                data: {
                    action: 'getSections',
                    course_id: $('#progCourses').val(),
                },
                success: function (response) {
    
                    console.log('Response:', response);
                    console.log('Type:', typeof response);
    
                    var data = typeof response === 'string' ? JSON.parse(response) : response;
    
                    var collegeSelect = $('#progSection');
                    collegeSelect.empty();
                    collegeSelect.append('<option value="">Select Section</option>');
    
                    if (data.length > 0) {
                        data.forEach(function (course) {
                            collegeSelect.append(
                                `<option data-sy="${course.academic_year}" data-level="${course.year_level}" data-semester="${course.semester}" value="${course.section_id}">${course.section_name}</option>`
                            );
                        });
                    } else {
                        collegeSelect.append('<option value="">No Section Available</option>');
                    }
                },
                error: function (xhr, status, error) {
                    console.log('Error Status:', status);
                    console.log('XHR Response:', xhr.responseText);
                    console.log('Error:', error);
                    alert('Error loading departments. Check console for details.');
                }
            });

        });
        
        $('#progSection').on('change', function () {

            var selected = $(this).find('option:selected');
            var year = selected.data('level');
            var term = selected.data('semester');
            var acad = selected.data('sy');
        
            $('#progYear').val(year);
            $('#progTerm').val(term);
        
            if (acad && acad.includes('-')) {
                var parts = acad.split('-');
                $('#progAcadstart').val(parts[0]);
                $('#progAcadend').val(parts[1]);
            } else {
                $('#progAcadstart').val('');
                $('#progAcadend').val('');
            }
        });

    });

    $('#progProgram').on('change', function () {
        
        console.log($('#progCourses').val());
        console.log($('#progProgram').val());

        $.ajax({ 
            url: './programAPI.php',
            type: 'GET',
            data: {
                action: 'getMajor',
                course_id: $('#progCourses').val(),
                education_level_id: $('#progProgram').val()
            },

            
            success: function (response) {

                console.log('Response:', response);
                console.log('Type:', typeof response);

                var data = typeof response === 'string' ? JSON.parse(response) : response;

                var collegeSelect = $('#progMajor');
                collegeSelect.empty();
                collegeSelect.append('<option value="0">Select Major</option>');

                if (data.length > 0) {
                    data.forEach(function (course) {
                        collegeSelect.append(
                            `<option value="${course.id}">${course.major_name}</option>`
                        );
                    });
                } else {
                    collegeSelect.append('<option value="0">No Major Available</option>');
                }
            },
            error: function (xhr, status, error) {
                console.log('Error Status:', status);
                console.log('XHR Response:', xhr.responseText);
                console.log('Error:', error);
                alert('Error loading departments. Check console for details.');
            }
        });

    });

    $('#subject_autocomp').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: './programAPI.php',
                type: 'GET',
                data: {
                    action: 'getSubjects2',
                    search: request.term,
                    department_id: $('#progDept').val()
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            label: item.subject_code + ' - ' + item.subject_name,
                            value: item.subject_code,
                            units: item.units,
                            id: item.id
                        };
                    }));
                },
                error: function () {
                    console.error('Error fetching subjects.');
                }
            });
        },
        minLength: 1,
        select: function(event, ui) {
            $("#subject_id").val(ui.item.id);
            console.log(ui.item.id);
            
        }
    });

    function getSubjectUnits(subject_code) {
        $.ajax({
            url: '../FacultyCanTeach1/api/facultyAPI.php',
            type: 'GET',
            data: {
                action: 'getSubjectDetails',
                subject_code: subject_code
            },
            success: function (data) {
                if (data.length > 0) {
                    $('#units_autocomp').val(data[0].units);
                } else {
                    $('#units_autocomp').val('');
                }
            },
            error: function () {
                console.error('Error fetching subject details.');
            }
        });
    }
}

        // Load the navbar
        $(document).ready(function() {
            loadNavbar();
        });

        // Function to load the navbar
        function loadNavbar() {
            $('#navbar-placeholder').load('../Components/Navbar.php', function(response, status, xhr) {
                if (status == "error") {
                    console.log("Error loading navbar: " + xhr.status + " " + xhr.statusText);
                }
            });
        }

</script>
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
        width: 100%;
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
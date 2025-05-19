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
    <title>Student Status Masterlist</title>
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
                <li><a href="#">Reports</a></li>
                <li class="active">Student Status Masterlist</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h5 class="text-light">Student Status Masterlist</h5>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form action="enrolled_stud_masterlist.php" method="POST">
                <h5 class="font-bold mb-4" style="margin-top: 25px; margin-bottom: 15px; margin-left: -15px;">FILTER BY:</h5>

                <!-- Student Status -->
                <div class="mb-3">
                    <label for="studStatus" class="form-label">Student Status</label>
                    <select id="studStatus" name="studStatus" class="form-select" required>
                        <option value="" disabled selected>Select Student Status</option>
                        <option value="enrolled">Enrolled</option>
                        <option value="not enrolled">Not Enrolled</option>
                    </select>
                </div>

                <!-- College -->
                <div class="mb-3">
                    <label for="progDept" class="form-label">Department</label>
                    <select id="progDept" name="progDept" class="form-select">
                        <option value="" disabled selected>Select a Department</option>
                        <!-- Populate options dynamically -->
                    </select>
                </div>

                <!-- Program -->
                <div class="mb-3">
                    <label for="progCourses" class="form-label">Course Program</label>
                    <select id="progCourses" name="progCourses" class="form-select">
                        <option value="" disabled selected>Select Course</option>
                        <!-- Populate options dynamically -->
                    </select>
                </div>

                <!-- School Year -->
                <div class="mb-4 flex items-center space-x-2">
                    <div class="flex-1">
                        <label class="block font-bold text-sm mb-2">SCHOOL YEAR</label>
                        <div class="flex items-center">
                            <input type="number" id="yearstart" name="yearstart" placeholder="Enter" class="w-full p-2 border border-gray-300 rounded" required>
                            <span class="mx-2 font-bold">TO</span>
                            <input type="number" id="yearend" name="yearend" placeholder="Enter" class="w-full p-2 border border-gray-300 rounded" required>
                        </div>
                    </div>
                </div>

                <!-- Term -->
                <div class="mb-3">
                    <label for="term" class="form-label">Term</label>
                    <select id="term" name="term" class="form-select" required>
                        <option value="" disabled selected>Select Semester Term</option>
                        <option value="1st">1st Semester</option>
                        <option value="2nd">2nd Semester</option>
                        <option value="Summer">Summer</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>
    
<script>
document.getElementById('yearstart').addEventListener('input', function () {
    const startYear = parseInt(this.value);
    const currentYear = new Date().getFullYear();
    const endYearInput = document.getElementById('yearend');

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

        function showAdditionalFields() {
            // Display the hidden information section
            document.getElementById("additional-fields-status").style.display = "block";
        }
    </script>
    

<script>

    fetchDepartments();

function fetchDepartments() {


$.ajax({
    url: 'programAPI.php',
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
        url: 'programAPI.php',
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
        url: 'programAPI.php',
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
            url: 'programAPI.php',
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
            url: 'programAPI.php',
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
        url: '../programAPI.php',
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
            url: '../programAPI.php',
            type: 'GET',
            data: {
                action: 'getSubjects',
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
        $("#progSubjectid").val(ui.item.id); 
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

function preFill(id) {
$.ajax({
    url: '../programAPI.php',
    type: 'GET',
    data: {
        action: 'getProgrambyID',
        program_id: id
    },
    success: function (response) {
        var data = typeof response === 'string' ? JSON.parse(response) : response;
        console.log('Prefill Data:', data);

        if (data[0]) {
            var departmentId = data[0].department_id;
            var programId = data[0].program_id;
            var courseId = data[0].course_id;
            var curriculumId = data[0].curriculum_id;
            var majorId = data[0].major_id;
            var sectionId = data[0].section_id;

            if (departmentId) {
                $('#progDept').val(departmentId).trigger('change');
                console.log('Department set to:', departmentId);
            }

            if (programId) {
                var programOptions = $('#progProgram option');
                var isProgramIdFound = false;
                programOptions.each(function () {
                    if ($(this).val() == programId) {
                        isProgramIdFound = true;
                    }
                });

                if (isProgramIdFound) {
                    $('#progProgram').val(programId).trigger('change');
                    console.log('Program set to:', programId);
                } else {
                    console.error('No matching program_id found in #progProgram options');
                }
            }

            if (courseId) {
                $('#progCourses').val(courseId).trigger('change');
                console.log('Course set to:', courseId);
            }

            if (curriculumId) {
                $('#progCurr').val(curriculumId).trigger('change');
                console.log('Curriculum set to:', curriculumId);
            }

            if (sectionId) {
                $('#progSection').val(sectionId).trigger('change');
                console.log('Section set to:', sectionId);
            }

            if (majorId) {
                $('#progMajor').val(majorId).trigger('change');
                console.log('Major set to:', majorId);
            }
            
            var schoolYear = String(data[0].school_year).trim();
            if (schoolYear && schoolYear.includes('-')) {
                var schoolYearParts = schoolYear.split('-');
                console.log('Start Year:', schoolYearParts[0]);
                console.log('End Year:', schoolYearParts[1]);

                $('#progAcadstart').val(schoolYearParts[0]);
                $('#progAcadend').val(schoolYearParts[1]);
            } else {
                console.error('Invalid school year format:', schoolYear);
            }

            $('#subcomponent').val(data[0].subject_component).trigger('change');
            $('#subjectofferingtype').val(data[0].subject_type).trigger('change');
            $('#progYear').val(data[0].year_level);
            $('#progTerm').val(data[0].term);
            $('#schedulewk').val(data[0].schedule_day);
            var scheduleTime = data[0].schedule_time;
            var timeParts = scheduleTime.split(' - ');
            
            $('#time_input1').val(timeParts[0]); 
            
            $('#time_input2').val(timeParts[1]); 

            if (data[0].academic_year && data[0].academic_year.includes('-')) {
                var parts = data[0].academic_year.split('-');
                $('#progAcadstart').val(parts[0]);
                $('#progAcadend').val(parts[1]);
            } else {
                $('#progAcadstart').val('');
                $('#progAcadend').val('');
            }
        } else {
            console.error('No data returned from API');
        }
    },
    error: function () {
        console.error('Error fetching program details');
    }
});
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
        width: 50%;
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

    /* Button styles */
    .form-actions button {
        padding: 12px 20px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
</style>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../../includes/auth.php';
redirectIfNotLoggedIn();

checkRole(['Admin', 'Faculty', 'Registrar']);

if (file_exists('../../includes/db_connection.php')) {
    require_once '../../includes/db_connection.php';
} else {
    die('Database connection file not found!');
}
if (!isset($_SESSION['username'])) {
    header("Location: http://localhost/capst/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Directory Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>

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
                <li><a href="#">Faculty</a></li>
                <li class="active">Faculty Loading/Scheduling</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>FACULTY PAGE - LOADING/SCHEDULING</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form class="bg-white p-6 shadow-md rounded-md border border-gray-300">
                <!-- College Offered -->
                 <div class="mb-4">
                    <label for="college" class="block font-bold text-sm mb-2">COLLEGE*</label>
                    <select id="college" class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                        <option value="" selected disabled>Select College</option>
                    </select>
                </div>
                <!-- Course Offered -->
                 <div class="mb-4">
                    <label for="courses" class="block font-bold text-sm mb-2">COURSE*</label>
                    <select id="courses" class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                        <option value="" selected disabled>Select Course</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="professor" class="block font-bold text-sm mb-2">Professor*</label>
                    <input type="text" class="w-full p-2 border border-gray-300 rounded"
                        id="professor_autocomp" placeholder="Employee ID, Firstname, Lastname" />
                </div>
                
                <input type="hidden" class="w-full p-2 border border-gray-300 rounded" id="professor_id" name="professor_id" />
                <div class="mb-4 flex space-x-4">
                    <!-- Subject Input -->
                    <!-- <div class="flex flex-col flex-1">
                        <label for="subject" class="block font-bold text-sm mb-2">Subject*</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded" id="subject_autocomp"
                            placeholder="Subject Code or Subject Name" />
                    </div> -->
                    <div class="flex flex-col flex-1">
                        <label for="subject" class="block font-bold text-sm mb-2">Subject*</label>
                        <select class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500" id="subject_autocomp">
                            <option value="" selected disabled>Select Subject</option>
                        </select>
                    </div>
                    <input type="hidden" id="subject_id" />
                    <!-- Units Input -->
                    <div class="flex flex-col flex-1">
                        <label for="units" class="block font-bold text-sm mb-2">Units*</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded" id="units_autocomp"
                            placeholder="Units" readonly />
                    </div>
                </div>
                <div class="mb-4 flex space-x-4">
                    <!-- Subject Input -->
                    <div class="flex flex-col flex-1">
                        <label for="section" class="block font-bold text-sm mb-2">Section*</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded" id="section"
                            placeholder="Section" readonly />
                    </div>
                </div>
                <div class="mb-4 flex space-x-4">
                    <!-- Subject Input -->
                    <div class="flex flex-col flex-1">
                        <label for="room" class="block font-bold text-sm mb-2">Room Number*</label>
                        <input type="number" class="w-full p-2 border border-gray-300 rounded" id="room"
                            placeholder="Room" readonly />
                    </div>
                </div>
                 <div class="mb-4">
                    <label for="day" class="block font-bold text-sm mb-2">Day*</label>
                    
                    <input type="text" class="w-full p-2 border border-gray-300 rounded" id="day"
                            placeholder="Day" readonly />
                </div>
                <div class="mb-4 flex items-center space-x-4">
                    <div class="flex-1">
                        <label for="timeclass" class="block font-bold text-sm mb-2">Class Time*</label>
                        <div class="flex items-center">
                            <input type="text" id="class-time-from" placeholder="hh:mm AM/PM" class="w-full p-2 border border-gray-300 rounded" readonly>
                            <span class="mx-2 font-bold">TO</span>
                            <input type="text" id="class-time-to" placeholder="hh:mm AM/PM" class="w-full p-2 border border-gray-300 rounded" readonly>
                        </div>
                    </div>
                </div>

                <!-- Year and Term (Range) -->
                <div class="mb-4 flex items-center space-x-4">
                    <div class="flex-1">
                        <label for="year-term-from" class="block font-bold text-sm mb-2">SCHOOL YEAR*</label>
                        <div class="flex items-center">
                            <input type="number" id="year-term-from" placeholder="From" class="w-full p-2 border border-gray-300 rounded" readonly>
                            <span class="mx-2 font-bold">TO</span>
                            <input type="number" id="year-term-to" placeholder="To" class="w-full p-2 border border-gray-300 rounded" readonly>
                        </div>
                    </div>
                </div>

                <!-- Year Level and Term -->
                <div class="mb-4 flex items-center space-x-4">
                    <div class="flex-1">
                        <label for="year-level" class="block font-bold text-sm mb-2">YEAR LEVEL*</label>
                    <input type="text" class="w-full p-2 border border-gray-300 rounded" id="year-level"
                            placeholder="Year Level" readonly />
                    </div>
                    <div class="flex-1">
                        <label for="term" class="block font-bold text-sm mb-2">TERM*</label>
                    <input type="text" class="w-full p-2 border border-gray-300 rounded" id="term"
                            placeholder="Term" readonly />
                    </div>
                </div>

                <!-- Proceed Button -->
                <div class="flex justify-end mt-4">
                    <button type="submit" id="btnProceed" class="bg-blue-700 text-white font-bold py-2 px-6 rounded shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Add Faculty Load
                    </button>
                </div>
            </form>
        </div>

        <div class="border-b-4 border-black my-4"></div>

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

            $(document).ready(function () {
                fetchDepartments();

                function fetchDepartments() {
                    $.ajax({
                        url: '../FacultyCanTeach1/api/facultyAPI.php',
                        type: 'GET',
                        data: {
                            action: 'getDepartments'
                        },
                        success: function (response) {

                            console.log('Response:', response);
                            console.log('Type:', typeof response);

                            var data = typeof response === 'string' ? JSON.parse(response) : response;

                            var collegeSelect = $('#college');
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
                    $('#college').on('change', function () {

                        var selectedValue = $(this).val();
                        var selectedText = $('#college option:selected').text();
                        console.log('Selected College ID:', selectedValue);
                        console.log('Selected College Name:', selectedText);
                        $('#subject_autocomp').val('');
                        $('#professor_autocomp').val('');

                            if (selectedValue) {
                                loadSubjects(selectedValue);
                            }

                        $.ajax({ 
                            url: '../FacultyCanTeach1/api/facultyAPI.php',
                            type: 'GET',
                            data: {
                                action: 'getCourses',
                                id: selectedValue
                            },
                            success: function (response) {

                                console.log('Response:', response);
                                console.log('Type:', typeof response);

                                var data = typeof response === 'string' ? JSON.parse(response) : response;

                                var collegeSelect = $('#courses');
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

                    });

                    $("#professor_autocomp").autocomplete({
                        source: function (request, response) {
                            $.ajax({
                                url: "../FacultyCanTeach1/api/facultyAPI.php",
                                type: "GET",
                                data: {
                                    action: "getProfessorSuggestions",
                                    term: request.term
                                },
                                success: function (data) {
                                    let suggestions = typeof data === "string" ? JSON.parse(data) : data;
                                    response(
                                        suggestions.map(function (item) {
                                            return {
                                                label: item.first_name + " " + item.last_name + " (" + item.employee_id + ")",
                                                value: item.first_name + " " + item.last_name,
                                                id: item.employee_id
                                            };
                                        })
                                    );
                                },
                                error: function (xhr, status, error) {
                                    console.error("Error:", error);
                                }
                            });
                        },
                        minLength: 1,
                        select: function (event, ui) {
                            console.log("Selected:", ui.item.label);
                            
                            $("#professor_id").val(ui.item.id);
                            
                            $("#professor_autocomp").data("professor_id", ui.item.id);

                            let professorID = $("#professor_autocomp").data("professor_id");
                            console.log("Professor ID:", professorID);
                        }
                    });

                    function loadSubjects(departmentId) {
                        $.ajax({
                            url: '../FacultyCanTeach1/api/facultyAPI.php',
                            type: 'GET',
                            data: {
                                action: 'getSubjects2',
                                department_id: departmentId
                            },
                            success: function (data) {
                                const $select = $('#subject_autocomp');
                                $select.empty().append('<option value="" disabled selected>Select Subject</option>');

                                $.each(data, function (index, item) {
                                    $select.append(
                                        $('<option>', {
                                            value: item.subject_id,
                                            text: item.subject_code + ' - ' + item.subject_name,
                                            'data-units': item.units,
                                            'data-code': item.subject_code,
                                            'data-term': item.term,
                                            'data-year': item.year_level,
                                            'data-section': item.section,
                                            'data-room': item.room_number,
                                            'data-day': item.schedule_day,
                                            'data-time': item.schedule_time,
                                            'data-program_id': item.program_id,
                                            'data-sy': item.schoolzear
                                        })
                                    );
                                });
                            },
                            error: function () {
                                console.error('Error loading subjects');
                            }
                        });
                    }
                    $('#subject_autocomp').on('change', function () {
                        const selected = $(this).find(':selected');

                        const subjectId = $(this).val();
                        const subjectCode = selected.data('code');
                        const units = selected.data('units');

                        const term = selected.data('term');
                        const year = selected.data('year');
                        const section = selected.data('section');
                        const room = selected.data('room');
                        const day = selected.data('day');
                        const sy = selected.data('sy');
                        const timeRange = selected.data('time');
                        alert('Selected Program: ' + selected.data('program_id'));
                        console.log(timeRange);
                        console.log(sy);
                        

                        if (timeRange && timeRange.includes(' - ')) {
                            const [startTime, endTime] = timeRange.split(' - ');
                            $('#class-time-from').val(startTime.trim());
                            $('#class-time-to').val(endTime.trim());
                        }
                        
                        if (sy && sy.includes('-')) {
                            const [startY, endY] = sy.split('-');
                            $('#year-term-from').val(startY.trim());
                            $('#year-term-to').val(endY.trim());
                        }

                        $('#subject_id').val(subjectId);
                        getSubjectUnits(subjectCode); 

                        $('#term').val(term);
                        $('#year-level').val(year);
                        $('#section').val(section);
                        $('#room').val(room);
                        $('#day').val(day);

                        console.log('Subject ID:', subjectId);
                        console.log('Units:', units);
                        console.log(room);
                        
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
                $('#courses').on('change', function () {
                    var selectedValue = $(this).val();
                    var selectedText = $(this).find('option:selected').text();
                    console.log('Selected Course ID:', selectedValue);
                    console.log('Selected Course Name:', selectedText);
                });

                $('#btnProceed').click(function (e) {
                    e.preventDefault();

                    var formData = {
                        action: 'addFacultyLoad',
                        college: $('#college').val(),
                        courses: $('#courses').val(),
                        professor_autocomp: $('#professor_id').val(),
                        subject_autocomp: $('#subject_id').val(),
                        units_autocomp: $('#units_autocomp').val(),
                        day: $('#day').val(),
                        section: $('#section').val(),
                        room: $('#room').val(),
                        class_time_from: $('#class-time-from').val(),
                        class_time_to: $('#class-time-to').val(),
                        year_term_from: $('#year-term-from').val(),
                        year_term_to: $('#year-term-to').val(),
                        year_level: $('#year-level').val(),
                        term: $('#term').val(),
                    };

                    if (
                        !formData.college || !formData.courses || !formData.professor_autocomp ||
                        !formData.subject_autocomp || !formData.units_autocomp || !formData.day ||
                        !formData.class_time_from || !formData.class_time_to || !formData.year_term_from ||
                        !formData.year_term_to || !formData.year_level || !formData.term
                    ) {
                        alert('Please fill in all required fields.');
                        return;
                    }

                    console.log(formData);

                    $.ajax({
                        url: '../FacultyCanTeach1/api/facultyAPI.php',
                        type: 'POST',
                        data: formData,
                        success: function (response) {
                            try {
                                let res = typeof response === "string" ? JSON.parse(response) : response;

                                if (res.status === 'success') {
                                    alert(res.message);
                                    $('form')[0].reset();
                                } else {
                                    alert(res.message);
                                }
                            } catch (e) {
                                console.error('Error parsing JSON:', e);
                                console.log('Raw Response:', response);
                                alert('Error processing response. Check console for details.');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('XHR Response:', xhr.responseText);
                            alert('An error occurred while adding the faculty load. Check the console for details.');
                        }
                    });
                });

            });

        </script>
    </div>
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

    /* Button styles */
    .form-actions button {
        padding: 12px 20px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
</style>
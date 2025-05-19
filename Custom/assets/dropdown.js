$(document).ready(function() {
    // Load departments on page load
    loadDepartments();
    loadEducationLevels(); // New line to load education levels
    loadSemesters(); // ✅ Add this line
    currentSemester();
    previousSemester();


    // When department changes, load courses
    $('#department-select').on('change', function() {
        const deptId = $(this).val();
        if (deptId) {
            loadCourses(deptId); // Load courses based on selected department
        } else {
            // If no department is selected, clear the course and major dropdowns
            $('#course-select').empty().append('<option value="" disabled>Select Program</option>').prop('disabled', true);
            $('#major-select').empty().append('<option value="" disabled>Select Major</option>').prop('disabled', true); // Reset majors
        }
    });

    // When course changes, load majors
    $('#course-select').on('change', function() {
        const courseId = $(this).val();
        const deptId = $('#department-select').val(); // Get the selected department ID
        if (courseId && deptId) {
            loadMajors(courseId, deptId); // Load majors based on selected course and department
        } else {
            // If no course is selected, clear the major dropdown
            $('#major-select').empty().append('<option value="" disabled>Select Major</option>').prop('disabled', true);
        }
    });
});

// Load Departments (unchanged)
function loadDepartments() {
    $.ajax({
        url: '/capst/custom/api/department.php', // URL to fetch departments
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            const $select = $('#department-select').empty();
            $select.append('<option value="" disabled selected>Select Department</option>');
            $.each(data, function(index, dept) {
                $select.append(`<option value="${dept.id}">${dept.department_name}</option>`);
            });
        },
        error: function(xhr, status, error) {
            console.error("Department load error:", error);
            $('#department-select').html('<option value="" disabled>Error loading departments</option>');
        }
    });
}

// Load Courses Based on Selected Department
function loadCourses(departmentId) {
    $.ajax({
        url: `/capst/custom/api/course.php`, // URL to fetch courses
        type: 'GET',
        dataType: 'json',
        data: { department_id: departmentId }, // Send department_id as query parameter
        success: function(courses) {
            const $courseSelect = $('#course-select').empty().prop('disabled', false);
            $courseSelect.append('<option value="" selected>Select Program</option>');
            $courseSelect.append('<option value="0">All Program</option>'); // "All Program" option

            if (courses.length > 0) {
                $.each(courses, function(index, course) {
                    $courseSelect.append(`<option value="${course.id}">${course.course_name}</option>`);
                });
            } else {
                $courseSelect.append('<option value="" disabled>No courses found</option>');
            }

            // Reset Major dropdown when courses change
            $('#major-select').empty().append('<option value="" disabled>Select Major</option>').prop('disabled', true);
        },
        error: function(xhr, status, error) {
            console.error("Course load error:", error);
            $('#course-select').empty().append('<option value="" disabled>Error loading courses</option>');
        }
    });
}

// Load Majors Based on Selected Course and Department
function loadMajors(courseId, departmentId) {
    $.ajax({
        url: `/capst/custom/api/majors.php`, // URL to fetch majors
        type: 'GET',
        dataType: 'json',
        data: { course_id: courseId, department_id: departmentId }, // Send both course_id and department_id as query parameters
        success: function(majors) {
            console.log(majors);
            const $majorSelect = $('#major-select').empty().prop('disabled', false);
            $majorSelect.append('<option value="" selected>Select Major</option>');

            if (majors.length > 0) {
                $.each(majors, function(index, major) {
                    $majorSelect.append(`<option value="${major.id}">${major.major_name}</option>`);
                });
            } else {
                $majorSelect.append('<option value="" disabled>No majors found</option>');
            }
        },
        error: function(xhr, status, error) {
            console.error("Major load error:", error);
            $('#major-select').empty().append('<option value="" disabled>Error loading majors</option>');
        }
    });
}

// Load Education Levels (Year Levels) on page load
function loadEducationLevels() {
    $.ajax({
        url: '/capst/custom/api/education_Level.php', // API URL for education levels
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            const $select = $('#education-level-select').empty().prop('disabled', false);
            $select.append('<option value="" disabled selected>Select Year Level</option>');
            $select.append('<option value="all">All Year Level</option>');


            if (data.length > 0) {
                // Loop through the data and add each education level to the dropdown
                $.each(data, function(index, level) {
                    $select.append(`<option value="${level.id}">${level.level_name}</option>`);
                });
            } else {
                $select.append('<option value="" disabled>No education levels found</option>');
            }
        },
        error: function(xhr, status, error) {
            console.error("Education Level load error:", error);
            $('#education-level-select').empty().append('<option value="" disabled>Error loading year levels</option>');
        }
    });
}

function loadSemesters() {
    $.ajax({
        url: '/capst/custom/api/semester.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            const $select = $('#semester-select').empty().prop('disabled', false);
            $select.append('<option value="" disabled selected>Select Semester</option>');
            $select.append('<option value="0">All Semester</option>');
            data.forEach(function(semester) {
                $select.append(`<option value="${semester.id}">${semester.name}</option>`);
            });
        },
        error: function(xhr, status, error) {
            console.error("Failed to load semesters:", error);
        }
    });
}

function previousSemester(){
    $.ajax({
        url: '/capst/custom/api/semester.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            const $select = $('#previous-semester-select').empty().prop('disabled', false);
            $select.append('<option value="" disabled selected>Select Semester</option>');
            data.forEach(function(semester) {
                $select.append(`<option value="${semester.id}">${semester.name}</option>`);
            });
        },
        error: function(xhr, status, error) {
            console.error("Failed to load semesters:", error);
        }
    });
}

function currentSemester(){
    $.ajax({
        url: '/capst/custom/api/semester.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            const $select = $('#current-semester-select').empty().prop('disabled', false);
            $select.append('<option value="" disabled selected>Select Semester</option>');
            data.forEach(function(semester) {
                $select.append(`<option value="${semester.id}">${semester.name}</option>`);
            });
        },
        error: function(xhr, status, error) {
            console.error("Failed to load semesters:", error);
        }
    });
}

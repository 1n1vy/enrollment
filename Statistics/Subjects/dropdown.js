function loadDepartments() {
    $.ajax({
        url: '/capst/custom/api/department.php', // URL to fetch departments
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            const $select = $('#department').empty();
            $select.append('<option value="" disabled selected>Select Department</option>');
            $.each(data, function(index, dept) {
                $select.append(`<option value="${dept.department_name}" data-id="${dept.id}">${dept.department_name}</option>`);
            });
        },
        error: function(xhr, status, error) {
            console.error("Department load error:", error);
            $('#department').html('<option value="" disabled>Error loading departments</option>');
        }
    });
}
function getSelectedOptionInfo(selector) {
    const $select = $(selector);
    const selectedOption = $select.find('option:selected');
    return {
        value: selectedOption.val(),                // option value (e.g., department_name)
        id: selectedOption.data('id') || null       // option data-id (e.g., department_id)
    };
}
function loadCourses(departmentId) {
    $.ajax({
        url: `/capst/custom/api/course.php`, // URL to fetch courses
        type: 'GET',
        dataType: 'json',
        data: { department_id: departmentId }, // Send department_id as query parameter
        success: function(courses) {
            const $courseSelect = $('#course').empty().prop('disabled', false);
            $courseSelect.append('<option value="" selected>Select Program</option>');

            if (courses.length > 0) {
                $.each(courses, function(index, course) {
                    $courseSelect.append(`<option value="${course.course_name}">${course.course_name}</option>`);
                });
            } else {
                $courseSelect.append('<option value="" disabled>No courses found</option>');
            }

            // Reset Major dropdown when courses change
            $('#major-select').empty().append('<option value="" disabled>Select Major</option>').prop('disabled', true);
        },
        error: function(xhr, status, error) {
            console.error("Course load error:", error);
            $('#course').empty().append('<option value="" disabled>Error loading courses</option>');
        }
    });
}
$(document).ready(function() {
    loadDepartments();
    loadCourses();
    $(document).on('change', '#department', function () {
        const selected = getSelectedOptionInfo('#department');
        console.log("Selected Department Name:", selected.value);
        loadCourses(selected.id)
    });
});
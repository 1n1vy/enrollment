function fetchDepartments(id) {


    $.ajax({
        url: '../programAPI.php',
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
            
                preFill(id);
            
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
            url: '../programAPI.php',
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
            url: '../programAPI.php',
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
                url: '../programAPI.php',
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
                url: '../programAPI.php',
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
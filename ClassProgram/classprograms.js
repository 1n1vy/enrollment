function fetchDepartments(id) {

    $.ajax({
        url: '../programAPI.php',
        type: 'GET',
        data: {
            action: 'getCurriculum'
        },
        success: function (response) {

            var data = typeof response === 'string' ? JSON.parse(response) : response;
    
            var currSelect = $('#progCurr');
            currSelect.empty();
            currSelect.append('<option value="" selected disabled>Select Curriculum</option>');
    
            if (data.length > 0) {
                data.forEach(function (curr) {
                    currSelect.append(
                        `<option value="${curr.id}">${curr.curriculum_year_start} - ${curr.curriculum_year_end}</option>`
                    );
                });
            } else {
                currSelect.append('<option value="">No Curriculum Available</option>');
            }

            currSelect.on('change', function () {
                var curriculumId = $(this).val();
                if (curriculumId) {
                    $.ajax({
                        url: '../programAPI.php',
                        type: 'GET',
                        data: {
                            action: 'getCourses',
                            curriculum_id: curriculumId
                        },
                        success: function (res) {
                            var courseData = typeof res === 'string' ? JSON.parse(res) : res;
    
                            var courseSelect = $('#progCourses');
                            courseSelect.empty();
                            courseSelect.append('<option value="" selected disabled>Select Course</option>');
    
                            if (courseData.length > 0) {
                                courseData.forEach(function (course) {
                                    courseSelect.append(
                                       `<option value="${course.id}" data-dept-id="${course.department_id}">
                                            ${course.course_code} - ${course.course_name}
                                        </option>`
                                    );
                                });
                            } else {
                                courseSelect.append('<option value="">No Courses Available</option>');
                            }
                        },
                        error: function (xhr) {
                            console.log('Error fetching courses:', xhr.responseText);
                        }
                    });
                } else {
                    $('#progCourses').empty().append('<option value="">Select Course</option>');
                }
            });
            $('#progCourses').on('change', function () {
                var selectedOption = $(this).find('option:selected');
                var departmentId = selectedOption.data('dept-id');
                $('#progDept').val(departmentId);
            });
            

            currSelect.on('change', function () {
                var curriculumId = $(this).val();
                if (curriculumId) {
                    $.ajax({
                        url: '../programAPI.php',
                        type: 'GET',
                        data: {
                            action: 'getProgram',
                            curriculum_id: curriculumId
                        },
                        success: function (res) {
                            var courseData = typeof res === 'string' ? JSON.parse(res) : res;
    
                            var courseSelect = $('#progProgram');
                            courseSelect.empty();
                            courseSelect.append('<option value="" selected disabled>Select a Program</option>');
    
                            if (courseData.length > 0) {
                                courseData.forEach(function (prog) {
                                    courseSelect.append(
                                        `<option value="${prog.id}">${prog.level_name}</option>`
                                    );
                                });
                            } else {
                                courseSelect.append('<option value="">No Courses Available</option>');
                            }
                        },
                        error: function (xhr) {
                            console.log('Error fetching courses:', xhr.responseText);
                        }
                    });
                } else {
                    $('#progCourses').empty().append('<option value="">Select Course</option>');
                }
            });
            $('#progCourses').on('change', function () {
                var courseId = $(this).val();
            
                if (courseId) {
                    $.ajax({
                        url: '../programAPI.php',
                        type: 'GET',
                        data: {
                            action: 'getMajor',
                            course_id: courseId
                        },
                        success: function (res) {
                            var majorData = typeof res === 'string' ? JSON.parse(res) : res;
            
                            var majorSelect = $('#progMajor');
                            majorSelect.empty();
                            majorSelect.append('<option value="" disabled selected>Select a Major</option>');
            
                            if (majorData.length > 0) {
                                majorData.forEach(function (major) {
                                    majorSelect.append(
                                        `<option value="${major.id}">${major.major_name}</option>`
                                    );
                                });
                            } else {
                                majorSelect.append('<option value="">No Majors Available</option>');
                            }
                        },
                        error: function (xhr) {
                            console.error('Error fetching majors:', xhr.responseText);
                        }
                    });
                } else {
                    $('#progMajor').empty().append('<option value="">Select Major</option>');
                }
            });
    
        },
        error: function (xhr) {
            console.log('Error:', xhr.responseText);
        }
    });

    

    let subjectMap = {};

    $('#progTerm').on('change', function () {
        $('#subject_autocomp').val("").trigger('change');
    });

    $('#subject_autocomp').autocomplete({
        source: function(request, response) {
            var searchText = request.term;
            var term = $('#progTerm').val();
            var course = $('#progCourses').val();
        
            $.ajax({
                url: '../programAPI.php',
                type: 'GET',
                data: {
                    action: 'getSubjects',
                    search: searchText,
                    term: term,
                    course: course
                },
                success: function(data) {
                    console.log('API Response:', data);
                    var subjects = typeof data === 'string' ? JSON.parse(data) : data;
                    subjectMap = {};
                    var options = [];
        
                    if (subjects.length > 0) {
                        subjects.forEach(function(subj) {
                            console.log('Subject:', subj); 
                            var label = `${subj.subject_code} - ${subj.subject_name}`;
                            subjectMap[label] = subj;
                            options.push(label);
                        });
                    }
        
                    console.log('Autocomplete Options:', options);
                    response(options);
                },
                error: function(xhr) {
                    console.error('Error fetching subjects:', xhr.responseText);
                }
            });
        },
        select: function(event, ui) {
            var selected = ui.item.value;
            console.log('Selected Item:', selected);
        
            if (subjectMap[selected]) {
                var year = subjectMap[selected].year_level || '';
                var subjectId = subjectMap[selected].id || '';
                var lecUnit = subjectMap[selected].lec_units; 
                console.log('lec_unit:', lecUnit);
        
                var component = lecUnit && !isNaN(Number(lecUnit)) && Number(lecUnit) > 0 ? 'LEC' : 'LAB';
                console.log('Component:', component);
        
                $('#progYear').val(year);
                $('#progSubjectid').val(subjectId);
                $('#subjectCompo').val(component);
                $.ajax({
                    url: '../programAPI.php',
                    type: 'GET',
                    data: {
                        action: 'getRooms',
                        subjectCompo: $('#subjectCompo').val(),
                        dept: $('#progDept').val(),
                    },
                    success: function(res) {
                        var rooms = typeof res === 'string' ? JSON.parse(res) : res;
                        var roomSelect = $('#room');
                
                        roomSelect.empty();
                        roomSelect.append('<option value="" disabled selected>Select Room</option>');
                
                        if (rooms.length > 0) {
                            rooms.forEach(function(room) {
                                roomSelect.append(`<option value="${room.id}">${room.building_code} - [${room.room_number}]</option>`);
                            });
                        } else {
                            roomSelect.append('<option value="">No Rooms Available</option>');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error fetching rooms:', xhr.responseText);
                    }
                });
            } else {
                $('#progYear').val('');
                $('#progSubjectid').val('');
                $('#subjectCompo').val('');
            }
        }
    });
    
}

// function preFill(id) {
//     $.ajax({
//         url: '../programAPI.php',
//         type: 'GET',
//         data: {
//             action: 'getProgrambyID',
//             program_id: id
//         },
//         success: function (response) {
//             var data = typeof response === 'string' ? JSON.parse(response) : response;
//             console.log('Prefill Data:', data);

//             if (data[0]) {
//                 var departmentId = data[0].department_id;
//                 var programId = data[0].program_id;
//                 var courseId = data[0].course_id;
//                 var curriculumId = data[0].curriculum_id;
//                 var majorId = data[0].major_id;
//                 var sectionId = data[0].section_id;

//                 if (departmentId) {
//                     $('#progDept').val(departmentId).trigger('change');
//                     console.log('Department set to:', departmentId);
//                 }

//                 if (programId) {
//                     var programOptions = $('#progProgram option');
//                     var isProgramIdFound = false;
//                     programOptions.each(function () {
//                         if ($(this).val() == programId) {
//                             isProgramIdFound = true;
//                         }
//                     });

//                     if (isProgramIdFound) {
//                         $('#progProgram').val(programId).trigger('change');
//                         console.log('Program set to:', programId);
//                     } else {
//                         console.error('No matching program_id found in #progProgram options');
//                     }
//                 }

//                 if (courseId) {
//                     $('#progCourses').val(courseId).trigger('change');
//                     console.log('Course set to:', courseId);
//                 }

//                 if (curriculumId) {
//                     $('#progCurr').val(curriculumId).trigger('change');
//                     console.log('Curriculum set to:', curriculumId);
//                 }

//                 if (sectionId) {
//                     $('#progSection').val(sectionId).trigger('change');
//                     console.log('Section set to:', sectionId);
//                 }

//                 if (majorId) {
//                     $('#progMajor').val(majorId).trigger('change');
//                     console.log('Major set to:', majorId);
//                 }
                
//                 var schoolYear = String(data[0].school_year).trim();
//                 if (schoolYear && schoolYear.includes('-')) {
//                     var schoolYearParts = schoolYear.split('-');
//                     console.log('Start Year:', schoolYearParts[0]);
//                     console.log('End Year:', schoolYearParts[1]);

//                     $('#progAcadstart').val(schoolYearParts[0]);
//                     $('#progAcadend').val(schoolYearParts[1]);
//                 } else {
//                     console.error('Invalid school year format:', schoolYear);
//                 }

//                 $('#subcomponent').val(data[0].subject_component).trigger('change');
//                 $('#subjectofferingtype').val(data[0].subject_type).trigger('change');
//                 $('#progYear').val(data[0].year_level);
//                 $('#progTerm').val(data[0].term);
//                 $('#schedulewk').val(data[0].schedule_day);
//                 var scheduleTime = data[0].schedule_time;
//                 var timeParts = scheduleTime.split(' - ');
                
//                 $('#time_input1').val(timeParts[0]); 
                
//                 $('#time_input2').val(timeParts[1]); 

//                 if (data[0].academic_year && data[0].academic_year.includes('-')) {
//                     var parts = data[0].academic_year.split('-');
//                     $('#progAcadstart').val(parts[0]);
//                     $('#progAcadend').val(parts[1]);
//                 } else {
//                     $('#progAcadstart').val('');
//                     $('#progAcadend').val('');
//                 }
//             } else {
//                 console.error('No data returned from API');
//             }
//         },
//         error: function () {
//             console.error('Error fetching program details');
//         }
//     });
// }
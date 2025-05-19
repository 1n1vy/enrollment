$('#check-student').click(function () {
    const studentId = $('#student_id').val().trim();

    if (studentId === "") {
        $('#student-warning')
            .text('Please enter a Student ID.')
            .removeClass('d-none');
        return;
    }

    // console.log('Checking student ID:', studentId);
    // console.log('Year Start:', $('#yearstart').val());
    // console.log('Year End:', $('#yearend').val());
    // console.log('Term:', $('#term').val());
    
    $.ajax({
        url: 'advisingAPI.php',
        method: 'POST',
        data: { action: 'checkStudent', student_id: studentId, yearstart: $('#yearstart').val(), yearend: $('#yearend').val(), term: $('#term').val() },
        success: function (response) {

            var data = typeof response === 'string' ? JSON.parse(response) : response;

            if (data.exists) {

                $('#student-warning').addClass('d-none');
                window.location.href = `proceed.php?student=${studentId}&yearstart=${$('#yearstart').val()}&yearend=${$('#yearend').val()}&term=${$('#term').val()}`;

            } else {
                $('#student-warning')
                    .text('Student not found.')
                    .removeClass('d-none');
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


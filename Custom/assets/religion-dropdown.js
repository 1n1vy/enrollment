function loadEducationLevels() {
    $.ajax({
        url: '/capst/custom/api/education_Level.php', // API URL for education levels
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            const $select = $('#education-level-select').empty().prop('disabled', false);
            $select.append('<option value="" disabled selected>Select Year Level</option>');
            $select.append('<option value="0">All Year Level</option>');


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

$(document).ready(function() {
    loadEducationLevels()
})
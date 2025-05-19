<?php
session_start();
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
    <title>Subject Filtered</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <section class="section-header text-sm md:text-xl">
        <h1>SUBJECTS FILTERED</h1>
    </section>

    <div class="form-container">
        <form action="#">
            <section class="section-header text-sm mt-6">
                <h1>LIST OF SUBJECTS</h1>
            </section>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300">
                    <thead style="background-color: #174069;" class="text-white">
                        <tr>
                            <th class="py-2 px-4 border">SUBJECT CODE</th>
                            <th class="py-2 px-4 border">SUBJECT DESCRIPTION</th>
                            <th class="py-2 px-4 border" style="width: 200px;">CHOOSE</th>
                        </tr>
                    </thead>
                    <tbody id="subject">

                    </tbody>
                </table>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            //SUBJECT LIST
            $.ajax({
                url: `http://localhost:3000/subject/list`,
                method: 'GET',
                success: function(data) {
                    var selectorElement = $('#subject')

                    const options = data.map((item) =>
                        `<tr class="text-gray-700 bg-white" id="subject-${item.id}">
                            <td class="py-2 px-4 border text-center">${item.subject_code}</td>
                            <td class="py-2 px-4 border text-center">${item.subject_desc}</td>
                            <td class="py-2 px-4 border text-center">
                                <button class="btn-confirm" data-id="${item.id}">Confirm</button>
                            </td>
                        </tr>`
                    ).join('');

                    selectorElement.html(options)
                },
                error: function(xhr, status, error) {
                    console.error('Request failed:', error);
                }
            });
        })

        $(document).on('click', '.btn-confirm', function() {
            const dataId = $(this).data('id');

            // If this is a popup window
            if (window.opener) {
                // Communicate with parent window
                window.opener.location.href = `NewClassPrograms.html?selected_subjcode=${dataId}`;
                // Close the popup
                window.close();
            } else {
                // If not a popup, redirect in the current window
                window.location.href = `NewClassPrograms.html?selected_subjcode=${dataId}`;
            }
        });
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
        width: 80%;
        margin: 40px auto;
        background-color: #f4f8fc;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .form-group {
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .form-group label {
        font-size: 14px;
        font-weight: bold;
        margin-right: 10px;
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
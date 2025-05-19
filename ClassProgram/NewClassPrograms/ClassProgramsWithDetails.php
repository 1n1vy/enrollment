<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Programs</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <section class="section-header text-sm md:text-xl">
        <h1>CLASS PROGRAMS PAGE</h1>
    </section>

    <div class="form-container">
        <form action="#">
            <div class="form-group">
                <label for="offeringcourse">Offerings for Course:</label>
                <span>Bachelor of Science in Information Technology</span>
            </div>
            <div class="form-group">
                <label for="yearterm">Year/Term:</label>
                <span>1st Year/1st Semester</span>
            </div>
            <div class="form-group">
                <label for="schoolyear">School Year:</label>
                <span>2023-2024</span>
            </div>
            <div class="form-group">
                <label for="offeringterm">Offering Term:</label>
                <span>2nd Semester</span>
            </div>
            <section class="section-header text-sm mt-6">
                <h1>LIST OF SUBJECT SCHEDULE</h1>
            </section>
            <div class="form-row">
                <button class="bi bi-printer"> Click to Print Class Program</button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300">
                    <thead style="background-color: #174069;" class="text-white">
                        <tr>
                            <th class="py-2 px-4 border">SUBJECT CODE</th>
                            <th class="py-2 px-4 border">SECTION</th>
                            <th class="py-2 px-4 border" style="width: 350px;">ROOM #</th>
                            <th class="py-2 px-4 border" style="width: 800px;">SCHEDULE(DAYS/TIME)</th>
                            <th class="py-2 px-4 border">DO NOT CHECK CONFLICT</th>
                            <th class="py-2 px-4 border" style="width: 200px;">DELETE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-gray-700 bg-white">
                            <td class="py-2 px-4 border text-center">CCC112-18 (Fundamentals of Programming Lecture)</td>
                            <td class="py-2 px-4 border text-center">1CICS_Pet</td>
                            <td class="py-2 px-4 border text-center">B231</td>
                            <td class="py-2 px-4 border text-center">Fri - 7:00 AM to 8:30 AM</td>
                            <td class="py-2 px-4 border text-center">-----</td>
                            <td class="py-2 px-4 border text-center bi bi-trash"></td>
                        </tr>
                        <tr class="text-gray-700 bg-white">
                            <td class="py-2 px-4 border text-center">-----</td>
                            <td class="py-2 px-4 border text-center">-----</td>
                            <td class="py-2 px-4 border text-center">-----</td>
                            <td class="py-2 px-4 border text-center">-----</td>
                            <td class="py-2 px-4 border text-center">-----</td>
                            <td class="py-2 px-4 border text-center">-----</td>
                        </tr>
                        <tr class="text-gray-700 bg-white">
                            <td class="py-2 px-4 border text-center">-----</td>
                            <td class="py-2 px-4 border text-center">-----</td>
                            <td class="py-2 px-4 border text-center">-----</td>
                            <td class="py-2 px-4 border text-center">-----</td>
                            <td class="py-2 px-4 border text-center">-----</td>
                            <td class="py-2 px-4 border text-center">-----</td>
                        </tr>
                    </tbody>
                </table>
        </form>
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
        display: flex;
        align-items: center;
    }

    .form-group label {
        font-size: 14px;
        font-weight: bold;
        margin-right: 10px;
        display: block;
    }

    .form-group input, .form-group select {
        width: 100%;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .label input, .label select {
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 100%; /* Ensure inputs take up full width inside their containers */
    }

    .flex {
        font-weight: bold;
        display: flex;
        gap: 5px; /* Maintain close proximity between elements */
        flex-wrap: nowrap; /* Prevent wrapping */
        width: 100%; /* Ensure the flex container is responsive */
    }


    .ml-1 {
        margin-left: 0; /* Remove any additional left margin */
        flex-grow: 1; /* Allow input fields to grow as needed */
    }

    .flex-grow {
    flex-grow: 1;
    }

    .empty-row {
    height: 75px; /* Adjust the height as needed */
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
    width: 100%;                     /* Make the table full width */
    border-collapse: collapse;       /* Remove spacing between cells */
    margin: 20px 0;                  /* Space around the table */
    }

    /* Header styling */
    .custom-table th,
    .custom-table td {
    border: 1px solid #ccc;          /* Light border for cells */
    padding: 8px;                    /* Padding for cell content */
    text-align: left;
    }

    /* Hover effect for rows */
    .custom-table tbody tr:hover {
    background-color: #f4f8fc;       /* Light background on hover */
    }

    /* Optional: Responsive design for smaller screens */
    @media (max-width: 600px) {
    .custom-table {
        font-size: 14px;              /* Smaller font size on mobile */
    }
    }

    /* Checkbox alignment */
    .custom-table input[type="checkbox"] {
    transform: scale(1.2);           /* Enlarge checkboxes */
    margin: 0 auto;                  /* Center the checkboxes */
    cursor: pointer;                 /* Change cursor to pointer */
    }

    /* Expandable heading styling - matching the table headers */
    .custom-table .expandable-heading {
    cursor: pointer;
    background-color: #f2f2f2;
    font-weight: bold;
    padding: 8px;
    border: 1px solid #ccc;          /* Same border as other headers */
    text-align: left;
    }

    /* Remove arrows */
    .custom-table .expandable-heading:after {
    content: ''; /* Remove arrow */
    }

    .custom-table.expanded .expandable-heading:after {
    content: ''; /* Remove arrow */
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
        overflow-x: auto; /* Allow horizontal scrolling */
    }

    .custom-table {
        width: 100%; /* Make the table take the full width of the container */
        border-collapse: collapse; /* Collapse borders for a cleaner look */
    }

    .custom-table th, .custom-table td {
        padding: 10px; /* Add padding for better readability */
        border: 1px solid #ddd; /* Add a light border to table cells */
        text-align: left; /* Align text to the left */
    }

    .headerbody {
    text-align: center;        /* Center text horizontally */
    padding: 20px;
    font-size: 24px;
    width: 100%;               /* Make sure it takes full width */
    margin: 0 auto;           /* Center the container if it has a defined width */
    }
</style>

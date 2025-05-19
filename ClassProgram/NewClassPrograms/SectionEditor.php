<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Section Maintenance Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <section class="section-header text-sm md:text-xl">
        <h1>SUBJECT SECTION MAINTENANCE PAGE</h1>
    </section>

    <div class="form-container">
        <form action="#">
            <!-- School Year -->
            <div class="form-group">
                <label for="sy">Offering School Year/Term</label>
                <div class="form-row">
                    <input type="text" id="sy" placeholder="Enter">
                    <span>to</span>
                    <input type="text" id="sy2" placeholder="Enter">
                    <!-- Dropdown for selecting the semester -->
                    <select id="term" class="ml-2 p-1 border rounded w-20">
                        <option value="1st">1st</option>
                        <option value="2nd">2nd</option>
                        <option value="3rd">3rd</option>
                        <option value="Summer">Summer</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="oldsubject">Old Subject Section</label>
            </div>
            <div class="form-group">
                <div class="form-row">
                <label for="newsubject">New Subject Section</label>
                <input type="text" id="entersection">
                </div>
            </div>
            <div class="form-row">
            <button class="bi bi-pencil-square"> Click to edit Changes</button>
            <span></span>
            <button class="bi bi-x-square"> Click to cancel Edit</button>
            </div>
            <div class="overflow-x-auto">
                <section class="section-header text-sm mt-6">
                    <h1>LIST OF EXISTING SUBJECT SECTIONS FOR 2023-2024 2nd TERM</h1>
                </section>
                <table class="min-w-full border border-gray-300">
                    <thead style="background-color: #174069;" class="text-white">
                        <tr>
                            <th class="py-2 px-4 border">SECTIONS</th>
                            <th class="py-2 px-4 border">EDIT</th>
                            <th class="py-2 px-4 border">DELETE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-gray-700 bg-white">
                            <td class="py-2 px-4 border text-center">1BSIT-1</td>
                            <td class="py-2 px-4 border text-center bi bi-pencil-square"> EDIT</td>
                            <td class="py-2 px-4 border text-center bi bi-trash"> DELETE</td>
                        </tr>
                        <tr class="text-gray-700 bg-white">
                            <td class="py-2 px-4 border text-center">1BSIT-2</td>
                            <td class="py-2 px-4 border text-center bi bi-pencil-square"> EDIT</td>
                            <td class="py-2 px-4 border text-center bi bi-trash"> DELETE</td>
                        </tr>
                        <tr class="text-gray-700 bg-white">
                            <td class="py-2 px-4 border text-center">1BSIT-3</td>
                            <td class="py-2 px-4 border text-center bi bi-pencil-square"> EDIT</td>
                            <td class="py-2 px-4 border text-center bi bi-trash"> DELETE</td>
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

    .form-group label {
        font-size: 14px;
        font-weight: bold;
        margin-right: 10px;
    }

    .form-row {
        display: flex;
        align-items: center;
        gap: 10px; /* Spacing between elements */
    }

    .form-row input,
    .form-row select {
        flex-grow: 1; /* Allows inputs to take up available space */
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

    .form-row span {
        padding: 0 5px;
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

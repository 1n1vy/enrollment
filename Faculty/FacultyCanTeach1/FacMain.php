<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../../includes/auth.php';
redirectIfNotLoggedIn();

// Optionally, restrict access by role
checkRole(['Admin', 'Faculty', 'Registrar']);

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
    <title>Faculty</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
                <li class="active">Courses Faculty Can Teach</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>FACULTY PAGE - COURSES FACULTY CAN TEACH</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form id="loadForm">
                <!-- Professor ID (Hidden Input) -->
                <label for="term" class="block font-bold text-sm mb-2">Employee ID*</label>
                <input type="text" class="w-full p-2 border border-gray-300 rounded" id="professor_id" />

                <!-- School Year and Term Selection -->
                <div class="mb-4 flex items-center space-x-2">
                    <div class="flex-1">
                        <label for="school-year-from" class="block font-bold text-sm mb-2">School Year*</label>
                        <div class="flex items-center">
                            <input type="number" id="school-year-from" placeholder="From" class="w-full p-2 border border-gray-300 rounded" required>
                            <span class="mx-2 font-bold">TO</span>
                            <input type="number" id="school-year-to" placeholder="To" class="w-full p-2 border border-gray-300 rounded" required readonly>
                        </div>
                    </div>
                    <div class="flex-1">
                        <label for="term" class="block font-bold text-sm mb-2">Term*</label>
                        <select id="term" class="w-full p-2 border border-gray-300 rounded" required>
                            <option value="" selected disabled>Select Term</option>
                            <option value="1st">1st Semester</option>
                            <option value="2nd">2nd Semester</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-between items-center w-full px-8 mt-6">
                    <button type="button" id="btnFetchInfo" class="bg-blue-600 text-white font-bold py-2 px-6 rounded-md">
                        Can Teach
                    </button>
                    <button type="button" id="btnFetchLoad" class="bg-green-600 text-white font-bold py-2 px-6 rounded-md">
                        View Load
                    </button>
                </div>
            </form>

            <!-- Display Can Teach Results -->
            <div id="canTeachResults" class="mt-6"></div>

            <!-- Display Faculty Load Results -->
            <div id="loadResults" class="mt-6"></div>
        </div>

        <div class="border-b-4 border-black my-4"></div>

    <div class="form-container" id="canteach">
        
        <div class="flex justify-end items-center space-x-4 mt-4">
        <a href="#" id="btnPrint2" title="Print" class="bg-red-600 text-white p-2 text-sm rounded-full flex items-center">
            <i class="bi bi-printer mr-2 text-lg"></i> Print
        </a>
        </div>
        <form>

            <div class="info mt-4">
                <p>Professor Information</p>
            </div>
            <table>
                <tr>
                    <td class="label">Employee Name</td>
                    <td id="profName">-</td>
                    <td class="label">Employment Status</td>
                    <td id="empStatus">-</td>
                </tr>
                <tr>
                    <td class="label">Department</td>
                    <td id="department">-</td>
                    <td class="label">Employment Type</td>
                    <td  id="empType">-</td>
                </tr>
            </table>
        </form>

        <!-- Update and Print Buttons Side by Side -->

        <div class="info mt-4">
            <p>Maximum Load Units/Hours: <span class="not-set" id="allowed2">-</span></p>
        </div>

        <table class="table-section">
            <thead>
                <tr>
                    <th colspan="3"><br>LIST OF SUBJECTS FACULTY CAN TEACH</th>
                </tr>
                <tr>
                    <th><br>SUBJECT GROUP</th>
                    <th><br>SUBJECT CODE</th>
                    <th><br>SUBJECT TITLE</th>
                </tr>
            </thead>
            <tbody id="canTeachSubjects">
                <tr>
                    <td colspan="3" class="no-record">No record of subjects that the faculty can teach.</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="form-container" id="loads" style="display: block;">
    <div class="flex justify-end items-center space-x-4 mt-4">
        <a href="#" id="btnPrint" title="Print" class="bg-red-600 text-white p-2 text-sm rounded-full flex items-center">
            <i class="bi bi-printer mr-2 text-lg"></i> Print
        </a>
    </div>
    <form id="printableArea">
        <div class="info mt-4">
            <p>Professor Information</p>
        </div>
        <table>
            <tr>
                <td class="label">Employee Name</td>
                <td id="profName2">-</td>
                <td class="label">Employment Status</td>
                <td id="empStatus2">-</td>
            </tr>
            <tr>
                <td class="label">Department</td>
                <td id="department2">-</td>
                <td class="label">Employment Type</td>
                <td id="empType2">-</td>
            </tr>
            <tr>
                <td class="label">Max Load</td>
                <td id="allowed">-</td>
            </tr>
        </table>
    </form>

    <div class="">
        <form>
            <div class="section-title">LIST OF SUBJECTS CURRENTLY HANDLED</div>

            <table class="table-section">
                <thead>
                    <tr>
                        <th colspan="7" id="load_info">School Year: 2023 - 2024 | Term: 2nd | Current Load: 0.0</th>
                    </tr>
                    <tr>
                        <th>SUBJECT CODE</th>
                        <th>SUBJECT TITLE</th>
                        <th>LEC/LAB UNITS</th>
                        <th>SECTION</th>
                        <th>SCHEDULE</th>
                        <th>ROOM #</th>
                    </tr>
                </thead>
                <tbody id="profLoads">
                    <tr>
                        <td colspan="7" class="no-record">No subjects currently handled.</td>
                    </tr>
                </tbody>
            </table>
        </form>
        <div class="border-b-4 border-black my-4"></div>
    </div>
</div>
</body>

</html>

<script>
document.getElementById('school-year-from').addEventListener('input', function () {
    const startYear = parseInt(this.value);
    const currentYear = new Date().getFullYear();
    const endYearInput = document.getElementById('school-year-to');

    if (!isNaN(startYear)) {
        if (startYear > currentYear) {
            this.value = currentYear;
            endYearInput.value = currentYear + 1;
        } else {
            endYearInput.value = startYear + 1;
        }
    } else {
        endYearInput.value = '';
    }
});
</script>
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

    .button-container {
        display: flex;
        justify-content: flex-end;
        /* Align button to the right */
    }

    .button {
        background-color: #b6b8e3;
        /* Light purple color */
        color: black;
        border: none;
        border-radius: 18px;
        /* Smaller radius for a more compact look */
        padding: 8px 12px;
        /* Further reduced padding */
        text-align: left;
        width: auto;
        /* Adjust width automatically */
        font-size: 12px;
        /* Smaller font size */
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        gap: 6px;
        /* Reduce space between text and arrow */
    }

    .button:hover {
        background-color: #9b9de0;
        /* Darker purple on hover */
    }

    .arrow {
        font-size: 16px;
        /* Further reduced arrow size */
        font-weight: bold;
    }
    

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px auto;
        background-color: #f4f4f4;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    td {
        padding: 10px;
        border: 1px solid #ccc;
        font-family: Arial, sans-serif;
    }
    @media print {
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .no-record {
            color: red;
            font-style: italic;
        }

        .border-b-4 {
            border-bottom: 4px solid #000;
        }
    }
</style>

<script>

$(document).ready(function () {

    var professor_id = $('#professor_id').val();

    $(document).ready(function () {
        
        $('#loads').hide();
        $('#canteach').hide();

        $('#btnFetchInfo').on('click', function () {

            var professor_id = $('#professor_id').val();
            var term = $('#term').val();

            console.log(professor_id);
            
            if (!professor_id) {
                alert('Please enter a valid Employee ID.');
                return;
            }
            
            if (!term) {
                alert('Select a Term.');
                return;
            }

                $.ajax({
                    url: 'api/facultyAPI.php',
                    type: 'GET',
                    data: {
                        action: 'getFacultyInfo',
                        professor_id: professor_id,
                        term: term,
                    },
                    success: function (response) {
                    console.log('Response:', response);
                    console.log('Type:', typeof response);

                    var data = typeof response === 'string' ? JSON.parse(response) : response;

                    if (data.length > 0) {
                        var faculty = data[0];
                        $('#profName').text(faculty.first_name + ' ' + faculty.last_name);
                        $('#department').text(faculty.department_name || '-');
                        $('#empType').text(faculty.role || 'Casual');
                        $('#empStatus').text(faculty.emp_type || 'Administrative Official');
                        $('#allowed').text(faculty.max_load || '0.0');
                        $('#loads').hide();
                        $('#canteach').show();
                        var subjectHtml = '';
                        var subjectsFound = false;

                        data.forEach(function (subject) {
                            if (subject.subject_code) {
                                subjectsFound = true;
                                subjectHtml += `
                                    <tr>
                                        <td style="text-align: center">${subject.department_code || '-'}</td>
                                        <td style="text-align: center">${subject.subject_code}</td>
                                        <td style="text-align: center">${subject.subject_name}</td>
                                    </tr>`;
                            }
                        });

                        if (!subjectsFound) {
                            subjectHtml = '<tr><td colspan="3" class="no-record">No record of subjects that the faculty can teach.</td></tr>';
                        }

                        $('#canTeachSubjects').html(subjectHtml);

                    } else {
                        alert('No faculty found with the given Employee ID.');
                        resetTable();
                    }
                },
                error: function (xhr, status, error) {
                    console.log('Error Status:', status);
                    console.log('XHR Response:', xhr.responseText);
                    console.log('Error:', error);
                    alert('Error fetching faculty information. Check console for details.');
                    resetTable();
                }
            });
        });

        $('#btnFetchLoad').on('click', function () {

            var professor_id = $('#professor_id').val();
            var year_from = $('#school-year-from').val();
            var year_to = $('#school-year-to').val();
            var term = $('#term').val();

            console.log(professor_id);
            
            if (!professor_id) {
                alert('Please enter a valid Employee ID.');
                return;
            }

                $.ajax({
                    url: 'api/facultyAPI.php',
                    type: 'GET',
                    data: {
                        action: 'getFacultyL',
                        professor_id: professor_id,
                        school_year_from: year_from,
                        school_year_to: year_to,  
                        term: term,
                    },
                    success: function (response) {
                    console.log('Response:', response);
                    console.log('Type:', typeof response);

                    var data = typeof response === 'string' ? JSON.parse(response) : response;

                    if (data.length > 0) {
                        var faculty = data[0];
                        $('#profName2').text(faculty.first_name + ' ' + faculty.last_name);
                        $('#department2').text(faculty.department_name || '-');
                        $('#empType2').text(faculty.role || 'Casual');
                        $('#empStatus2').text(faculty.status || 'Administrative Official');
                        $('#allowed2').text(faculty.max_load+'.0' || '0.0');
                        $('#loads').show();
                        $('#canteach').hide();

                        // $('#load_info').text("School Year: "+faculty.school_year_from + "-" + faculty.school_year_to + " | " + faculty.term + " | Current Load:" + faculty.units || 'Casual');
                        //School Year: 2023 - 2024 | Term: 2nd | Current Load: 0.0
                        $('#empType2').text(faculty.role || 'Casual');
                        var subjectHtml = '';
                        var subjectsFound = false;
                        var totalUnits = 0.0; // Initialize total units

                        data.forEach(function (subject) {
                            if (subject.subject_code) {
                                subjectsFound = true;

                                // Add units to total (ensure it's a number to avoid string concatenation)
                                totalUnits += parseFloat(subject.units) || 0.0;

                                subjectHtml += `
                                    <tr>
                                        <td style="text-align: center">${subject.subject_code}</td>
                                        <td style="text-align: center">${subject.subject_name}</td>
                                        <td style="text-align: center">${subject.units || '-'}</td>
                                        <td style="text-align: center">${subject.section || '-'}</td>
                                        <td style="text-align: center">${subject.day} ${subject.class_time_from} - ${subject.class_time_to}</td>
                                        <td style="text-align: center">${subject.room}</td>
                                    </tr>`;
                            }
                        });

                        // Update the current load with the correct total units
                        $('#load_info').text(
                            "School Year: " +
                            faculty.school_year_from +
                            "-" +
                            faculty.school_year_to +
                            " | " +
                            faculty.term +
                            " | Current Load: " +
                            totalUnits.toFixed(1) // Show 1 decimal place for consistency
                        );

                        if (!subjectsFound) {
                            subjectHtml = '<tr><td colspan="6" class="no-record">No recordasd of subjects that the faculty can teach.</td></tr>';
                        }

                        $('#profLoads').html(subjectHtml);

                    } else {
                        alert('No Result Found.');
                        resetTable();
                    }
                },
                error: function (xhr, status, error) {
                    console.log('Error Status:', status);
                    console.log('XHR Response:', xhr.responseText);
                    console.log('Error:', error);
                    alert('Error fetching faculty information. Check console for details.');
                    resetTable();
                }
            });

        });

        $('#btnPrint').on('click', function (e) {
            e.preventDefault();
            printSection('loads');
        });

        $('#btnPrint2').on('click', function (e) {
            e.preventDefault();
            printSection('canteach');
        });

        function printSection(sectionId) {
            var content = document.getElementById(sectionId).innerHTML;

            // Create a new window for printing
            var printWindow = window.open('', '', 'height=500, width=800');
            printWindow.document.write('<html><head><title>Print Info</title>');
            printWindow.document.write('<link rel="stylesheet" href="path/to/your/css/style.css">'); // Optional CSS
            printWindow.document.write('</head><body>');
            printWindow.document.write(content);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();

            // Print after content loads
            setTimeout(function () {
                printWindow.print();
                printWindow.close();
            }, 500);
        }
        function resetTable() {
            $('#profName').text('-');
            $('#empStatus').text('-');
            $('#department').text('-');
            $('#empType').text('-');
            $('#canTeachSubjects').html('<tr><td colspan="3" class="no-record">No record of subjects that the faculty can teach.</td></tr>');
            
            $('#loads').hide();
            $('#canteach').hide();
        }
    });

});

</script>



<script>
    // Load navbar dynamically
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
                // Dynamically load app.js if not already loaded
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
</script>

<script>
            // Load navbar dynamically
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
                        // Dynamically load app.js if not already loaded
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
        </script>
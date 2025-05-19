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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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
                <li class="active">Summary Faculty Load | Per College/Department</li>
            </ul>

        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>FACULTY PAGE - SUMMARY FACULTY LOAD (PER COLLEGE/DEPARTMENT)</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form>
                <div class="mb-4 flex items-center space-x-4">
                    <div class="flex-1">
                        <label for="year-term-from" class="block font-bold text-sm mb-2">SCHOOL YEAR*</label>
                        <div class="flex items-center">
                            <input type="number" id="year-term-from" placeholder="From" class="w-full p-2 border border-gray-300 rounded">
                            <span class="mx-2 font-bold">TO</span>
                            <input type="number" id="year-term-to" placeholder="To" class="w-full p-2 border border-gray-300 rounded">
                        </div>
                    </div>
                </div>
                
                <div class="flex-1 mb-4 ">
                    <label for="term" class="block font-bold text-sm mb-2">TERM*</label>
                    <select id="term" class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                        <option selected disabled>Select Term</option>
                        <option value="1st Semester">1st Semester</option>
                        <option value="2nd Semester">2nd Semester</option>
                        <option value="Summer">Summer</option>
                    </select>
                </div>

                <!-- College -->
                 <div class="mb-4">
                    <label for="college" class="block font-bold text-sm mb-2">COLLEGE*</label>
                    <select id="college" class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                        <option value="" selected disabled>Select College</option>
                    </select>
                </div>

                <!-- Proceed Button -->
                <div class="flex justify-end">
                    <button id="btnProceed" type="button" style="background-color: #174069;"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Proceed
                    </button>
                </div>
            </form>
        </div>

        <div id="tableContainer" class="mt-6">
            
        </div>


        <div class="border-b-4 border-black my-4"></div>

        <script>

            $(document).ready(function () {
                $("#btnProceed").on("click", function () {
                    let year_from = $("#year-term-from").val();
                    let year_to = $("#year-term-to").val();
                    let term = $("#term").val();
                    let college = $("#college").val();

                    // Validate fields
                    if (!year_from || !year_to || !term || !college) {
                        alert("Please fill out all required fields.");
                        return;
                    }

                    // Make AJAX request to fetch the filtered data
                    $.ajax({
                        url: "../FacultyCanTeach1/api/facultyAPI.php",
                        type: "GET",
                        data: {
                            action: "getDeptLoad",
                            year_from: year_from,
                            year_to: year_to,
                            term: term,
                            college: college
                        },
                        success: function (data) {
                            let loadData = typeof data === "string" ? JSON.parse(data) : data;

                            if (loadData.length > 0) {
                                drawTable(loadData, year_from, year_to, term, college);
                            } else {
                                $("#tableContainer").html(`
                                    <div class="text-center text-red-500 font-bold mt-4">No data found for the selected filters.</div>
                                `);
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

                function drawTable(data, year_from, year_to, term, college) {
                    let collegeName = $("#college option:selected").text();
                    let tableHtml = `
                        <div class="border border-gray-300 p-4 shadow-md bg-white">
                            <div class="flex justify-end items-center space-x-4 mt-4">
                                <a href="#" id="btnPrint2" title="Print" class="bg-red-600 text-white p-2 text-sm rounded-full flex items-center">
                                    <i class="bi bi-printer mr-2 text-lg"></i> Print
                                </a>
                            </div>
                            <div class="text-center font-bold text-lg mb-2">
                                COLLEGE OF ${collegeName.toUpperCase()} <br />
                                S.Y. ${year_from} - ${year_to} | ${term}
                            </div>
                            <table class="w-full border-collapse border border-gray-400 mt-4">
                                <thead class="bg-gray-200">
                                    <tr>
                                        <th class="p-2 border border-gray-400">Professor</th>
                                        <th class="p-2 border border-gray-400">Subject</th>
                                        <th class="p-2 border border-gray-400">Unit/s</th>
                                        <th class="p-2 border border-gray-400">Schedule</th>
                                        <th class="p-2 border border-gray-400">Section</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                    let currentProfessor = "";
                    let rowspanCount = {};

                    data.forEach(function (item) {
                        let professorName = item.professor_name;
                        if (rowspanCount[professorName]) {
                            rowspanCount[professorName]++;
                        } else {
                            rowspanCount[professorName] = 1;
                        }
                    });

                    data.forEach(function (item, index) {
                        let professorName = item.professor_name;
                        let subject = `${item.subject_code} - ${item.subject_name}`;
                        let schedule = `${item.day} - ${item.class_time_from} - ${item.class_time_to}`;
                        let units = item.units;
                        let section = item.section;

                        if (professorName !== currentProfessor) {
                            tableHtml += `
                                <tr>
                                    <td class="p-2 border border-gray-400 font-bold text-center" rowspan="${rowspanCount[professorName]}">
                                        ${professorName}
                                    </td>
                                    <td class="p-2 border border-gray-400">${subject}</td>
                                    <td class="p-2 border border-gray-400 text-center">${units}</td>
                                    <td class="p-2 border border-gray-400">${schedule}</td>
                                    <td class="p-2 border border-gray-400 text-center">${section}</td>
                                </tr>
                            `;
                            currentProfessor = professorName;
                        } else {
                            tableHtml += `
                                <tr>
                                    <td class="p-2 border border-gray-400">${subject}</td>
                                    <td class="p-2 border border-gray-400 text-center">${units}</td>
                                    <td class="p-2 border border-gray-400">${schedule}</td>
                                    <td class="p-2 border border-gray-400 text-center">${section}</td>
                                </tr>
                            `;
                        }
                    });

                    tableHtml += `
                                </tbody>
                            </table>
                        </div>
                    `;

                    $("#tableContainer").html(tableHtml);
                }
            });

            $(document).on("click", "#btnPrint2", function (e) {
                e.preventDefault();

                let content = $("#tableContainer").html();

                let printWindow = window.open("", "", "width=1200,height=800");

                printWindow.document.write(`
                    <html>
                    <head>
                        <title>Faculty Load by Department</title>
                        <style>
                            @media print {
                                @page {
                                    size: landscape; /* Set landscape mode */
                                    margin: 1cm; /* Add margin */
                                }
                                body {
                                    font-family: Arial, sans-serif;
                                    margin: 20px;
                                }
                                .border {
                                    border: 1px solid #ccc;
                                }
                                .p-2 {
                                    padding: 8px;
                                }
                                .text-center {
                                    text-align: center;
                                }
                                .text-lg {
                                    font-size: 1.25rem;
                                }
                                .font-bold {
                                    font-weight: bold;
                                }
                                .mt-4 {
                                    margin-top: 16px;
                                }
                                table {
                                    width: 100%;
                                    border-collapse: collapse;
                                }
                                th, td {
                                    padding: 8px;
                                    border: 1px solid #ccc;
                                    text-align: center;
                                }
                                .bg-gray-200 {
                                    background-color: #f2f2f2;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        ${content}
                    </body>
                    </html>
                `);

                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            });

        fetchDepartments();

        function fetchDepartments() {
            $.ajax({
                url: '../FacultyCanTeach1/api/facultyAPI.php',
                type: 'GET',
                data: {
                    action: 'getDepartments'
                },
                success: function (response) {

                    console.log('Response:', response);
                    console.log('Type:', typeof response);

                    var data = typeof response === 'string' ? JSON.parse(response) : response;

                    var collegeSelect = $('#college');
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
                },
                error: function (xhr, status, error) {
                    console.log('Error Status:', status);
                    console.log('XHR Response:', xhr.responseText);
                    console.log('Error:', error);
                    alert('Error loading departments. Check console for details.');
                }
            });
            $('#college').on('change', function () {

                var selectedValue = $(this).val();
                var selectedText = $('#college option:selected').text();
                console.log('Selected College ID:', selectedValue);
                console.log('Selected College Name:', selectedText);
                $('#subject_autocomp').val('');
                $('#professor_autocomp').val('');

                $.ajax({ // get courses if college is selected
                    url: '../FacultyCanTeach1/api/facultyAPI.php',
                    type: 'GET',
                    data: {
                        action: 'getCourses',
                        id: selectedValue
                    },
                    success: function (response) {

                        console.log('Response:', response);
                        console.log('Type:', typeof response);

                        var data = typeof response === 'string' ? JSON.parse(response) : response;

                        var collegeSelect = $('#courses');
                        collegeSelect.empty();
                        collegeSelect.append('<option value="">Select Course</option>');

                        if (data.length > 0) {
                            data.forEach(function (course) {
                                collegeSelect.append(
                                    `<option value="${course.course_id}">${course.course_name}</option>`
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

            });
        }
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
    </div>
    <div class="fixed bottom-6 right-6">
        <a href="FacSum.php" type="submit" style="background-color: #aaa;" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            Back
        </a>
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

    body {
        font-family: Arial, sans-serif;
        background-color: #e8f0f8;
        margin: 0;
        padding: 20px;
    }

    .container {
        width: 80%;
        margin: 0 auto;
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .form-group label {
        width: 20%;
        text-align: right;
        margin-right: 10px;
        font-weight: bold;
    }

    .form-group input,
    .form-group select {
        width: 70%;
        padding: 5px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    .form-group input[type="text"] {
        width: 40%;
    }

    .form-group .inline-inputs {
        display: flex;
        gap: 10px;
        width: 70%;
    }

    .buttons {
        text-align: center;
        margin-top: 20px;
    }

    .buttons button {
        background-color: #0056b3;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .buttons button:hover {
        background-color: #004494;
    }
</style>
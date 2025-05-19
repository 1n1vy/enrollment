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
    <title>Room Directory Form</title>
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
                <li class="active">Substitution</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>FACULTY PAGE - SUBSTITUTION</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form class="bg-white p-6 shadow-md rounded-md border border-gray-300">
                <div class="mb-4">
                    <label for="professor" class="block font-bold text-sm mb-2">Professor*</label>
                    <input type="text" class="w-full p-2 border border-gray-300 rounded"
                        id="professor_autocomp" placeholder="Employee ID, Firstname, Lastname" />
                </div>
                
                <input type="hidden" class="w-full p-2 border border-gray-300 rounded" id="professor_id" name="professor_id" />
                <div class="mb-4">
                    <label for="professor" class="block font-bold text-sm mb-2">Substitute Professor*</label>
                    <input type="text" class="w-full p-2 border border-gray-300 rounded"
                        id="professor_autocomp2" placeholder="Employee ID, Firstname, Lastname" />
                </div>
                <div class="mb-4">
                    <label for="professor" class="block font-bold text-sm mb-2">Substitute Date*</label>
                    <input type="date" class="w-full p-2 border border-gray-300 rounded"
                        id="date_picker" />
                </div>
                <input type="hidden" class="w-full p-2 border border-gray-300 rounded" id="professor_id2" name="professor_id2" />
                 <div class="mb-4">
                    <label for="courses" class="block font-bold text-sm mb-2">Section*</label>
                    <select id="courses" class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                        <option value="" selected disabled>Select Section</option>
                    </select>
                </div>
                <div class="mb-4 flex items-center space-x-4">
                    <div class="flex-1">
                        <label for="timeclass" class="block font-bold text-sm mb-2">Class Time*</label>
                        <div class="flex items-center">
                            <input type="text" id="class-time-from" placeholder="hh:mm AM/PM" class="w-full p-2 border border-gray-300 rounded" readonly>
                            <span class="mx-2 font-bold">TO</span>
                            <input type="text" id="class-time-to" placeholder="hh:mm AM/PM" class="w-full p-2 border border-gray-300 rounded" readonly>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4 flex items-center space-x-4">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <input type="hidden" id="section" placeholder="Section" class="w-full p-2 border border-gray-300 rounded" readonly>
                            <input type="hidden" id="subject_id" placeholder="subject" class="w-full p-2 border border-gray-300 rounded" readonly>
                        </div>
                    </div>
                </div>
                <!-- Proceed Button -->
                <div class="flex justify-end mt-4">
                    <button type="submit" id="btnProceed" class="bg-blue-700 text-white font-bold py-2 px-6 rounded shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Add Faculty Substitute
                    </button>
                </div>
            </form>
        </div>

        <div class="border-b-4 border-black my-4"></div>

        <script>

            $("#btnProceed").on("click", function (e) {
                e.preventDefault();

                var formData = {
                    action: "addSubstitution",
                    professor_id: $("#professor_id").val(),
                    substitute_professor_id: $("#professor_id2").val(),
                    section: $("#section").val(),
                    subject_id: $("#subject_id").val(),
                    substitute_date: $("#date_picker").val(),
                    class_time_from: $("#class-time-from").val(),
                    class_time_to: $("#class-time-to").val()
                };
                console.log(formData);
                

                $.ajax({
                    url: "../FacultyCanTeach1/api/facultyAPI.php",
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        let res = typeof response === "string" ? JSON.parse(response) : response;

                        if (res.status === "success") {
                            alert("Substitution added successfully!");
                            window.location.reload();
                        } else {
                            alert("Error: " + res.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error inserting data:", xhr.responseText);
                        alert("An error occurred while inserting data.");
                    }
                });
            });

            $("#professor_autocomp").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "../FacultyCanTeach1/api/facultyAPI.php",
                        type: "GET",
                        data: {
                            action: "getProfessorSuggestions",
                            term: request.term
                        },
                        success: function (data) {
                            let suggestions = typeof data === "string" ? JSON.parse(data) : data;
                            response(
                                suggestions.map(function (item) {
                                    return {
                                        label: item.first_name + " " + item.last_name + " (" + item.employee_id + ")",
                                        value: item.first_name + " " + item.last_name,
                                        id: item.employee_id
                                    };
                                })
                            );
                        },
                        error: function (xhr, status, error) {
                            console.error("Error:", error);
                        }
                    });
                },
                minLength: 1,
                select: function (event, ui) {
                    console.log("Selected:", ui.item.label);

                    $("#professor_id").val(ui.item.id);
                    $("#professor_autocomp").data("professor_id", ui.item.id);

                    let professorID = $("#professor_autocomp").data("professor_id");
                    console.log("Professor ID:", professorID);

                    fetchFacultyLoad(professorID);
                }
            });

            $("#professor_autocomp2").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "../FacultyCanTeach1/api/facultyAPI.php",
                        type: "GET",
                        data: {
                            action: "getProfessorSuggestions",
                            term: request.term
                        },
                        success: function (data) {
                            let suggestions = typeof data === "string" ? JSON.parse(data) : data;
                            response(
                                suggestions.map(function (item) {
                                    return {
                                        label: item.first_name + " " + item.last_name + " (" + item.employee_id + ")",
                                        value: item.first_name + " " + item.last_name,
                                        id: item.employee_id
                                    };
                                })
                            );
                        },
                        error: function (xhr, status, error) {
                            console.error("Error:", error);
                        }
                    });
                },
                minLength: 1,
                select: function (event, ui) {
                    console.log("Selected:", ui.item.label);

                    $("#professor_id2").val(ui.item.id);
                    $("#professor_autocomp2").data("professor_id2", ui.item.id);

                    let professorID = $("#professor_autocomp2").data("professor_id2");
                    console.log("Professor ID:", professorID);

                    fetchFacultyLoad(professorID);
                }
            });

            function fetchFacultyLoad(professorID) {
                $.ajax({
                    url: "../FacultyCanTeach1/api/facultyAPI.php",
                    type: "GET",
                    data: {
                        action: "getFacultyLoad2",
                        professor_id: professorID
                    },
                    success: function (data) {
                        let loadData = typeof data === "string" ? JSON.parse(data) : data;
                        let options = '<option value="" selected disabled>Select Section</option>';

                        if (loadData.length > 0) {
                            loadData.forEach(function (item) {
                                options += `
                                    <option value="${item.suject_id}" 
                                            data-time-from="${item.class_time_from}" 
                                            data-time-to="${item.class_time_to}"
                                            data-section="${item.section}"
                                            data-subject-id="${item.subject_id}">
                                        [${item.section}] ${item.subject_name} - ${item.day} - ${item.class_time_from} - ${item.class_time_to}
                                    </option>`;
                            });
                        } else {
                            options = '<option value="" selected disabled>No subjects found</option>';
                        }

                        $("#courses").html(options);
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching faculty load:", error);
                    }
                });
            }

            $("#courses").on("change", function () {
                let selectedOption = $("#courses option:selected");

                let classTimeFrom = selectedOption.data("time-from") || '';
                let classTimeTo = selectedOption.data("time-to") || '';
                let section = selectedOption.data("section") || '';
                let subjectId = selectedOption.data("subject-id") || '';

                $("#class-time-from").val(classTimeFrom);
                $("#class-time-to").val(classTimeTo);
                $("#section").val(section);
                $("#subject_id").val(subjectId);
            });

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
</style>
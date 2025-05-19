<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../../includes/auth.php';
redirectIfNotLoggedIn();

// Optionally, restrict access by role
checkRole(['Admin', 'Registrar']);


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
    <title>Religion-Statistics</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../Custom/assets/religion-dropdown.js"></script>
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
                <li><a href="#">Statistics</a></li>
                <li class="active">Religion-Statistics</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>STATISTICS-RELIGIONS PAGE</h1>
        </section>

        <div class="form-container">
            <form method="GET">
                <div class="form-group" style="display: flex; justify-content:left;">
                    <div>
                        <label for="year" class="text-sm font-medium text-gray-700">School Year</label>
                        <div style="display: flex; gap: 10px; text-align: left; width:400px">
                            <input type="number" id="schoolyear1" name="year1" placeholder="Enter Year" class="form-input px-3 py-2 border border-gray-300 rounded-md" min="1975" max="2026" step= "1" required>
                            <span class="mt-2">to</span>
                            <input type="number" id="schoolyear2" name="year2" placeholder="Enter Year" class="form-input px-3 py-2 border border-gray-300 rounded-md" min="1975" max="2026" step= "1" required>
                        </div>
                    </div>
                    <div id="semester-container" style="display: none;">
                        <label for="term" class="text-sm font-medium text-gray-700" style="margin-left: 15px;">Semester</label>
                        <select id="term" name="term" class="form-select px-3 py-2 border border-gray-300 rounded-md" style="margin-left: 10px;" required>
                            <option value="1st">1st Sem</option>
                            <option value="2nd">2nd Sem</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="display: flex; justify-content: space-between; gap: 20px;">
                    <div style="flex: 1;">
                        <label for="yearlevel" class="text-sm font-medium text-gray-700">Level</label>
                        <select id="education-level-select" name="yearlevel" class="form-select px-3 py-2 border border-gray-300 rounded-md w-full" onchange="updateSubLevel()" required>
                        </select>
                    </div>
                </div>

                <div class="flex justify-center mt-4">
                    <button type="submit" formaction="ReligionPage.php" class="bg-blue-900 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700">Proceed</button>
                </div>
            </form>
        </div>


        <!-- Navbar script -->
        <script>
            function updateSubLevel() {
                    const level = document.getElementById('yearlevel').value;
                    const sublevelContainer = document.getElementById('sublevel-container');
                    const sublevel = document.getElementById('sublevel');
                    const semesterContainer = document.getElementById('semester-container');

                    sublevelContainer.style.display = level ? 'block' : 'none';
                    semesterContainer.style.display = level === 'College' ? 'block' : 'none';

                    let options = [];
                    if (level === 'Primary') {
                        options = ['Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'];
                    } else if (level === 'Junior High School') {
                        options = ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'];
                    } else if (level === 'Senior High School') {
                        options = ['Grade 11', 'Grade 12'];
                    } else if (level === 'College') {
                        options = ['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year', '6th Year'];
                    }

                    sublevel.innerHTML = options.map(option => `<option value="${option}">${option}</option>`).join('');
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

            function redirectToReligion(event) {
                event.preventDefault(); // Prevent default form submission

                // Get form fields
                const schoolYear1 = document.getElementById("schoolyear1").value.trim();
                const schoolYear2 = document.getElementById("schoolyear2").value.trim();
                const date = document.getElementById("date").value.trim();
                const yearLevel = document.getElementById("yearlevel").value;
                const subLevelContainer = document.getElementById("sublevel-container");
                const subLevel = document.getElementById("sublevel") ? document.getElementById("sublevel").value : null;
                const semesterContainer = document.getElementById("semester-container");
                const semester = document.getElementById("term") ? document.getElementById("term").value : null;

                // Validate required fields
                if (!schoolYear1 || !schoolYear2) {
                    alert("Please enter both start and end years.");
                    return;
                }

                if (parseInt(schoolYear1) > parseInt(schoolYear2)) {
                    alert("The starting year cannot be greater than the ending year.");
                    return;
                }

                if (!date) {
                    alert("Please select a date.");
                    return;
                }

                if (!yearLevel) {
                    alert("Please select a year level.");
                    return;
                }

                if (subLevelContainer.style.display !== "none" && (!subLevel || subLevel === "")) {
                    alert("Please select a sub-level.");
                    return;
                }

                if (semesterContainer.style.display !== "none" && (!semester || semester === "")) {
                    alert("Please select a semester.");
                    return;
                }

                // If all validations pass, proceed to redirection
                window.location.href = 'ReligionPage.php';
            }

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

</html>
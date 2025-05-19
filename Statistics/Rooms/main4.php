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
    <title>Rooms-Statistics</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
                <li class="active">Rooms</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>STATISTICS-ROOMS PAGE</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form action="#">
                <!-- School Year and Term -->
                <div class="form-group" style="display: flex; justify-content:left;">
                    <div>
                        <label for="year" class="text-sm font-medium text-gray-700">School Year</label>
                        <div style="display: flex; gap: 10px; text-align: left; width:400px">
                            <input type="number" id="schoolyear1" name="year1" placeholder="Enter Year" class="form-input px-3 py-2 border border-gray-300 rounded-md" min="1975" max="2026" step= "1" required>
                            <span class="mt-2">to</span>
                            <input type="number" id="schoolyear2" name="year2" placeholder="Enter Year" class="form-input px-3 py-2 border border-gray-300 rounded-md" min="1975" max="2026" step= "1" required>
                        </div>
                    </div>
                    <div>
                        <label for="term" class="text-sm font-medium text-gray-700" style="margin-left: 15px;">Semester</label>
                        <select id="term" name="term" class="form-select px-3 py-2 border border-gray-300 rounded-md" style="margin-left: 10px;" required>
                            <option value="1st">1st Sem</option>
                            <option value="2nd">2nd Sem</option>
                        </select>
                    </div>
                </div>

                <!-- Room Status and Location -->
                <div class="form-group" style="display: flex; justify-content: space-between; gap: 20px;">
                    <div style="flex: 1;">
                        <label for="room" class="text-sm font-medium text-gray-700">Room Status</label>
                        <select id="room-status" name="room-status" class="form-select w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            <option value="all">All</option>
                            <option value="occupied">Occupied</option>
                            <option value="available">Available</option>
                        </select>
                    </div>
                    <div style="flex: 1;">
                        <label for="location" class="text-sm font-medium text-gray-700">Location</label>
                        <select id="location" name="location" class="form-select px-3 py-2 border border-gray-300 rounded-md w-full" required>
                            <option value="all">All Buildings</option>
                            <option value="bldgA">Building A</option>
                            <option value="bldgB">Building B</option>
                            <option value="main">Main</option>
                            <option value="som">SOM</option>
                            <option value="psb">PSB</option>
                            <option value="psb">Library</option>

                        </select>
                    </div>
                </div>

                <!-- Range and Type -->
                <div class="form-group" style="display: flex; justify-content: space-between; gap: 20px;">
                    <!-- <div style="flex: 1;">
                        <div style="display: flex; align-items: center;">
                            <label for="days" class="text-sm font-medium text-gray-700">Range</label>
                            <span class="text-gray-500 text-xs mb-2" style="margin-left: 8px;">(m-t-w-th-f-sat-s)</span>
                        </div>
                        <input type="text" id="days" name="days" placeholder="Enter" class="form-input px-3 py-2 border border-gray-300 rounded-md w-full">
                    </div> -->
                    <div style="flex: 1;">
                        <label for="roomType" class="text-sm font-medium text-gray-700">Type</label>
                        <select id="room" name="room" class="form-select px-3 py-2 border border-gray-300 rounded-md w-full" required>
                            <option value="all">All</option>
                            <option value="accreditation">Accreditation Room</option>
                            <option value="activity">Activity Room</option>
                            <option value="anatomy">Anatomy Laboratory</option>
                            <option value="asean">Asean Research Center</option>
                            <option value="avr">AVR</option>
                            <option value="laboratory">Laboratory Room</option>
                            <option value="bstore">Bookstore</option>
                            <option value="canteen">Canteen</option>
                        </select>
                    </div>
                </div>

                <!-- Schedule Time -->
                <div class="form-group mt-4">
                    <label for="scheduleTime" class="text-sm font-medium text-gray-700">Schedule Time</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="time" id="startTime" name="startTime" class="form-input w-16 px-3 py-2 border border-gray-300 rounded-md" required>
                        <span style="margin-top: 9px;">to</span>
                        <input type="time" id="endTime" name="endTime" class="form-input w-16 px-3 py-2 border border-gray-300 rounded-md"required>
                    </div>
                    <span class="text-gray-500 text-xs">(Please enter correct time format)</span>
                </div>

                <!-- Room Number -->
                <div class="form-group mb-4">
                    <label for="arrange-result" class="block text-sm font-medium text-gray-700">Room Number (Optional) </label>
                    <input type="text" id="roomnum" name="roomnum" placeholder="Enter Room No." class="form-input px-3 py-2 border border-gray-300 rounded-md w-full">
                    <p class="text-gray-500 text-xs text-left mt-2">(Optional. This field takes room #; Ex. input CEN outputs CENRUM 01/CENTRUM 02...)</p>
                    <div class="mt-2">
                        <label style="height: 20px;">
                            <input type="checkbox" name="show_room_numbers_grouped" value="1" class="form-checkbox" style="height: 10px; width: 30px;">
                            <span style="font-size: smaller;">Show room number per room type/location</span>
                        </label>
                        <label style="height: 20px;">
                            <input type="checkbox" name="show_room_numbers_only" value="1" class="form-checkbox" style="height: 10px; width: 30px;">
                            <span style="font-size: smaller;">(Show only room number. Type/Location will not be included)</span>
                        </label>
                    </div>
                </div>

                <!-- Proceed Button -->
                <div class="flex justify-center mt-4">
                    <button type="submit" formaction="/capst/Statistics/Rooms/RoomsPage.php"  class="bg-blue-900 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700">Proceed</button>
                </div>
            </form>
        </div>


        <!-- Navbar script -->
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

            
            function redirectToRoom(event) {
                event.preventDefault(); // Prevent default button behavior

                // Get form elements
                let schoolYear1 = document.getElementById('schoolyear1').value;
                let schoolYear2 = document.getElementById('schoolyear2').value;
                let term = document.getElementById('term').value;
                let roomStatus = document.getElementById('room-status').value;
                let location = document.getElementById('location').value;
                let roomType = document.getElementById('room').value;
                let startTime = document.getElementById('startTime').value;
                let endTime = document.getElementById('endTime').value;

                // Validation logic
                if (!schoolYear1 || !schoolYear2) {
                    alert("Please enter a valid school year.");
                    return;
                }
                if (parseInt(schoolYear1) >= parseInt(schoolYear2)) {
                    alert("School year range is invalid.");
                    return;
                }
                if (!term) {
                    alert("Please select a semester.");
                    return;
                }
                if (!roomStatus) {
                    alert("Please select a room status.");
                    return;
                }
                if (!location) {
                    alert("Please select a location.");
                    return;
                }
                if (!roomType) {
                    alert("Please select a room type.");
                    return;
                }
                if (!startTime || !endTime) {
                    alert("Please enter a valid schedule time.");
                    return;
                }
                if (startTime >= endTime) {
                    alert("Start time must be before end time.");
                    return;
                }

                // If all validations pass, redirect to the next page
                window.location.href = 'RoomsPage.php';
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
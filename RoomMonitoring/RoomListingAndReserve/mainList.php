<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require '../../includes/auth.php';
redirectIfNotLoggedIn();

// Optionally, restrict access by role
checkRole(['Admin', 'Building Manager']);

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

// Fetch rooms
$queryStatus = "SELECT DISTINCT status FROM rooms WHERE status IS NULL OR status != 'Unavailable'";
$resultStatus = $conn->query($queryStatus);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Listing and Reserve</title>
    <!-- Load Google Fonts Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar placeholder -->
    <div id="navbar-placeholder"></div>

    <!-- Main content -->
    <div class="main-content p-6" id="mainContent">
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Enrollment</a></li>
                <li><a href="#">Rooms Monitoring</a></li>
                <li class="active">Room Listing and Reserve</li>
            </ul>
        </nav>

        <section class="section-header mb-20">
            <h1 class="text-2xl font-semibold">Room Availability Information</h1>
        </section>

        <div class="form-container">

        <form id="filterForm" action="tableList.php" method="GET" onsubmit="validateForm(event)">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Left Column -->
        <div class="left-column">
            <div class="form-group mb-4">
                <label for="schoolYear" class="block font-bold text-sm">SCHOOL YEAR<span class="text-red-500">*</span></label>
                <div class="flex space-x-2">
                    <input type="text" id="year1" name="year1" class="p-2 border border-gray-300 rounded" placeholder="Enter" required>
                    <input type="text" id="year2" name="year2" class="p-2 border border-gray-300 rounded" placeholder="Enter" required>
                </div>
            </div>
            <div class="form-group">
                <label for="room">ROOM STATUS<span class="text-red-500">*</span></label>
                <select id="room-status" name="status" class="p-2 border border-gray-300 rounded" required>
                    <option value="" disabled selected>Select Room Status</option>
                    <?php while ($row = $resultStatus->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row['status']); ?>">
                            <?php echo htmlspecialchars($row['status'] ?: 'No Status'); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>

        <!-- Right Column -->
        <div class="right-column">
            <div class="form-group">
                <label for="term">TERM<span class="text-red-500">*</span></label>
                <select id="term" name="term" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="" disabled selected>Select Term</option>
                    <option value="1st-Semester">1st Semester</option>
                    <option value="2nd-Semester">2nd Semester</option>
                    <option value="summer">Summer</option>
                </select>
            </div>
            <div class="form-group">
                <label for="location">LOCATION<span class="text-red-500">*</span></label>
                <select id="loc" name="building" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="" disabled selected>ALL BUILDINGS</option>
                    <option value="MAIN">MAIN</option>
                    <option value="ProfBldg">Professional School Building</option>
                    <option value="SOM">SOM</option>
                </select>
            </div>
        </div>
    </div>
                <div class="form-actions mt-8 flex justify-center space-x-4">
                    <!-- <a href="tableList.php" class="px-6 py-2 bg-blue-900 text-white rounded-md">PROCCED</a> -->
                    <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded-md">Search</button>
                </div>
            </form>
        </div>

    </div>
    <script>
        fetch('../../Components/Navbar.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('navbar-placeholder').innerHTML = data;
                // Load navbar script after inserting HTML
                var script = document.createElement('script');
                script.src = '../../Components/app.js';
                document.body.appendChild(script);
            });

            
    </script>
    </div>
</body>

</html>

<style scoped>
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

    .section-header {
        background-color: #174069;
        padding: 20px;
        text-align: center;
        margin-bottom: 20px;
    }

    .section-header h1 {
        color: white;
        font-size: 24px;
        margin: 0;
    }

    .form-container {
        width: 100%;
        background-color: #f4f8fc;
        padding: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;

    }

    .form-group {
        margin-top: 20px;
        margin-bottom: 15px;
        margin-left: 40px;
    }

    .form-group label {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 10px;
        display: block;
    }

    .form-group input,
    .form-group select {
        width: 50%;
        max-width: 400px;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-top: 5px;
        margin-right: 10px;
        gap: 10px;
    }
</style>
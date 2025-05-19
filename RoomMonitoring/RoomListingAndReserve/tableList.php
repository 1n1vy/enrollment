<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require '../../includes/auth.php';
redirectIfNotLoggedIn();
checkRole(['Admin', 'Building Manager']);

if (file_exists('../../includes/db_connection.php')) {
    require_once '../../includes/db_connection.php';
} else {
    die('Database connection file not found!');
}

// Retrieve filter values from GET request
$schoolYearStart = isset($_GET['school_year']) ? $_GET['school_year'] : '';
$schoolYearEnd = isset($_GET['school_year_end']) ? $_GET['school_year_end'] : '';
$term = isset($_GET['term']) ? $_GET['term'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';

$query = "SELECT room_number, floor, building_location FROM rooms WHERE 1=1";

if (!empty($schoolYearStart) && !empty($schoolYearEnd)) {
    $query .= " AND school_year_start = '$schoolYearStart' AND school_year_end = '$schoolYearEnd'";
}

if (!empty($term)) {
    $query .= " AND term = '$term'";
}

if (!empty($location)) {
    $query .= " AND building_location = '$location'";
}

if (!empty($statusFilter)) {
    $query .= " AND status = '$statusFilter'";
}

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room List Table</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
<div id="navbar-placeholder"></div>
    <div class="main-content" id="mainContent">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Enrollment</a></li>
                <li><a href="mainList.php">Room Listing and Reserve</a></li>
                <li class="active">Room Listing page</li>
            </ul>
        </nav>

        <section class="section-header mb-20">
            <h1 class="text-2xl font-semibold">Room Listing page</h1>
        </section>

        <div>
            <div class="container">
                <h2>ROOM STATUS:
                    <span style="color: rgb(207, 227, 98);">
                        <?php echo htmlspecialchars($statusFilter ?: 'ALL'); ?>
                    </span>
                </h2>
                <table id="rooms-table" class="table-auto border-collapse border border-gray-400 w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border border-gray-400 px-4 py-2">ROOM NUMBER</th>
                            <th class="border border-gray-400 px-4 py-2">ROOM LOCATION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="border border-gray-400 px-4 py-2"><?php echo htmlspecialchars($row['room_number']); ?></td>
                                <td class="border border-gray-400 px-4 py-2">
                                    <?php echo htmlspecialchars($row['floor'] . " - " . $row['building_location']); ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="flex justify-left">
            <a href="CreateReserve.php" type="submit" style="background-color: #174069;"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Proceed</a>(Go to Reserve Room)
        </div>

        <div class="border-b-4 border-black my-4"></div>

        <div class="fixed bottom-6 right-6">
            <a href="mainList.php" type="submit" style="background-color: #aaa;"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Back
            </a>
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
        margin: 0;
    }

    .form-group {
        display: flex;
        align-items: center;
        margin-left: 20px;
    }

    .inline-label {
        font-weight: bold;
        font-size: 14px;
    }

    .form-select-container select {
        width: 150px;
        padding: 5px;
        font-size: 14px;
        border: 1px solid #000000;
        border-radius: 4px;
        margin-left: 10px;
    }

    .total-rooms {
        margin-left: 15px;
        font-size: 14px;
        font-weight: bold;
        text-align: left;
        color: #000000;

    }

    /* Button styles */
    .form-actions button {
        padding: 10px 30px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-right: 10px;
    }

    .header {
        background-color: #174069;
        color: rgb(255, 255, 255);
    }

    h2 {
        text-align: left;
        font-weight: bold;
        background-color: #174069;
        padding: 10px;
        color: #ffffff;

    }

    .container {
        background-color: #ffffff;
        color: #000000;
        padding: 5px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #000000;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #174069;
        font-weight: bold;
    }
</style>
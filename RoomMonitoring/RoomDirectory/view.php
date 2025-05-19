<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require '../../includes/auth.php';
redirectIfNotLoggedIn();

// Optionally, restrict access by role
checkRole(['Admin', 'Registrar', 'Building Manager']);
var_dump($_SESSION); // Check if role_as is set properly

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: http://localhost/capst/index.php");
    exit();
}

// Include the database connection
if (file_exists('../../includes/db_connection.php')) {
    require_once '../../includes/db_connection.php';
} else {
    die('Database connection file not found!');
}

// Fetch buildings
$queryBuildings = "SELECT code, name FROM buildings";
$resultBuilding = $conn->query($queryBuildings);

// Fetch rooms floor
$queryRoomFloors = "SELECT DISTINCT floor FROM rooms WHERE status IS NULL OR status != 'Unavailable'";
$resultRoomFloor = $conn->query($queryRoomFloors);

// Fetch rooms room_type
$queryRoomTypes = "SELECT DISTINCT room_type FROM rooms WHERE status IS NULL OR status != 'Unavailable'";
$resultRoomType = $conn->query($queryRoomTypes);

// Fetch room data if editing
$roomData = [];
if (isset($_GET['id'])) {
    $room_id = intval($_GET['id']);
    $queryRoom = "SELECT * FROM rooms WHERE id = $room_id LIMIT 1";
    $resultRoom = $conn->query($queryRoom);
    if ($resultRoom->num_rows > 0) {
        $roomData = $resultRoom->fetch_assoc();
    }
}

// Show success modal if update was successful
$showSuccessModal = false;
if (isset($_SESSION['update_success']) && $_SESSION['update_success'] === true) {
    $showSuccessModal = true;
    unset($_SESSION['update_success']); // Clear the session variable after displaying the modal
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
                <li><a href="#">Rooms Monitoring</a></li>
                <li class="active">Room Directory and Creator</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>ROOMS MAINTENANCE PAGE</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
        <h1>
        <button onclick="location.reload();" class="text-white bg-blue-500 hover:bg- blue-700 font-bold py-2 px-4 rounded">
          <i class="bi bi-arrow-clockwise"></i> 
        </button> Click REFRESH to reload the page
        </h1>
            <form id="roomForm" action="update_room.php" method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <input type="hidden" name="id" value="<?php echo $roomData['id'] ?? ''; ?>">
                        <div class="form-group">
                            <label for="building">BUILDING</label>
                            <select id="building" name="building">
                                <option value="" disabled selected>Select Building</option>
                                <?php while ($row = $resultBuilding->fetch_assoc()): ?>
                                    <option value="<?php echo $row['code']; ?>" <?php echo isset($roomData['building_code']) && $roomData['building_code'] == $row['code'] ? 'selected' : ''; ?>>
                                        <?php echo $row['name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="floor">FLOOR</label>
                            <select id="floor" name="floor">
                                <option value="" disabled selected>Select Floor</option>
                                <?php while ($row = $resultRoomFloor->fetch_assoc()): ?>
                                    <option value="<?php echo $row['floor']; ?>" <?php echo isset($roomData['floor']) && $roomData['floor'] == $row['floor'] ? 'selected' : ''; ?>>
                                        <?php echo $row['floor']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="room-number">ROOM NUMBER</label>
                            <input type="text" id="room-number" name="room_number" placeholder="Enter" required value="<?php echo $roomData['room_number'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="room-capacity">ROOM CAPACITY</label>
                            <input type="number" id="room-capacity" name="room_capacity" placeholder="Enter" value="<?php echo $roomData['room_capacity'] ?? ''; ?>">
                        </div>

                        <div class="checkbox-wrapper flex flex-col mt-6 space-y-4">
                            <div class="checkbox-container flex items-center">
                                <input type="checkbox" id="no-subject" name="no_subject" class="mr-2" <?php echo isset($roomData['no_subject']) && $roomData['no_subject'] == 1 ? 'checked' : ''; ?>>
                                <label for="no-subject">Click if room is not used for subject assignment.</label>
                            </div>
                            <div class="checkbox-container flex items-center">
                                <input type="checkbox" id="room-conflict" name="room_conflict" class="mr-2" <?php echo isset($roomData['room_conflict']) && $roomData['room_conflict'] == 1 ? 'checked' : ''; ?>>
                                <label for="room-conflict">Click if room conflict is not checked during room assignment.</label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="form-group">
                            <label for="room-type">ROOM TYPE</label>
                            <select id="room-type" name="room_type">
                                <option value="" disabled selected>Select Room Type</option>
                                <?php while ($row = $resultRoomType->fetch_assoc()): ?>
                                    <option value="<?php echo $row['room_type']; ?>" <?php echo isset($roomData['room_type']) && $roomData['room_type'] == $row['room_type'] ? 'selected' : ''; ?>>
                                        <?php echo $row['room_type']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status">STATUS/REMARKS</label>
                            <input type="text" id="status" name="status" placeholder="Enter" value="<?php echo $roomData['status'] ?? ''; ?>">
                        </div>

                        <div class="form-group full-width">
                            <label for="description">DESCRIPTION</label>
                            <input type="text" id="description" name="description" placeholder="Enter" value="<?php echo $roomData['description'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="inspection-date">LAST DATE OF INSPECTION</label>
                            <input type="date" id="inspection-date" name="inspection_date" min="2017-01-01" max="2024-12-31" value="<?php echo $roomData['last_inspection_date'] ?? ''; ?>">
                        </div>
                    </div>
                </div>

                <div class="form-actions mt-8 flex justify-center">
                    <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded-md mr-4 hover:bg-blue-700 focus:outline-none">
                        <?php echo isset($roomData['id']) ? 'Update' : 'Add'; ?>
                    </button>

                    <button type="reset" class="px-6 py-2 bg-blue-900 text-white rounded-md">CLEAR</button>
                </div>
            </form>
        </div>

        <!-- Load Navbar and Script -->
        <script>
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
</body>

</html>

<!-- CSS styling -->
<style scoped>
    .hidden {
        display: none;
    }

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
</style>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require '../../includes/auth.php';
redirectIfNotLoggedIn(); // Ensure the user is logged in

// Include database connection
require_once('../../includes/db_connection.php');

// Check if we are editing an existing reservation
$reservation = null;
if (isset($_GET['edit'])) {
    $reservation_id = $_GET['edit'];
    $edit_query = "SELECT * FROM reservation_room WHERE id = ?";
    $edit_stmt = $conn->prepare($edit_query);
    $edit_stmt->bind_param("i", $reservation_id);
    $edit_stmt->execute();
    $edit_result = $edit_stmt->get_result();
    $reservation = $edit_result->fetch_assoc();
}

// Handle the form submission for creating or editing a reservation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $school_year_start = $_POST['school_year_start'];
    $school_year_end = $_POST['school_year_end'];
    $term = $_POST['term'];
    $activity_start = $_POST['activity_start'];
    $activity_end = $_POST['activity_end'];
    $reservation_start = $_POST['start_time'];
    $reservation_end = $_POST['end_time'];
    $room_number = $_POST['room_number'];
    $notes = $_POST['notes'];

    if (isset($_POST['reservation_id'])) {
        // Edit an existing reservation
        $reservation_id = $_POST['reservation_id'];
        $update_query = "UPDATE room_reservations SET school_year_start = ?, school_year_end = ?, term = ?, activity_start = ?, activity_end = ?, reservation_start= ?, reservation_end = ?, room_number = ?, notes = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sssssssssi", $school_year_start, $school_year_end, $term, $activity_start, $activity_end, $reservation_start, $reservation_end, $room_number, $notes, $reservation_id);
        $update_stmt->execute();

        // Redirect to a success page or the same page with a success message
        header("Location: resave.php?status=updated");
        exit();
    } else {
        // Create a new reservation
       // SQL for creating a new reservation
$insert_query = "INSERT INTO room_reservations (school_year_start, school_year_end, term, activity_start, activity_end, reservation_start, reservation_end, room_number, notes) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$insert_stmt = $conn->prepare($insert_query);
$insert_stmt->bind_param("sssssssss",$school_year_start, $school_year_end, $term, $activity_start, $activity_end, $reservation_start, $reservation_end, $room_number, $notes);
$insert_stmt->execute();

// SQL for updating an existing reservation
$update_query = "UPDATE room_reservations SET school_year_start = ?, school_year_end = ?, term = ?, activity_start= ?, activity_end = ?, reservation_start = ?, reservation_end = ?, room_number = ?, notes = ? WHERE id = ?";
$update_stmt = $conn->prepare($update_query);
$update_stmt->bind_param("sssssssssi", $school_year_start, $school_year_end, $term, $activity_start, $activity_end, $reservation_start, $reservation_end, $room_number, $notes, $reservation_id);
$update_stmt->execute();


        // Redirect to a success page or confirmation
        header("Location: resave.php?status=success");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve Room</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div id="navbar-placeholder"></div>

    <div class="main-content p-6" id="mainContent">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Enrollment</a></li>
                <li><a href="#">Room Listing and Reserve</a></li>
                <li class="active">Reserve Room</li>
            </ul>
        </nav>

        <section class="section-header mb-20">
            <h1 class="text-2xl font-semibold">Reserve Room</h1>
        </section>

        <div class="form-container">
            <form action="CreateReserve.php" method="POST">
                <?php if ($reservation): ?>
                    <!-- Hidden Input for Editing -->
                    <input type="hidden" name="reservation_id" value="<?php echo htmlspecialchars($reservation['id']); ?>">
                <?php endif; ?>

                <div class="two-columns">
                    <div class="left-column">
                        <div class="form-group">
                            <label for="schoolYearStart">SCHOOL YEAR<span class="text-red-500">*</span></label>
                            <div class="flex space-x-2">
                                <input type="text" name="school_year_start" class="p-2 border border-gray-300 rounded" placeholder="Start Year" value="<?php echo htmlspecialchars($reservation['school_year_start'] ?? ''); ?>" required>
                                <input type="text" name="school_year_end" class="p-2 border border-gray-300 rounded" placeholder="End Year" value="<?php echo htmlspecialchars($reservation['school_year_end'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="activityDate">ACTIVITY DATE<span class="text-red-500">*</span></label>
                            <div class="flex space-x-2">
                                <input type="date" name="activity_start" value="<?php echo htmlspecialchars($reservation['activity_start'] ?? ''); ?>" required>
                                <input type="date" name="activity_end" value="<?php echo htmlspecialchars($reservation['activity_end'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="room-number">ROOM NUMBER<span class="text-red-500">*</span></label>
                            <input type="text" name="room_number" placeholder="Enter Room Number" value="<?php echo htmlspecialchars($reservation['room_number'] ?? ''); ?>" list="room-numbers" required>
                        </div>
                        <div class="form-group">
                            <label for="notes">Others</label>
                            <textarea name="notes" class="custom-textarea" rows="3" placeholder="Enter additional details (Optional)"><?php echo htmlspecialchars($reservation['notes'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <div class="right-column">
    <div class="form-group">
        <label for="term">TERM<span class="text-red-500">*</span></label>
        <select id="term" name="term" class="w-full p-2 border border-gray-300 rounded" required>
            <option value="" disabled selected>Select Term</option>
            <option value="1st-Semester" <?php echo (isset($reservation['term']) && $reservation['term'] == '1st-Semester') ? 'selected' : ''; ?>>1st Semester</option>
            <option value="2nd-Semester" <?php echo (isset($reservation['term']) && $reservation['term'] == '2nd-Semester') ? 'selected' : ''; ?>>2nd Semester</option>
            <option value="summer" <?php echo (isset($reservation['term']) && $reservation['term'] == 'summer') ? 'selected' : ''; ?>>Summer</option>
        </select>
    </div>

                        <div class="form-group">
                            <label for="reservation-time">RESERVATION TIME<span class="text-red-500">*</span></label>
                            <div class="flex space-x-2">
                                <input type="time" name="start_time" value="<?php echo htmlspecialchars($reservation['start_time'] ?? ''); ?>" required>
                                <input type="time" name="end_time" value="<?php echo htmlspecialchars($reservation['end_time'] ?? ''); ?>" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded-md">Save</button>
                </div>
            </form>
        </div>

        <div class="fixed bottom-6 right-6">
            <a href="tableList.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Back
            </a>
        </div>
    </div>

    <script>
        fetch('../../Components/Navbar.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('navbar-placeholder').innerHTML = data;
                var script = document.createElement('script');
                script.src = '../../Components/app.js';
                document.body.appendChild(script);
            });
    </script>

</body>

</html>

<style scoped>
        .breadcrumb-nav {
            margin-bottom: 5px;
            font-size: 14px;
        }
        .breadcrumb {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
           
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
            padding: 20px;
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
        .custom-textarea {
            width: 100%;
            margin-top: 5px;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #000;
            border-radius: 4px;
            background-color: #ffffff;
        }
        .form-actions {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .two-columns {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .left-column, .right-column {
            width: 48%;
        }
</style>
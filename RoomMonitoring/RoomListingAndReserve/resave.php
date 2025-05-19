<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();
require '../../includes/auth.php';
redirectIfNotLoggedIn();  // Ensure the user is logged in


require_once('../../includes/db_connection.php');

$query = "SELECT * FROM room_reservations"; 
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

// If no records are found
if ($result->num_rows == 0) {
    echo "No reservations found.";
    exit();
}

$reservation = null;
if (isset($_GET['edit'])) {
    $reservation_id = $_GET['edit'];
    $edit_query = "SELECT * FROM room_reservations WHERE id = ?";
    $edit_stmt = $conn->prepare($edit_query);
    $edit_stmt->bind_param("i", $reservation_id);
    $edit_stmt->execute();
    $edit_result = $edit_stmt->get_result();
    $reservation = $edit_result->fetch_assoc();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $reservation_id = $_GET['delete'];
    $delete_query = "DELETE FROM room_reservations WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $reservation_id);
    $delete_stmt->execute();
    header("Location: resave.php?status=deleted");
    exit();
}

// Update Reservation Data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $room_number = $_POST['room_number'];
    $activity_start = $_POST['activity_start'];
    $activity_end = $_POST['activity_end'];
    $reservation_start = $_POST['reservation_start'];
    $reservation_end = $_POST['reservation_end'];
    $notes = $_POST['notes'];
    $reserved_by = $_POST['reserved_by'];

    $update_query = "UPDATE room_reservations SET room_number = ?, activity_start = ?, activity_end = ?, reservation_start = ?, reservation_end = ?, notes = ?, reserved_by = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sssssssi", $room_number, $activity_start, $activity_end, $reservation_start, $reservation_end, $notes, $reserved_by, $id);
    $update_stmt->execute();

    header("Location: resave.php?status=updated");
    exit();
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
                <li class="active">Reservation Note</li>
            </ul>
        </nav>

        <section class="section-header mb-20">
            <h1 class="text-2xl font-semibold">Reservation Details</h1>
        </section>

        <div class="form-container">
            <table id="resaveTable">
                <thead class="header">
                    <tr>
                        <th>ROOM RESERVED</th>
                        <th>RESERVED ON</th>
                        <th>RESERVED TIME</th>
                        <th>RESERVATION NOTE</th>
                        <th>RESERVED BY</th>
                        <th>EDIT</th>
                        <th>REMOVE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($row['room_number']); ?></td>
                            <td class="px-4 py-2">
                                <?php echo htmlspecialchars($row['activity_start']); ?> - <?php echo htmlspecialchars($row['activity_end']); ?>
                            </td>
                            <td class="px-4 py-2">
                                <?php echo htmlspecialchars($row['reservation_start']); ?> - <?php echo htmlspecialchars($row['reservation_end']); ?>
                            </td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($row['notes']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($row['reserved_by']); ?></td>
                            <td class="px-4 py-2">
                            <a href="?edit=<?php echo $row['id']; ?>" class="bi bi-pencil" title="Edit"></a>
                                </td>
                                <td class="px-4 py-2">
                                    <a href="?delete=<?php echo $row['id']; ?>" class="bi bi-trash" title="Delete" onclick="return confirm('Are you sure you want to delete this reservation?');"></a>
                                </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="fixed bottom-6 right-6">
            <a href="CreateReserve.php" type="submit" style="background-color: #aaa;"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Back
            </a>
        </div>

    </div>
</body>

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
            </form>
        </div>
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
        width: 90%;
        margin: 30px auto;
        background-color: #f4f8fc;
        padding: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .form-group {
        margin-bottom: 10px;
        margin-top: 10px;
    }

    .form-group label {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 10px;
        margin-top: 1px;
        margin-left: 20px;
        display: block;
    }

    .form-group input,
    .form-group select {
        width: 45%;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-left: 20px;
    }

    .to {
        margin: 0 10px;
        font-weight: bold;
    }

    .form-actions button {
        font-size: 14px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin: 5px;
    }

    .notes {
        background-color: #f4f8fc;
        border: 1px solid #000000;
        border-radius: 4px;
    }

    .custom-textarea {
        width: 80%;
        margin-left: 20px;
        padding: 10px;
    }

    .header {
        background-color: #174069;
        color: rgb(255, 255, 255);
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
    .bi-pencil:hover {
        color: #ff8c00; /* Change color when hovered */
        cursor: pointer;
    }

    .bi-trash:hover {
        color: #ff0000; /* Change color when hovered */
        cursor: pointer;
    }

    
</style>
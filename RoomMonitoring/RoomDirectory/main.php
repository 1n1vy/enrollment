<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
session_start();

require '../../includes/auth.php';
redirectIfNotLoggedIn();

// Optionally, restrict access by role
checkRole(['Admin', 'Registrar', 'Building Manager']);

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_room_id'])) {
    $deleteRoomId = intval($_POST['delete_room_id']); 

    // Check if the room exists (optional, for validation)
    $checkQuery = "SELECT id FROM rooms WHERE id = $deleteRoomId";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        // Delete the room from the database
        $deleteQuery = "DELETE FROM rooms WHERE id = $deleteRoomId";
        if ($conn->query($deleteQuery) === TRUE) {
            $deleteSuccess = true; // Set a success flag
        } else {
            $deleteError = "Failed to delete the room. Error: " . $conn->error;
        }
    } else {
        $deleteError = "Room not found or already deleted.";
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="../../styles.css"> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    

    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>

</head>

<body>

    <nav id="navbar-placeholder">
        <p>Loading navbar...</p>
    </nav>
    <div class="main-content" id="mainContent">
        <?php if (isset($deleteSuccess) && $deleteSuccess): ?>
            <div class="alert alert-success">
                Room deleted successfully.
            </div>
        <?php elseif (isset($deleteError)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($deleteError); ?>
            </div>
        <?php endif; ?>


        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Enrollment</a></li>
                <li><a href="#">Rooms Monitoring</a></li>
                <li><a href="create.php">Room Directory Creator</a></li>
                <li class="active">List of Existing Rooms</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>LIST OF ROOM SCHEDULES</h1>
        </section>

        <div class="flex items-center justify-between">
            <!-- Left section: Title, chevron, and pen icon -->
            <div class="flex items-center">

            </div>
        </div>

        <div class="overflow-x-auto mt-6">
            <table id="myTable" class="display">
                <thead>
                    <tr>
                        <th>Building</th>
                        <th>Floor</th>
                        <th>Room #</th>
                        <th>Type</th>
                        <th>Section</th>
                        <th>Subject</th>
                        <th>Day/s</th>
                        <th>Time</th>
                        <th>Term</th>
                        <th>S.Y.</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="border-b-4 border-black my-4"></div>

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
<script>
    

</script>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable({
            ajax: {
                url: 'http://localhost/capst/ClassProgram/programAPI.php?action=fetchRooms',
                type: 'GET',
                dataSrc: 'data'
            },
            columns: [
                { data: 'building_code' },
                { data: 'floor' },
                { data: 'room_number' },
                { data: 'room_type' },
                { data: 'section' },
                { data: 'subject' },
                { data: 'days' },
                { data: 'time' },
                { data: 'term' },
                { data: 'school_year' },
            ]
        });
    });
</script>
</html>
<style scoped>
    .navbar {
        background-image: url('../../images/cover.png');
    }

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

    .icon-link {
        margin-right: 15px;
        text-decoration: none;
        color: #000000;
    }
</style>
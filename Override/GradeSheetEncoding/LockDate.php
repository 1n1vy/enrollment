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
    <title>Override</title>
    <!-- Load Google Fonts Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar placeholder -->
    <nav id="navbar-placeholder">
        <p>Loading navbar...</p>
    </nav>

    <!-- Main content -->
    <div class="main-content p-6" id="mainContent">
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Enrollment</a></li>
                <li><a href="#">Override Parameters</a></li>
                <li><a href="#">Lock Garde Sheet Encoding</a></li>
                <li class="active">Grade Sheet Locking page</li>
            </ul>
        </nav>

        <section class="section-header mb-20">
            <h1 class="text-2xl font-semibold">SET GRADE SHEET LOCKING PAGE</h1>
        </section>

        <div class="form-container">

            <form action="#">
                <div class="info-container">
                    <table class="min-w-full border-collapse border border-black">
                        <tr>
                            <td class="border border-black px-4 py-2">Lock Status: NOT ACTIVE</td>
                        </tr>
                        <tr>
                            <td class="border border-black px-4 py-2">Locked By: xxxx</td>
                        </tr>
                        <tr>
                            <td class="border border-black px-4 py-2">Locked Ranged: xxxx</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="border-b-4 border-black my-4"></div>
                <div class="info-container">
                    <table class="min-w-full border-collapse border border-black">
                        <tbody>
                            <tr>
                                <td class="border border-black px-4 py-2">New Lock Setting</td>
                            </tr>
                            <tr>
                                <td class="border border-black px-4 py-2">
                                    Lock Range:
                                    <input type="date" class="date-input" placeholder="Start Date"> to
                                    <input type="date" class="date-input" placeholder="End Date">
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-black px-4 py-2">Operation:</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="form-actions mt-8 flex justify-center">
                    <a href="" class="px-6 py-2 bg-blue-900 text-white rounded-md mr-4">SAVE</a>
                </div>

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
        margin: 20px 0 15px 0;
        /* Adjusted for top and bottom margins */
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
        /* Set to full width of the column */
        max-width: 400px;
        /* Max width for inputs */
        padding: 10px;
        font-size: 14px;
        border: 1px solid #000000;
        border-radius: 4px;
        margin-top: 5px;

    }

    .info-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto;
        padding: 20px;
        background-color: #ffffff;
        border: 1px solid #080808;
        width: 100%;
        font-weight: bold;
    }

    .date-input {
        border: 1px solid #000000;
        border-radius: 4px;
        padding: 5px;
        font-size: 1em;
        margin: 0 5px;
        width: auto;
    }
</style>
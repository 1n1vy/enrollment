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
    <title>Faculty</title>
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
                <li><a href="#">Faculty</a></li>
                <li class="active">View/Print Plotted Load</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>FACULTY PAGE - VIEW/PRINT PLOTTED LOAD</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form>
                <!-- Colleges -->
                <div class="mb-4">
                    <label for="program-offered" class="block font-bold text-sm mb-2">COLLEGES</label>
                    <select id="program-offered" class="w-full p-2 border border-gray-300 rounded">
                        <option>Select College</option>
                        <option>College A</option>
                        <option>College B</option>
                    </select>
                </div>

                <!-- Department/Offices -->
                <div class="mb-4">
                    <label for="program-offered" class="block font-bold text-sm mb-2">DEPARTMENT/OFFICES</label>
                    <select id="program-offered" class="w-full p-2 border border-gray-300 rounded">
                        <option>Choose</option>
                        <option>Option A</option>
                        <option>Option B</option>
                    </select>
                </div>

                <!-- Gender -->
                <div class="mb-4">
                    <label for="program-offered" class="block font-bold text-sm mb-2">GENDER</label>
                    <select id="program-offered" class="w-full p-2 border border-gray-300 rounded">
                        <option>Select Gender</option>
                        <option>Female</option>
                        <option>Male</option>
                    </select>
                </div>

                <!-- Employment Tensure -->
                <div class="mb-4">
                    <label for="program-offered" class="block font-bold text-sm mb-2">EMPLOYMENT TENSURE</label>
                    <select id="program-offered" class="w-full p-2 border border-gray-300 rounded">
                        <option>Choose</option>
                        <option>Option A</option>
                        <option>Option B</option>
                    </select>
                </div>

                <!-- School Offering Year (Two Inputs for Range) and Term -->
                <div class="mb-4 flex items-center space-x-2">
                    <div class="flex-1">
                        <label for="school-year-from" class="block font-bold text-sm mb-2">SCHOOL YEAR</label>
                        <div class="flex items-center">
                            <input type="text" id="school-year-from" placeholder="Enter" class="w-full p-2 border border-gray-300 rounded">
                            <span class="mx-2 font-bold">TO</span>
                            <input type="text" id="school-year-to" placeholder="Enter" class="w-full p-2 border border-gray-300 rounded">
                        </div>
                    </div>
                    <div class="flex-1">
                        <label for="term" class="block font-bold text-sm mb-2">TERM</label>
                        <select id="term" class="w-full p-2 border border-gray-300 rounded">
                            <option>Select Term</option>
                            <option>Term 1</option>
                            <option>Term 2</option>
                        </select>
                    </div>
                </div>

                <!-- Refresh Button -->
                <div class="flex justify-end">
                    <a href="FactPlot.html" type="submit" style="background-color: #174069;"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Refresh
                    </a>
                </div>
            </form>
        </div>

        <div class="form-container">
            <form>
                <table>
                    <tr>
                        <th>COLLEGE :: DEPT</th>
                        <th>EMPLOYEE ID</th>
                        <th>EMPLOYEE NAME</th>
                        <th>GENDER</th>
                        <th>EMP. STATUS</th>
                        <th>MAX LOAD</th>
                        <th>TOTAL UNITS LOAD</th>
                        <th>VIEW DETAIL</th>
                    </tr>
                    <tr>
                        <td>CEA :: Engineering Sciences</td>
                        <td>003433</td>
                        <td>ABANTO, JEFFREY ALLOY Q.</td>
                        <td>M</td>
                        <td>Honorary</td>
                        <td>36.0</td>
                        <td>31.0</td>
                        <td><a href="#" class="view-link"><img src="../images/view.png" alt="View"></a></td>
                    </tr>
                    <tr>
                        <td>CEA</td>
                        <td>18338-166</td>
                        <td>ABELLA, ARNELI A.</td>
                        <td>F</td>
                        <td>Part-time</td>
                        <td>18.0</td>
                        <td>3.0</td>
                        <td><a href="#" class="view-link"><img src="../images/view.png" alt="View"></a></td>
                    </tr>
                    <tr>
                        <td>SOIR</td>
                        <td>22689-069</td>
                        <td>ABELLO, MARK ALBERT F.</td>
                        <td>M</td>
                        <td>Honorary</td>
                        <td>18.0</td>
                        <td>3.0</td>
                        <td><a href="#" class="view-link"><img src="../images/view.png" alt="View"></a></td>
                    </tr>
                    <tr>
                        <td>COA</td>
                        <td>18329-184</td>
                        <td>ABUYUAN, KATHRYN O.</td>
                        <td>F</td>
                        <td>Part-time</td>
                        <td>18.0</td>
                        <td>12.0</td>
                        <td><a href="#" class="view-link"><img src="../images/view.png" alt="View"></a></td>
                    </tr>
                    <tr>
                        <td>CDC</td>
                        <td>003378</td>
                        <td>AGUILAR, MONALICE G.</td>
                        <td>F</td>
                        <td>Part-time</td>
                        <td>21.0</td>
                        <td>9.0</td>
                        <td><a href="#" class="view-link"><img src="../images/view.png" alt="View"></a></td>
                    </tr>
                    <tr>
                        <td>CED :: BSE Mathematics</td>
                        <td>001414</td>
                        <td>AGUIAR, MARIA HEIZEL S.</td>
                        <td>F</td>
                        <td>Regular Full-Time</td>
                        <td>55.0</td>
                        <td>36.0</td>
                        <td><a href="#" class="view-link"><img src="../images/view.png" alt="View"></a></td>
                    </tr>
                </table>
            </form>
        </div>

        <div class="border-b-4 border-black my-4"></div>

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

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    th,
    td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: center;
    }

    th {
        background-color: #007bff;
        /* Blue color */
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .view-link img {
        width: 20px;
        cursor: pointer;
    }
</style>
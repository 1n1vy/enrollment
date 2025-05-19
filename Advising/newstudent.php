<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../includes/auth.php';
redirectIfNotLoggedIn();

// Optionally, restrict access by role
checkRole(['Admin', 'Faculty']);

if (file_exists('../includes/db_connection.php')) {
    require_once '../includes/db_connection.php';
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
    <title>Search Temporary Student</title>
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
                <li><a href="#">Advising</a></li>
                <li class="active">Advise Student</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>Search Temporary Subject</h1>
        </section>

        <!-- Form Section -->
        <div class="container mx-auto mt-8 bg-white p-8 rounded-lg shadow-lg max-w-7xl">
            <form class="space-y-6">
                <div class="grid grid-cols-6 gap-4">
                    <!-- Row 1 -->
                    <div class="col-span-2">
                        <label for="student-id" class="font-semibold">Student ID</label>
                        <input type="text" id="student-id" class="border w-full p-2 rounded" placeholder="">
                    </div>
                    <div class="col-span-2">
                        <label for="last-name" class="font-semibold">Last Name</label>
                        <input type="text" id="last-name" class="border w-full p-2 rounded">
                    </div>
                    <div class="col-span-2">
                        <label for="first-name" class="font-semibold">First Name</label>
                        <input type="text" id="first-name" class="border w-full p-2 rounded">
                    </div>

                    <!-- Row 2 -->
                    <div class="col-span-2">
                        <label for="gender" class="font-semibold">Gender</label>
                        <select id="gender" class="border w-full p-2 rounded">
                            <option value="N/A">N/A</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label for="college" class="font-semibold">College</label>
                        <select id="college" class="border w-full p-2 rounded">
                            <option value="N/A">N/A</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label for="course" class="font-semibold">Course</label>
                        <select id="course" class="border w-full p-2 rounded">
                            <option value="N/A">N/A</option>
                        </select>
                    </div>

                    <!-- Row 3 -->
                    <div class="col-span-2">
                        <label for="major" class="font-semibold">Major</label>
                        <select id="major" class="border w-full p-2 rounded">
                            <option value="N/A">N/A</option>
                        </select>
                    </div>
                    <div class="col-span-4 flex items-center space-x-4">
                        <div>
                            <label for="sy-sem" class="font-semibold">SY-SEM</label>
                            <input type="text" id="sy-sem" class="border p-2 w-24 rounded" placeholder="2023">
                        </div>
                        <span class="font-semibold">To</span>
                        <div>
                            <input type="text" id="sy-sem-to" class="border p-2 w-24 rounded" placeholder="2024">
                        </div>
                        <div>
                            <select class="border p-2 rounded w-full">
                                <option value="2nd">2nd Sem</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 4 -->
                    <div class="col-span-2">
                        <label for="year-level" class="font-semibold">YEAR LEVEL</label>
                        <select id="year-level" class="border w-full p-2 rounded">
                            <option value="N/A">N/A</option>
                        </select>
                    </div>
                </div>

                <!-- Checkboxes -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
                    <div>
                        <input type="checkbox" id="enrolled-students" class="mr-2">
                        <label for="enrolled-students" class="font-semibold">Show Enrolled Students</label>
                    </div>
                    <div>
                        <input type="checkbox" id="advised-students" class="mr-2">
                        <label for="advised-students" class="font-semibold">Show Advised Students</label>
                    </div>
                    <div>
                        <input type="checkbox" id="downpayment" class="mr-2">
                        <label for="downpayment" class="font-semibold">Show Students with Downpayment</label>
                    </div>
                    <div>
                        <input type="checkbox" id="reg-fee" class="mr-2">
                        <label for="reg-fee" class="font-semibold">Show Student with Registration Fee</label>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-between mt-8">
                    <a href="Student.php" style="background-color: #174069;" class="bg-blue-800 text-white py-3 px-6 rounded-full">
                        Search Current Students
                    </a>
                    </a>
                    <a href="OldStudent.php" style="background-color: #174069;" class="bg-blue-800 text-white py-3 px-6 rounded-full">
                        Search Old Students
                    </a>
                </div>
            </form>
            <div class="border-b-4 border-black my-4 -mx-8"></div>
            <div class="flex items-center space-x-2">
                <label for="term" class="text-gray-700 text-sm font-bold mb-1">Sort by:</label>
                <select id="term" name="term" class="p-2 border border-gray-300 rounded">
                    <option value="2nd-sem">N/A</option>
                </select>
                <select id="term" name="term" class="p-2 border border-gray-300 rounded">
                    <option value="2nd-sem">Ascending</option>
                    <option value="2nd-sem">Descending</option>
                </select>
            </div>
            <div class="flex justify-center">
                <button id="proceed-btn" style="background-color: #174069;" class="bg-blue-800 text-white py-3 px-6 rounded-full hover:bg-blue-900">
                    Proceed
                </button>
            </div>
            <!-- Table section (hidden by default) -->
            <div id="schedule-table" class="mt-6 hidden">

                <div class="flex justify-between items-center">
                    <p class="text-gray-700 font-semibold mb-2">Total Students: 100 - Showing (1 - 3)</p>

                    <div class="flex items-center">
                        <label for="pages" class="font-semibold mr-2">Jump to page:</label>
                        <!-- Dropdown with select -->
                        <select class="text-gray-700 font-semibold py-1 px-4 rounded border border-black">
                            <option value="action1">1 of 3</option>
                            <option value="action2">2 of 3</option>
                            <option value="action3">3 of 3</option>
                        </select>
                    </div>
                </div>

                <div style="background-color: #174069;" class="text-white p-3 text-center font-bold text-xl rounded-t-md">
                    Search Result
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300">
                        <thead class="text-gray-700">
                            <tr>
                                <th class="py-2 px-4 border" style="width: 200px;">STUDENT ID</th>
                                <th class="py-2 px-4 border">START DATE OF ENROLLMENT</th>
                                <th class="py-2 px-4 border" style="width: 300px;">LNAME, FNAME, MI</th>
                                <th class="py-2 px-4 border">GENDER</th>
                                <th class="py-2 px-4 border">COURSE/MAJOR</th>
                                <th class="py-2 px-4 border">YEAR LEVEL</th>
                                <th class="py-2 px-4 border">APPL CATG</th>
                                <th class="py-2 px-4 border">ADVISED</th>
                                <th class="py-2 px-4 border">NO. OF PMT</th>
                                <th class="py-2 px-4 border">VIEW ADVISED SUBJ</th>
                                <th class="py-2 px-4 border">REMOVE ADVISING</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-gray-700">
                                <td class="py-2 px-4 border text-center">17-1230-231</td>
                                <td class="py-2 px-4 border text-center">2024-02-21</td>
                                <td class="py-2 px-4 border text-center">SIMBULAN, ARJAY ,L</td>
                                <td class="py-2 px-4 border text-center">M</td>
                                <td class="py-2 px-4 border text-center">BSIT</td>
                                <td class="py-2 px-4 border text-center">4</td>
                                <td class="py-2 px-4 border text-center">OLD</td>
                                <td class="py-2 px-4 border text-center"><i class="bi bi-check2"></i></td>
                                <td class="py-2 px-4 border text-center">0</td>
                                <td class="py-2 px-4 border text-center">
                                    <a href="#" onclick="openPopup()">
                                        <i class="bi bi-search"></i> <!-- Added cursor pointer -->
                                    </a>
                                <td class="py-2 px-4 border text-center"><i class="bi bi-trash-fill"></i></td>
                            </tr>
                            <tr class="text-gray-700">
                                <td class="py-2 px-4 border text-center">17-1230-231</td>
                                <td class="py-2 px-4 border text-center">2024-02-21</td>
                                <td class="py-2 px-4 border text-center">SIMBULAN, ARJAY ,L</td>
                                <td class="py-2 px-4 border text-center">M</td>
                                <td class="py-2 px-4 border text-center">BSIT</td>
                                <td class="py-2 px-4 border text-center">4</td>
                                <td class="py-2 px-4 border text-center">OLD</td>
                                <td class="py-2 px-4 border text-center"><i class="bi bi-check2"></i></td>
                                <td class="py-2 px-4 border text-center">0</td>
                                <td class="py-2 px-4 border text-center">
                                    <a href="#" onclick="openPopup()">
                                        <i class="bi bi-search"></i> <!-- Added cursor pointer -->
                                    </a>
                                <td class="py-2 px-4 border text-center"><i class="bi bi-trash-fill"></i></td>
                            </tr>
                            <tr class="text-gray-700">
                                <td class="py-2 px-4 border text-center">17-1230-231</td>
                                <td class="py-2 px-4 border text-center">2024-02-21</td>
                                <td class="py-2 px-4 border text-center">SIMBULAN, ARJAY ,L</td>
                                <td class="py-2 px-4 border text-center">M</td>
                                <td class="py-2 px-4 border text-center">BSIT</td>
                                <td class="py-2 px-4 border text-center">4</td>
                                <td class="py-2 px-4 border text-center">OLD</td>
                                <td class="py-2 px-4 border text-center"><i class="bi bi-check2"></i></td>
                                <td class="py-2 px-4 border text-center">0</td>
                                <td class="py-2 px-4 border text-center">
                                    <a href="#" onclick="openPopup()">
                                        <i class="bi bi-search"></i> <!-- Added cursor pointer -->
                                    </a>
                                <td class="py-2 px-4 border text-center"><i class="bi bi-trash-fill"></i></td>
                            </tr>
                            <tr class="text-gray-700">
                                <td class="py-2 px-4 border text-center">17-1230-231</td>
                                <td class="py-2 px-4 border text-center">2024-02-21</td>
                                <td class="py-2 px-4 border text-center">SIMBULAN, ARJAY ,L</td>
                                <td class="py-2 px-4 border text-center">M</td>
                                <td class="py-2 px-4 border text-center">BSIT</td>
                                <td class="py-2 px-4 border text-center">4</td>
                                <td class="py-2 px-4 border text-center">OLD</td>
                                <td class="py-2 px-4 border text-center"></i></td>
                                <td class="py-2 px-4 border text-center">0</td>
                                <td class="py-2 px-4 border text-center">
                                    <a href="#" onclick="openPopup()">
                                        <i class="bi bi-search"></i> <!-- Added cursor pointer -->
                                    </a>
                                <td class="py-2 px-4 border text-center"><i class="bi bi-trash-fill"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="border-b-4 border-black my-4"></div>

    <script>
        // Load navbar dynamically
        (function loadNavbar() {
            fetch('../Components/Navbar.php')
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
                        script.src = '../Components/app.js';
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

        document.getElementById('proceed-btn').addEventListener('click', function() {
            document.getElementById('schedule-table').classList.remove('hidden');
        });

        function openPopup() {
            // URL of the page you want to open
            const url = 'PopupTwo.php'; // Replace with your popup page URL
            const options = 'width=1200,height=800,resizable=yes,scrollbars=yes'; // Customize the dimensions
            window.open(url, 'PopupWindow', options);
        }
    </script>

</body>

</html>

<!-- CSS styling -->
<style scoped>
    body {
        font-family: Arial, sans-serif;
        background-color: #f7f8fa;
        margin: 0;
        padding: 0;
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

    .form-container {
        max-width: 1500px;
        margin: 20px auto;
        background-color: #f4f8fc;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    @media (max-width: 600px) {
        .form-group {
            flex-direction: column;
            align-items: flex-start;
        }

        .form-group input {
            width: 100%;
        }

        .bottom-btns {
            flex-direction: column;
            align-items: center;
        }

        .bottom-btn-group {
            margin-bottom: 15px;
        }
    }
</style>
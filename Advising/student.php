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

$genders = $conn->query("SELECT DISTINCT gender FROM students");
$student_statuses = $conn->query("SELECT DISTINCT student_status FROM students");
$enrollment_statuses = $conn->query("SELECT DISTINCT enrollment_status FROM students");
$departments = $conn->query("SELECT id, department_name FROM departments");
$courses = $conn->query("SELECT id, course_name FROM courses");
$majors = $conn->query("SELECT id, major_name FROM majors");
$classification_code = $conn->query("SELECT DISTINCT classification_code FROM students");



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Student</title>
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
            <h1>Search Student</h1>
        </section>

        <!-- Form Section -->
        <div class="container mx-auto mt-8 bg-white p-8 rounded-lg shadow-lg max-w-7xl">
            <form class="space-y-6">
                <div class="grid grid-cols-6 gap-4">
                    <!-- Row 1 -->
                    <div class="col-span-2">
                        <label for="student_number" class="font-semibold">Student ID</label>
                        <input type="text" id="student_number" name="student_number" class="border w-full p-2 rounded">
                    </div>
                    <div class="col-span-2">
                        <label for="last_name" class="font-semibold">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="border w-full p-2 rounded">
                    </div>
                    <div class="col-span-2">
                        <label for="first_name" class="font-semibold">First Name</label>
                        <input type="text" id="first_name" name="first_name" class="border w-full p-2 rounded">
                    </div>

                    <!-- Row 2 -->
                    <div class="col-span-2">
                        <label for="gender" class="font-semibold">Gender</label>
                        <select id="gender" name="gender" class="border w-full p-2 rounded">
                            <option value="N/A">N/A</option>
                            <?php while ($row = $genders->fetch_assoc()) { ?>
                                <option value="<?php echo $row['gender']; ?>"><?php echo $row['gender']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label for="department_name" class="font-semibold">Department</label>
                        <select id="department_name" name="department_name" class="border w-full p-2 rounded">
                            <option value="N/A">N/A</option>
                            <?php while ($row = $departments->fetch_assoc()) { ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['department_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label for="course_name" class="font-semibold">Course</label>
                        <select id="course_name" name="course_name" class="border w-full p-2 rounded">
                            <option value="N/A">N/A</option>
                            <?php while ($row = $courses->fetch_assoc()) { ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['course_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- Row 3 -->
                    <div class="col-span-2">
                        <label for="department_name" class="font-semibold">Department</label>
                        <select id="department_name" name="department_name" class="border w-full p-2 rounded">
                            <option value="N/A">N/A</option>
                            <?php while ($row = $departments->fetch_assoc()) { ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['department_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- Row 4 -->
                    <div class="col-span-2">
                        <label for="classification_code" class="font-semibold">Year Level</label>
                        <select id="classification_code" name="classification_code" class="border w-full p-2 rounded">
                            <option value="N/A">N/A</option>
                            <?php while ($row = $classification_code->fetch_assoc()) { ?>
                                <option value="<?php echo $row['classification_code']; ?>"><?php echo $row['classification_code']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

            </form>
            <div class="border-b-4 border-black my-4 -mx-8"></div>
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
                                <th class="py-2 px-4 border">DEPARTMENT</th>
                                <th class="py-2 px-4 border">COURSE</th>                                
                                <th class="py-2 px-4 border">YEAR LEVEL</th>
                                <th class="py-2 px-4 border">APPL CATG</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-gray-700">
                                <td class="py-2 px-4 border text-center"></td>
                                <td class="py-2 px-4 border text-center"></td>
                                <td class="py-2 px-4 border text-center"></td>
                                <td class="py-2 px-4 border text-center"></td>
                                <td class="py-2 px-4 border text-center"></td>
                                <td class="py-2 px-4 border text-center"></td>
                                <td class="py-2 px-4 border text-center"></td>
                                <td class="py-2 px-4 border text-center"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="border-b-4 border-black my-4"></div>

    <script>
    let allStudents = [];

    // Load all students when the page loads
    window.onload = () => {
        fetch('fetch_students.php')
            .then(res => res.json())
            .then(data => {
                allStudents = data;
            });
    };

    document.getElementById('proceed-btn').addEventListener('click', function () {
        const studentNumber = document.getElementById('student_number').value.toLowerCase();
        const lastName = document.getElementById('last_name').value.toLowerCase();
        const firstName = document.getElementById('first_name').value.toLowerCase();
        const gender = document.getElementById('gender').value;
        const department = document.getElementById('department_name').value;
        const course = document.getElementById('course_name').value;
        const classification = document.getElementById('classification_code').value;

        const filtered = allStudents.filter(student => {
            return (!studentNumber || student.student_number.toLowerCase().includes(studentNumber)) &&
                   (!lastName || student.last_name.toLowerCase().includes(lastName)) &&
                   (!firstName || student.first_name.toLowerCase().includes(firstName)) &&
                   (gender === "N/A" || student.gender === gender) &&
                   (department === "N/A" || student.departments_id == department) &&
                    (course === "N/A" || student.course_id == course) &&
                   (classification === "N/A" || student.classification_code === classification);
        });

        const tbody = document.querySelector('#schedule-table tbody');
        tbody.innerHTML = '';

        filtered.forEach(student => {
            const tr = document.createElement('tr');
            tr.className = "text-gray-700";
            tr.innerHTML = `
                <td class="py-2 px-4 border text-center">${student.student_number}</td>
                <td class="py-2 px-4 border text-center">${student.created_at}</td>
                <td class="py-2 px-4 border text-center">${student.last_name.toUpperCase()}, ${student.first_name.toUpperCase()} ${student.middle_name ? ', ' + student.middle_name.charAt(0).toUpperCase() : ''}</td>
                <td class="py-2 px-4 border text-center">${student.gender}</td>
                <td class="py-2 px-4 border text-center">${student.department_name}</td>
                <td class="py-2 px-4 border text-center">${student.course_name}</td>
                <td class="py-2 px-4 border text-center">${student.classification_code}</td>
                <td class="py-2 px-4 border text-center">${student.student_status}</td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('schedule-table').classList.remove('hidden');
    });
</script>


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
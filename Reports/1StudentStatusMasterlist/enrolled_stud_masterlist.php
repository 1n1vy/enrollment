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
<?php
$studentStatus = $_POST['studStatus'] ?? '';
$department = $_POST['progDept'] ?? '';
$course = $_POST['progCourses'] ?? '';
$yearstart = $_POST['yearstart'] ?? '';
$yearend = $_POST['yearend'] ?? '';
$term = $_POST['term'] ?? '';

$deptQuery = "SELECT department_name FROM departments WHERE id = ?";
$stmt = $conn->prepare($deptQuery);
$stmt->bind_param("i", $department);
$stmt->execute();
$deptResult = $stmt->get_result();
$deptName = $deptResult->fetch_assoc()['department_name'] ?? '';

$courseQuery = "SELECT course_name FROM courses WHERE id = ?";
$stmt = $conn->prepare($courseQuery);
$stmt->bind_param("i", $course);
$stmt->execute();
$courseResult = $stmt->get_result();
$courseName = $courseResult->fetch_assoc()['course_name'] ?? '';

$sql = "
    SELECT 
        s.student_number, 
        s.last_name, 
        s.first_name, 
        s.middle_name, 
        s.suffix, 
        p.section, 
        sub.subject_name
    FROM students s
    LEFT JOIN student_course sc ON sc.student_id = s.id
    LEFT JOIN programs p ON p.program_id = sc.program_id
    LEFT JOIN subjects sub ON p.subject_id = sub.id
    WHERE s.enrollment_status = ? AND s.departments_id = ? AND s.course_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $studentStatus, $department, $course);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolled Student Masterlist</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
                <li><a href="#">Reports</a></li>
                <li><a href="#">Student Status Masterlist</a></li>
                <li class="active">Enrolled Student Masterlist</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>REPORTS - ENROLLED STUDENT MASTERLIST</h1>
        </section>

        <!-- Form container -->
        <div class="form-container">
            <form action="#">
                <!-- Student Status -->
                <div class="form-group">
                    <label for="student-status" class="block font-bold">STUDENT STATUS</label>
                    <input type="text" id="student-status" placeholder="Enrolled" class="border p-2 rounded-md w-full sm:w-64" value="<?php echo strtoupper(htmlspecialchars($studentStatus)); ?>" readonly>
                </div>

                <!-- SY Term, To, and Semester Row -->
                <div class="flex flex-wrap gap-4 mt-4">
                    <div class="w-full sm:w-auto mb-4">
                        <label for="sy-from" class="block font-bold">SY Term</label>
                        <input type="text" id="sy-from" placeholder="2024" class="border p-2 rounded-md w-full sm:w-24" value="<?php echo htmlspecialchars($yearstart); ?>" readonly>
                    </div>
                    <div class="w-full sm:w-auto mb-4">
                        <label for="sy-to" class="block font-bold">To</label>
                        <input type="text" id="sy-to" placeholder="2025" class="border p-2 rounded-md w-full sm:w-24" value="<?php echo htmlspecialchars($yearend); ?>" readonly>
                    </div>
                    <div class="w-full sm:w-auto mb-4">
                        <label for="semester" class="block font-bold">Semester</label>
                        <input type="text" id="semester" placeholder="1st Semester" class="border p-2 rounded-md w-full sm:w-32" value="<?php echo htmlspecialchars($term)." Semester"; ?>" readonly>
                    </div>
                </div>

                <!-- Show By Section -->
                <h4 class="font-bold mb-4" style="margin-top: 25px; margin-bottom: 15px; margin-left: -15px;">SHOW BY:</h4>
                <div class="form-show flex flex-col gap-4">
                    <div>
                        <label for="college" class="block font-bold">College</label>
                        <input type="text" id="college" placeholder="College of Informatics and Computing Studies" class="border p-2 rounded-md w-full" value="<?php echo htmlspecialchars($deptName); ?>" readonly>
                    </div>
                    <div>
                        <label for="course" class="block font-bold">Program</label>
                        <input type="text" id="course" placeholder="Bachelor of Science in Information Technology" class="border p-2 rounded-md w-full" value="<?php echo htmlspecialchars($courseName); ?>" readonly>
                    </div>
                </div>

                <!-- Buttons: Print and Back -->
                <!-- <div class="flex justify-between mt-6">
                    <button type="button" class="bg-blue-900 text-white rounded-md p-2">Print</button>
                    <a href="student_stats_masterlist.php" type="button" class="bg-blue-900 text-white rounded-md p-2">Back</a>
                </div> -->

                <!-- Responsive Table Section -->
                <!-- Entries Selection -->

                <div class="form-actions mt-8 flex justify-evenly mb-5">
                <a href="#" id="btnPrint2" title="Print" class="bg-blue-900 text-white rounded-md p-2">
                    <i class="bi bi-printer mr-2 text-lg"></i> Print
                </a>
                <a href="student_stats_masterlist.php" type="button" class="bg-blue-900 text-white rounded-md p-2">Back</a>
            </div>
                <!-- Responsive Table Section -->
                <div class="table-container mt-8 overflow-x-auto">
                    <table class="min-w-full border border-collapse">
                        <thead class="bg-gray-200">
                            <tr class="text-left">
                                <th class="p-2 border">Student Number</th>
                                <th class="p-2 border">Last Name</th>
                                <th class="p-2 border">First Name</th>
                                <th class="p-2 border">Middle Name</th>
                                <th class="p-2 border">Suffix</th>
                                <th class="p-2 border">Subject</th>
                                <th class="p-2 border">Section</th>
                            </tr>
                        </thead>
                        <tbody id="student-table-body">
                            <?php
                                if (!empty($students)) {
                                    foreach ($students as $student) {
                                        echo "<tr>";
                                        echo "<td class='p-2 border'>{$student['student_number']}</td>";
                                        echo "<td class='p-2 border'>{$student['last_name']}</td>";
                                        echo "<td class='p-2 border'>{$student['first_name']}</td>";
                                        echo "<td class='p-2 border'>" . ($student['middle_name'] ?: '') . "</td>";
                                        echo "<td class='p-2 border'>" . ($student['suffix'] ?: '') . "</td>";
                                        echo "<td class='p-2 border'>" . ($student['subject_name'] ?: '') . "</td>";
                                        echo "<td class='p-2 border'>" . ($student['section'] ?: '') . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center p-2'>No students found.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>

    <script>

$(document).on("click", "#btnPrint2", function (e) {
    e.preventDefault();

    let content = $("#student-table-body").html();

    let theadContent = $("#student-table-body").closest('table').find('thead').html();

    let printWindow = window.open("", "", "width=1200,height=800");

    printWindow.document.write(`
        <html>
        <head>
            <title>Enrolled Student Masterlist</title>
            <style>
                @media print {
                    @page {
                        size: landscape;
                        margin: 1cm;
                    }
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        padding: 8px;
                        border: 1px solid #ccc;
                        text-align: center;
                    }
                    th {
                        background-color: #174069;
                        color: white;
                    }
                    .bg-gray-200 {
                        background-color: #f2f2f2;
                    }
                }
            </style>
        </head>
        <body>
            <h5>Student List</h5>
            <table class="min-w-full border border-gray-300 w-100">
                <thead>
                    ${theadContent} <!-- Correctly include the thead here -->
                </thead>
                <tbody>
                    ${content} <!-- Table body content here -->
                </tbody>
            </table>
        </body>
        </html>
    `);

    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
});

        const entriesSelect = document.getElementById('entries');
        const tableBody = document.getElementById('student-table-body');

        const originalRows = Array.from(tableBody.rows); 

        function updateTable() {
            const selectedValue = parseInt(entriesSelect.value);
            tableBody.innerHTML = '';

            for (let i = 0; i < selectedValue && i < originalRows.length; i++) {
                tableBody.appendChild(originalRows[i]);
            }
        }

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

        function showAdditionalFields() {
            // Display the hidden information section
            document.getElementById("additional-fields-status").style.display = "block";
        }
    </script>
</body>

</html>

<!-- CSS Styling -->
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
        width: 50%;
        margin: 100px auto;
        background-color: #f4f8fc;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    /* Table styles */
    .table-container {
        width: 100%;
        overflow-x: auto;
    }

    table {
        width: 100%;
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
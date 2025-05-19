<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../includes/db_connection.php';
// session_start();
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
<?php

$student_id = $_GET['student'];

$query = "
    SELECT 
        students.*, 
        d.department_name,
        c.course_name,
        SUM(COALESCE(s.units, 0)) AS total_units
    FROM students
    LEFT JOIN departments d ON students.departments_id = d.id
    LEFT JOIN courses c ON students.course_id = c.id
    LEFT JOIN student_course sc ON students.id = sc.student_id
    LEFT JOIN programs p ON sc.program_id = p.program_id 
    LEFT JOIN subjects s ON p.subject_id = s.id
    WHERE students.student_number = ?
    GROUP BY students.id
";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param('s', $student_id);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $studentData = $result->fetch_assoc();
        echo "Student ID: " . $studentData['student_number'] . "<br>";
        echo "Course: " . $studentData['course_id'] . "<br>";
        echo "Department: " . $studentData['department_name'] . "<br>";
    } else {
        echo "Student not found.";
    }

    $stmt->close();
} else {
    echo "Error preparing query.";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advising Student</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
            <h1>ADVISING STUDENT</h1>
        </section>

        <div class="form-container mt-4">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="student-status" class="form-label">Student Status:</label>
                    <p class="form-control-plaintext text-primary" id="student-status"><?php echo $studentData['status'] ?></p>
                </div>
                <div class="col-md-4">
                    <label for="student-id" class="form-label">Student ID:</label>
                    <p class="form-control-plaintext text-primary" id="student-id"><?php echo $_GET['student'] ?></p>
                </div>
                <div class="col-md-4">
                    <label for="student-name" class="form-label">Student Name:</label>
                    <p class="form-control-plaintext text-primary" id="student-name"><?php echo $studentData['first_name']." ".$studentData['middle_name']." ".$studentData['last_name'] ?></p>
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">College:</label>
                    <p class="form-control-plaintext text-primary"><?php echo $studentData['department_name'] ?></p>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Course:</label>
                    <p class="form-control-plaintext text-primary"><?php echo $studentData['course_name'] ?></p>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Year level:</label>
                    <p class="form-control-plaintext text-primary"><?php echo $studentData['year_level'] ?></p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="year-level" class="form-label">Student Type:</label>
                    <p class="form-control-plaintext text-primary" id="year-level"><?php echo strtoupper($studentData['student_status']) ?></p>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cur. Year:</label>
                    <p class="form-control-plaintext text-primary">2018 - 2019</p>
                </div>
                <div class="col-md-4">
                    <label class="form-label">School Year Term:</label>
                    <p class="form-control-plaintext text-primary"><?php echo $_GET['yearstart'].'-'.$_GET['yearend'].' ,'.$_GET['term'].' Semester' ?></p>
                </div>
            </div>

            <div class="mb-4">
                <h5 class="text-primary border-bottom pb-2">SUBJECT LIST ALLOWED TO ADD</h5>
            </div>
            <div class="row mb-3 align-items-center">
                <div class="col-md-4">
                    <label for="max-units" class="form-label">Maximum units the Student can take:</label>
                    <p class="form-control-plaintext fw-bold" id="max-units">
                        <?php echo ($studentData['year_level'] === '1st') ? '23.00' : '18.00'; ?>
                    </p>
                </div>
                <div class="col-md-4">
                    <label for="student-load" class="form-label">Total Student Load:</label>
                    <p class="form-control-plaintext fw-bold text-primary" id="student-load"><?php echo $studentData['total_units'].'.00' ?></p>
                </div>
            </div>
        </div>
        <div class="form-container w-100">
        <div class="table-responsive">
        <form method="POST">
            <table class="table table-bordered">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center">YEAR</th>
                        <th class="text-center">TERM</th>
                        <th class="text-center" style="width: 350px;">SUBJECT CODE</th>
                        <th class="text-center" style="width: 800px;">SUBJECT TITLE</th>
                        <th class="text-center" style="width: 200px;">UNITS</th>
                        <th class="text-center" style="width: 200px;">SECTION</th>
                        <th class="text-center" style="width: 200px;">ROOM</th>
                        <th class="text-center" style="width: 200px;">SCHEDULE</th>
                        <th class="text-center">SELECT</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $yearLevel = $studentData['year_level'];
                    $course_id = $studentData['course_id'];
                    $term = isset($_GET['term']) ? $_GET['term'] : '';

                    $query = "
                        SELECT p.*, s.subject_code, s.subject_name, s.units, sc.student_id, r.*
                        FROM programs p
                        JOIN subjects s ON p.subject_id = s.id
                        LEFT JOIN student_course sc ON p.program_id = sc.program_id
                        LEFT JOIN rooms r ON p.room_id = r.id
                        WHERE p.year_level = ? AND p.course_id = ?
                        AND p.term = ?
                    ";

                    if ($stmt = $conn->prepare($query)) {
                        $stmt->bind_param('iis', $yearLevel, $course_id, $term);

                        $stmt->execute();

                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $day = $row['schedule_day'];
                                $time = $row['schedule_time'];
                                echo '<tr>';
                                echo '<td class="text-center">' . $row['year_level'] .'</td>';
                                echo '<td class="text-center">' . $row['term'] . '</td>';
                                echo '<td class="text-center">' . $row['subject_code'] . '</td>';
                                echo '<td class="text-center">' . $row['subject_name'] . '</td>';
                                echo '<td class="text-center">' . $row['units'] . '.00</td>';
                                echo '<td class="text-center">' . $row['section'] . '</td>';
                                echo '<td class="text-center">' . $row['building_code']. '- '.$row['room_number'] . '</td>';
                                echo '<td class="text-center">' . $row['schedule_day'].'~'. $row['schedule_time'] . '</td>';
                                // echo '<td class="text-center"><input type="checkbox" name="select_subject[]" value="' . $row['program_id'] . '" onclick="updateStudentLoad(this, ' . $row['units'] . ')"></td>';
                                if ($row['student_id'] == $studentData['id']) {
                                    echo '<td class="text-center enrolled" data-day="'.$day.'" data-time="'.$time.'">ENROLLED</td>';
                                } else {
                                    // echo '<td class="text-center"><input type="checkbox" name="select_subject[]" value="' . $row['program_id'] . '" data-day="'.$day.'" data-time="'.$time.'" onclick="checkScheduleConflict(this, ' . $row['units'] . ')"></td>';
                                    echo '<td class="text-center"><input type="checkbox" name="select_subject[]" value="' . $row['program_id'] . '" data-day="' . $day . '" data-time="' . $time . '" onclick="checkScheduleConflict(this, ' . $row['units'] . ')"></td>';
                                }
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="9" class="text-center">No programs available</td></tr>';
                        }

                        $stmt->close();
                    } else {
                        echo "Error preparing query.";
                    }

                    $conn->close();
                ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-end mt-3">
                <button class="btn btn-primary btn-lg p-2" onclick="saveSelectedSubjects()">Save</button>
            </div>
        </form>
        </div>

        </div>
    </div>
    <div class="border-b-4 border-black my-4"></div>
    <script>
        
function checkScheduleConflict(checkbox, units) {
    const selectedDay = checkbox.getAttribute('data-day');
    const selectedTime = checkbox.getAttribute('data-time');
    const selectedSubjectCode = checkbox.closest('tr').querySelector('td:nth-child(3)').innerText.trim();
    const selectedSubjectName = checkbox.closest('tr').querySelector('td:nth-child(4)').innerText.trim();

    const enrolledSubjects = document.querySelectorAll('.enrolled');
    const selectedCheckboxes = document.querySelectorAll('input[name="select_subject[]"]:checked');

    for (let i = 0; i < enrolledSubjects.length; i++) {
        const enrolledRow = enrolledSubjects[i].closest('tr');
        const enrolledDay = enrolledSubjects[i].getAttribute('data-day');
        const enrolledTime = enrolledSubjects[i].getAttribute('data-time');
        const enrolledSubjectCode = enrolledRow.querySelector('td:nth-child(3)').innerText.trim();
        const enrolledSubjectName = enrolledRow.querySelector('td:nth-child(4)').innerText.trim();

        if (
            selectedDay === enrolledDay && selectedTime === enrolledTime ||
            selectedSubjectCode === enrolledSubjectCode ||
            selectedSubjectName === enrolledSubjectName
        ) {
            alert('Conflict detected with an enrolled subject!');
            checkbox.checked = false;
            return;
        }
    }

    for (let i = 0; i < selectedCheckboxes.length; i++) {
        const selectedRow = selectedCheckboxes[i].closest('tr');
        const selectedCode = selectedRow.querySelector('td:nth-child(3)').innerText.trim();
        const selectedName = selectedRow.querySelector('td:nth-child(4)').innerText.trim();
        const selectedDayConflict = selectedCheckboxes[i].getAttribute('data-day');
        const selectedTimeConflict = selectedCheckboxes[i].getAttribute('data-time');

        if (
            checkbox !== selectedCheckboxes[i] &&
            (selectedDay === selectedDayConflict && selectedTime === selectedTimeConflict ||
            selectedSubjectCode === selectedCode ||
            selectedSubjectName === selectedName)
        ) {
            alert('Conflict with another selected subject!');
            checkbox.checked = false;
            return;
        }
    }

    updateStudentLoad(checkbox, units);
}
</script>
    <script>
    let totalLoad = <?php echo $studentData['total_units'] ?>;
        function updateStudentLoad(checkbox, units) {
        const maxUnits = document.getElementById('max-units').innerText === '23.00' ? 23.00 : 18.00;

        if (checkbox.checked && totalLoad + parseFloat(units) > maxUnits) {
            checkbox.checked = false;
            alert(`You can't exceed ${maxUnits.toFixed(2)} units.`);
            return;
        }

        if (checkbox.checked) {
            totalLoad += parseFloat(units);
        } else {
            totalLoad -= parseFloat(units);
        }

        document.getElementById('student-load').innerText = totalLoad.toFixed(2);

        const checkboxes = document.querySelectorAll('input[name="select_subject[]"]');
        checkboxes.forEach(cb => {
            const cbUnits = parseFloat(cb.getAttribute('data-units'));
            if (!cb.checked && totalLoad + cbUnits > maxUnits) {
                cb.disabled = true;
            } else if (!cb.checked) {
                cb.disabled = false;
            }
        });
    }
</script>
    <script>

function saveSelectedSubjects() {
    const checkedBoxes = document.querySelectorAll('input[name="select_subject[]"]:checked');
    const selectedPrograms = Array.from(checkedBoxes).map(cb => cb.value);

    const studentNumber = '<?= $studentData["student_number"] ?>';
    const term = '<?= $_GET["term"] ?? "" ?>';
    const schoolYear = '<?php echo $_GET['yearstart'].'-'.$_GET['yearend'] ?>';

    if (selectedPrograms.length === 0) {
        alert("Please select at least one subject.");
        return;
    }

    const formData = new URLSearchParams();
    formData.append('student_number', studentNumber);
    formData.append('term', term);
    formData.append('school_year', schoolYear);
    selectedPrograms.forEach(id => formData.append('selected_programs[]', id));

    fetch('advisingAPI.php?action=saveSelectedSubjects', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Saved successfully!');
            location.reload(); 
        } else {
            alert('Error: ' + data.message);
        }
    });
}

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

        function openPopup() {
            // URL of the page you want to open
            const url = 'popup.php'; // Replace with your popup page URL
            const options = 'width=1200,height=800,resizable=yes,scrollbars=yes'; // Customize the dimensions
            window.open(url, 'PopupWindow', options);
        }
    </script>
</body>

</html>

<style>
    /* General Body Styles */
    body {
        font-family: 'Poppins', Arial, sans-serif;
        background-color: #f7f8fa;
        margin: 0;
        padding: 0;
    }

    /* Breadcrumb styles */
    .breadcrumb-nav {
        margin: 10px 0;
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
        padding: 15px;
        text-align: center;
        margin-bottom: 15px;
    }

    .section-header h1 {
        color: white;
        margin: 0;
        font-size: 24px;
    }

    /* Form Container */
    .form-container {
        max-width: 1400px;
        margin: 20px auto;
        background-color: #f4f8fc;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    /* Form Row */
    .form-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    /* Double row (for Course and Major) */
    .form-row-double {
        display: flex;
        justify-content: space-between;
    }

    /* Triple row (for Year Level Entry, Cur. Year, and School Year Term) */
    .form-row-triple {
        display: flex;
        justify-content: space-between;
    }

    /* Form Group */
    .form-group {
        display: flex;
        flex-direction: column;
        margin-right: 10px;
        flex: 1;
    }

    .form-group label {
        margin-bottom: 5px;
        font-weight: 600;
    }

    .form-group input,
    .form-group select {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }

    /* Static Text (no bold) */
    .form-group .static-text {
        font-weight: normal;
    }

    /* Special Elements */
    .course-info,
    .cur-year,
    .school-year-term {
        font-size: 16px;
    }

    /* Checkbox Style */
    .form-group input[type="checkbox"] {
        width: 18px;
        height: 18px;
        margin-right: 5px;
    }

    /* Placeholder Content */
    .placeholder-content {
        height: 150px;
        border: 1px solid #ccc;
        background-color: white;
        border-radius: 4px;
        overflow-y: auto;
        padding: 10px;
    }

    /* Adjustments for smaller screens */
    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
        }

        .form-group {
            margin-right: 0;
        }

        .section-header h1 {
            font-size: 20px;
        }
    }
</style>
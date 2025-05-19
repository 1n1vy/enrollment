<?php
require __DIR__ . '/../../../includes/db_connection.php';
header('Content-Type: application/json');
ob_clean();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$action = $_REQUEST['action'] ?? '';

switch ($action) {
    case 'getFacultyInfo':
        getFacultyInfo($conn);
        break;
    case 'getCanTeach':
        getCanTeach($conn);
        break;
    case 'getFacultyLoad':
        getFacultyLoad($conn);
        break;
    case 'getDepartments':
        getDepartments($conn);
        break;
    case 'getCourses':
        getCourses($conn);
        break;
    case 'getProfessorSuggestions':
        getProfessorSuggestions($conn);
        break;
    case 'getSubjects':
        getSubjects($conn);
        break;
    case 'getSubjects2':
        getSubjects2($conn);
        break;
    case 'getSubjectDetails':
        getSubjectDetails($conn);
        break;
    case 'addFacultyLoad':
        addFacultyLoad($conn);
        break;
    case 'getFacultyL':
        getFacultyL($conn);
        break;
    case 'getFacultyLoad2':
        getFacultyLoad2($conn);
        break;
    case 'addSubstitution':
        addSubstitution($conn);
        break;
    case 'getDeptLoad':
        getDeptLoad($conn);
        break;
    case 'getSections':
        getSections($conn);
        break;
    case 'getUnassignedSubjects':
        getUnassignedSubjects($conn);
        break;
    case 'getSubstitutions':
        getSubstitutions($conn);
        break;
    case 'check_availability':
        checkFacultyAvailability($conn);
        break;
    default:
        echo json_encode(['error' => 'Invalid action.']);
        break;
}
function checkFacultyAvailability($conn)
{
    $yearFrom = isset($_POST['year_term_from']) ? $_POST['year_term_from'] : '';
    $yearTo = isset($_POST['year_term_to']) ? $_POST['year_term_to'] : '';
    $term = isset($_POST['term']) ? $_POST['term'] : '';
    $collegeId = isset($_POST['college']) ? intval($_POST['college']) : null;
    $day = isset($_POST['day']) ? $_POST['day'] : '';
    $timeFrom = isset($_POST['class_time_from']) ? $_POST['class_time_from'] : '';
    $timeTo = isset($_POST['class_time_to']) ? $_POST['class_time_to'] : '';

    $sql = "
    SELECT 
        f.id, 
        f.first_name, 
        f.last_name, 
        f.department_id,
        d.department_name,
        f.email
    FROM 
        faculty f
    LEFT JOIN 
        departments d ON d.id = f.department_id
    WHERE 
        f.department_id = ? AND
        f.id NOT IN (
            SELECT 
                fl.professor_id
            FROM 
                faculty_load fl
            WHERE 
                fl.day = ? AND
                fl.class_time_from = ? AND
                fl.class_time_to = ? AND
                fl.school_year_from = ? AND
                fl.school_year_to = ? AND
                fl.term = ?
        )
    ORDER BY 
        f.id ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "issssss",
    $collegeId,
    $day,
    $timeFrom,
    $timeTo,
    $yearFrom,
    $yearTo,
    $term
);

    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}

function getSubstitutions($conn)
{
    $sql = "
        SELECT 
            ss.*, 
            f1.first_name AS original_first_name,
            f1.last_name AS original_last_name,
            f1.department_id AS original_department_id,
            f2.first_name AS substitute_first_name,
            f2.last_name AS substitute_last_name,
            f2.department_id AS substitute_department_id,
            s.subject_name AS subject_name
        FROM 
            substitution_schedule ss
        LEFT JOIN 
            faculty f1 ON ss.original_professor_id = f1.id
        LEFT JOIN 
            faculty f2 ON ss.substitute_professor_id = f2.id
        LEFT JOIN 
            subjects s ON ss.subject_id = s.id
        ORDER BY 
            ss.schedule_date DESC
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode([]);
    }
}


function getUnassignedSubjects($conn)
{
    $sql = "
        SELECT 
            s.*,
            d.department_name
        FROM 
            subjects s
        JOIN 
            departments d ON s.department_id = d.id
        WHERE 
            s.id NOT IN (SELECT subject_id FROM faculty_load)
        ORDER BY 
            s.subject_name ASC
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode([]);
    }
}

function getSections($conn)
{
    $sql = "
        SELECT 
            s.*,
            c.*,
            d.*,
            fl.*
        FROM 
            faculty_load fl
        JOIN 
            departments d ON fl.college_id = d.id
        JOIN 
            subjects s ON fl.subject_id = s.id
        JOIN 
            courses c ON fl.course_id = c.id
        ORDER BY fl.section ASC
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode([]);
    }
}

function getDeptLoad($conn)
{
    $year_from = $_GET['year_from'] ?? '';
    $year_to = $_GET['year_to'] ?? '';
    $term = $_GET['term'] ?? '';
    $college = $_GET['college'] ?? '';

    if (empty($year_from) || empty($year_to) || empty($term) || empty($college)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    $query = "
        SELECT 
            CONCAT(fa.first_name, ' ', fa.last_name) AS professor_name,
            s.subject_code, 
            s.subject_name, 
            fl.units, 
            fl.day, 
            fl.room, 
            fl.year_level, 
            fl.class_time_from, 
            fl.class_time_to, 
            fl.section
        FROM faculty_load fl
        INNER JOIN subjects s ON fl.subject_id = s.id
        INNER JOIN faculty fa ON fl.professor_id = fa.id
        WHERE fl.school_year_from = ? 
        AND fl.school_year_to = ? 
        AND fl.term = ? 
        AND fl.college_id = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssi', $year_from, $year_to, $term, $college);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
    $stmt->close();
    $conn->close();
}

function addSubstitution($conn)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $professor_id = $_POST['professor_id'] ?? '';
        $substitute_professor_id = $_POST['substitute_professor_id'] ?? '';
        $section = $_POST['section'] ?? '';
        $subject_id = $_POST['subject_id'] ?? '';
        $substitute_date = $_POST['substitute_date'] ?? '';
        $class_time_from = $_POST['class_time_from'] ?? '';
        $class_time_to = $_POST['class_time_to'] ?? '';

        if (
            empty($professor_id) || empty($substitute_professor_id) || empty($section) ||
            empty($subject_id) || empty($substitute_date) || empty($class_time_from) || empty($class_time_to)
        ) {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
            exit;
        }

        $checkQuery = "
            SELECT id FROM substitution_schedule 
            WHERE original_professor_id = ? AND substitute_professor_id = ? AND section = ? 
            AND subject_id = ? AND schedule_date = ? AND time_from = ? AND time_to = ?
        ";

        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param(
            'iisisss',
            $professor_id,
            $substitute_professor_id,
            $section,
            $subject_id,
            $substitute_date,
            $class_time_from,
            $class_time_to
        );

        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'This substitution already exists!']);
            $checkStmt->close();
            $conn->close();
            exit;
        }

        $checkStmt->close();

        $query = "
            INSERT INTO substitution_schedule 
            (original_professor_id, substitute_professor_id, section, subject_id, schedule_date, time_from, time_to)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            echo json_encode(['status' => 'error', 'message' => 'SQL error: ' . $conn->error]);
            exit;
        }

        $stmt->bind_param(
            'iisisss',
            $professor_id,
            $substitute_professor_id,
            $section,
            $subject_id,
            $substitute_date,
            $class_time_from,
            $class_time_to
        );

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Substitution added successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error adding substitution.']);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    }
}


function getSubjects2($conn) {

    $department_id = $_GET['department_id'] ?? 0;

    // $query = "
    //         SELECT s.id AS subject_id, s.subject_code, s.subject_name, s.units
    //         FROM programs p
    //         LEFT JOIN subjects s ON p.subject_id = s.id
    //         WHERE s.department_id = ?
    //         AND s.id NOT IN (
    //             SELECT subject_id FROM faculty_load
    //         )
    //         GROUP BY s.id
    //         ORDER BY s.subject_name ASC
    //     ";
    $query = "
        SELECT 
            s.id AS subject_id,
            s.subject_code,
            s.subject_name,
            s.units,
            p.term,
            p.year_level,
            p.section,
            r.room_number,
            p.schedule_day,
            p.schedule_time,
            p.school_year as schoolzear
        FROM programs p
        LEFT JOIN subjects s ON p.subject_id = s.id
        LEFT JOIN rooms r ON r.id = p.room_id
        WHERE s.department_id = ?
        AND s.id NOT IN (SELECT subject_id FROM faculty_load)
        ORDER BY s.subject_name ASC
    ";

    executeAndReturn($conn, $query, 'i', [$department_id]);
}

function getSubjects($conn) {
    $search = $_GET['search'] ?? '';
    $department_id = $_GET['department_id'] ?? 0;

    $query = "
        SELECT id, subject_code, subject_name, units
        FROM subjects
        WHERE (subject_code LIKE ? OR subject_name LIKE ?) AND department_id = ?
        ORDER BY subject_name ASC
    ";

    $search_param = '%' . $search . '%';
    executeAndReturn($conn, $query, 'ssi', [$search_param, $search_param, $department_id]);
}

function getSubjectDetails($conn) {
    $subject_code = $_GET['subject_code'] ?? '';

    $query = "SELECT subject_code, subject_name, units FROM subjects WHERE subject_code = ?";
    executeAndReturn($conn, $query, 's', [$subject_code]);
}

function getProfessorSuggestions($conn) {
    $term = $_GET['term'] ?? '';

    if (empty($term)) {
        echo json_encode([]);
        return;
    }

    $term = '%' . $term . '%';

    $query = "
        SELECT id AS employee_id, first_name, last_name 
        FROM faculty 
        WHERE first_name LIKE ? OR last_name LIKE ? OR id LIKE ?
        LIMIT 10
    ";

    executeAndReturn($conn, $query, 'sss', [$term, $term, $term]);
}

function getFacultyInfo($conn) {
    $professor_id = $_GET['professor_id'] ?? 0;
    $term = $_GET['term'] ?? '';

    $query = "
        SELECT f.first_name, f.last_name, d.department_name, f.emp_type, f.role, f.max_load, d.department_code,
               s.subject_code, s.subject_name
        FROM faculty f
        LEFT JOIN departments d ON f.department_id = d.id
        LEFT JOIN subjects s ON f.department_id = s.department_id
        WHERE f.id = ?
          AND s.term = ?
    ";

    executeAndReturn($conn, $query, 'is', [$professor_id, $term]);
}

function getFacultyLoad2($conn) {
    $professor_id = $_GET['professor_id'] ?? 0;

    $query = "
        SELECT fl.*, s.subject_name
        FROM faculty_load fl
        LEFT JOIN subjects s ON fl.subject_id = s.id
        WHERE fl.professor_id != ?
    ";

    executeAndReturn($conn, $query, 'i', [$professor_id]);
}

function getFacultyL($conn) {

    $professor_id = $_GET['professor_id'] ?? 0;
    $school_year_from = $_GET['school_year_from'] ?? 0;
    $school_year_to = $_GET['school_year_to'] ?? 0;
    $term = $_GET['term'] ?? '';

    $query = "
        SELECT fl.*, 
               s.subject_code, 
               s.subject_name, 
               s.units,
               f.first_name, 
               f.last_name,
               f.max_load,
               d.department_name, 
               d.department_code
        FROM faculty_load fl
        JOIN subjects s ON fl.subject_id = s.id
        JOIN faculty f ON fl.professor_id = f.id
        JOIN departments d ON f.department_id = d.id
        WHERE fl.professor_id = ? 
        AND fl.school_year_from = ? 
        AND fl.school_year_to = ? 
        AND fl.term = ?
    ";

    executeAndReturn($conn, $query, 'isss', [$professor_id, $school_year_from, $school_year_to, $term]);
}

function getDepartments($conn) {
    $query = "SELECT id, department_name FROM departments ORDER BY department_name ASC";
    executeAndReturn($conn, $query, '', []);
}
function getCourses($conn) {

    $collegeID = $_GET['id'] ?? 0;

    $query = "SELECT id, course_name FROM courses WHERE department_id = ? ORDER BY course_name ASC";

    executeAndReturn($conn, $query, 'i', [$collegeID]);
}
function getCanTeach($conn) {
    $professor_id = $_GET['professor_id'] ?? 0;

    $query = "
        SELECT s.subject_code, s.subject_name
        FROM faculty_can_teach fct
        JOIN subjects s ON fct.subject_id = s.id
        WHERE fct.faculty_id = ?
    ";

    executeAndReturn($conn, $query, 'i', [$professor_id]);
}

function getFacultyLoad($conn) {
    $professor_id = $_GET['professor_id'] ?? 0;
    $school_year_from = $_GET['school_year_from'] ?? 0;
    $school_year_to = $_GET['school_year_to'] ?? 0;
    $term = $_GET['term'] ?? '';

    $query = "
        SELECT s.subject_code, s.subject_name, fl.term, fl.section, fl.schedule, fl.room_number
        FROM faculty_load fl
        JOIN subjects s ON fl.subject_id = s.id
        WHERE fl.faculty_id = ? AND fl.school_year_from = ? AND fl.school_year_to = ? AND fl.term = ?
    ";

    executeAndReturn($conn, $query, 'iiis', [$professor_id, $school_year_from, $school_year_to, $term]);
}

function addFacultyLoad($conn)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $college_id = $_POST['college'] ?? '';
        $course_id = $_POST['courses'] ?? '';
        $professor_id = $_POST['professor_autocomp'] ?? '';
        $subject_id = $_POST['subject_autocomp'] ?? '';
        $units = $_POST['units_autocomp'] ?? '';
        $day = $_POST['day'] ?? '';
        $room = $_POST['room'] ?? '';
        $section = $_POST['section'] ?? '';
        $class_time_from = $_POST['class_time_from'] ?? '';
        $class_time_to = $_POST['class_time_to'] ?? '';
        $year_from = $_POST['year_term_from'] ?? '';
        $year_to = $_POST['year_term_to'] ?? '';
        $year_level = $_POST['year_level'] ?? '';
        $term = $_POST['term'] ?? '';

        if (
            empty($college_id) || empty($course_id) || empty($professor_id) || empty($subject_id) ||
            empty($units) || empty($day) || empty($class_time_from) || empty($class_time_to) ||
            empty($year_from) || empty($year_to) || empty($year_level) || empty($term)
        ) {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
            exit;
        }

        // Check for duplicate faculty load
        $checkQuery = "
            SELECT id FROM faculty_load 
            WHERE professor_id = ? AND subject_id = ? AND day = ? 
            AND class_time_from = ? AND class_time_to = ? AND school_year_from = ? 
            AND school_year_to = ? AND room = ? AND term = ?
        ";

        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param(
            'iisssssis',
            $professor_id,
            $subject_id,
            $day,
            $class_time_from,
            $class_time_to,
            $year_from,
            $year_to,
            $room,
            $term
        );

        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'This faculty load already exists!']);
            $checkStmt->close();
            $conn->close();
            exit;
        }

        $checkStmt->close();

        // Proceed with insertion if no duplicates found
        $query = "
            INSERT INTO faculty_load 
            (college_id, course_id, professor_id, subject_id, units, day, class_time_from, class_time_to, 
            school_year_from, school_year_to, year_level, room, section, term)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            echo json_encode(['status' => 'error', 'message' => 'SQL error: ' . $conn->error]);
            exit;
        }

        $stmt->bind_param(
            'iiissssssssiss',
            $college_id,
            $course_id,
            $professor_id,
            $subject_id,
            $units,
            $day,
            $class_time_from,
            $class_time_to,
            $year_from,
            $year_to,
            $year_level,
            $room,
            $section,
            $term
        );

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Faculty load added successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error adding faculty load.']);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    }
}

function executeAndReturn($conn, $query, $param_types = '', $params = []) {
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        echo json_encode(['error' => 'Query preparation failed: ' . $conn->error]);
        return;
    }

    if (!empty($params)) {
        $stmt->bind_param($param_types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    ob_clean();
    echo json_encode($data);
    $stmt->close();
}
?>

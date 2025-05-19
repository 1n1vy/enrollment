<?php
require __DIR__ . '/../includes/db_connection.php';
header('Content-Type: application/json');
ob_clean();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$action = $_REQUEST['action'] ?? '';

switch ($action) {
    case 'checkStudent':
        checkStudent($conn);
        break;
    case 'saveSelectedSubjects':
        saveSelectedSubjects($conn);
        break;
        
    default:
        echo json_encode(['error' => 'Invalid action.']);
        break;
}

function saveSelectedSubjects($conn) {
    $studentNumber = $_POST['student_number'] ?? '';
    $selectedPrograms = $_POST['selected_programs'] ?? [];
    $term = $_POST['term'] ?? '';
    $schoolYear = $_POST['school_year'] ?? '';
    $status = 'Enrolled'; 
    $today = date('Y-m-d'); 

    if (empty($studentNumber) || empty($selectedPrograms)) {
        echo json_encode(['success' => false, 'message' => 'Missing student or subjects.']);
        return;
    }

    $stmt = $conn->prepare("SELECT id FROM students WHERE student_number = ?");
    $stmt->bind_param("s", $studentNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if (!$student) {
        echo json_encode(['success' => false, 'message' => 'Student not found.']);
        return;
    }

    $studentId = $student['id'];
    $stmt->close();

    $insert = $conn->prepare("
        INSERT INTO student_course 
            (student_id, program_id, enrollment_date, school_year, term, status) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    foreach ($selectedPrograms as $programId) {
        $insert->bind_param("sissis", $studentId, $programId, $today, $schoolYear, $term, $status);
        $insert->execute();
    }

    $insert->close();

    echo json_encode(['success' => true, 'message' => 'Subjects successfully saved.']);
}

function checkStudent($conn) {

    $studentId = $_POST['student_id'] ?? '';
    $term = $_POST['term'] ?? '';
    $yearstart = $_POST['yearstart'] ?? '';
    $yearend = $_POST['yearend'] ?? '';
    $appendedYear = $yearstart . '-' . $yearend;

    if (empty($studentId)) {
        echo json_encode(['exists' => false, 'error' => 'Student ID is required.']);
        return;
    }

    $stmt = $conn->prepare("SELECT * FROM students WHERE student_number = ? AND term = ? AND school_year = ?");
    $stmt->bind_param("sss", $studentId, $term, $appendedYear);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }

    $stmt->close();
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
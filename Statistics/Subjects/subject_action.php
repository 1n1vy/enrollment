<?php
require_once '../../Custom/Handlers/connection.php';

$conn = new Connection();
$db = $conn->getConnection();

$redirectBase = 'CoursesPage.php';
$params = [
    'schoolyear1' => $_POST['schoolyear1'] ?? '',
    'schoolyear2' => $_POST['schoolyear2'] ?? '',
    'subject_status' => $_POST['subject_status'] ?? '',
    'department' => $_POST['department'] ?? '',
    'course' => $_POST['course'] ?? '',
    'sort_by' => $_POST['sort_by'] ?? '',
    'sort_order' => $_POST['sort_order'] ?? '',
];

$extraParams = [
    'hide_schedule',
    'show_leclab',
    'hide_college',
    'hide_capacity',
    'show_student_breakdown'
];

foreach ($extraParams as $key) {
    if (isset($_POST[$key])) {
        $params[$key] = 'on';
    }
}

$subjectId = isset($_POST['subject_id']) ? (int)$_POST['subject_id'] : null;

if ($subjectId) {
    if (isset($_POST['recover'])) {
        $stmt = $db->prepare("UPDATE subjects SET status = 'open' WHERE id = ?");
        $stmt->bind_param("i", $subjectId);
        $stmt->execute();
        $stmt->close();
        $params['success'] = 'recovered';
    }

    if (isset($_POST['delete'])) {
        $stmt = $db->prepare("DELETE FROM subjects WHERE id = ?");
        $stmt->bind_param("i", $subjectId);
        $stmt->execute();
        $stmt->close();
        $params['success'] = 'deleted';
    }

    $db->close();
    header("Location: $redirectBase?" . http_build_query($params));
    exit();
}

header("Location: view_dissolved.php?error=invalid");
exit();

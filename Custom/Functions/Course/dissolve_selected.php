<?php
require_once '../../Handlers/connection.php';
// require_once '../../Custom/Handlers/connection.php';

$conn = new Connection();
$db = $conn->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['select_program'])) {
    $programIds = $_POST['select_program'];

    $stmt = $db->prepare("
        UPDATE subjects 
        JOIN program ON subjects.id = program.subject_id 
        SET subjects.status = 'dissolved' 
        WHERE program.id = ?
    ");

    foreach ($programIds as $id) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    $stmt->close();

    // Redirect with original query parameters
    $params = http_build_query([
        'dissolved' => 'success',
        'schoolyear1' => $_POST['schoolyear1'] ?? '',
        'schoolyear2' => $_POST['schoolyear2'] ?? '',
        'subject_status' => $_POST['subject_status'] ?? 'all',
        'department' => $_POST['department'] ?? '',
        'course' => $_POST['course'] ?? '',
        'sort_by' => $_POST['sort_by'] ?? '',
        'term' => $_POST['term'] ?? '',
        'sort_order' => $_POST['sort_order'] ?? 'asc',
        'hide_schedule' => ($_POST['hide_schedule'] ?? '') === 'on' ? 'on' : '',
        'show_leclab' => ($_POST['show_leclab'] ?? '') === 'on' ? 'on' : '',
        'hide_college' => ($_POST['hide_college'] ?? '') === 'on' ? 'on' : '',
        'hide_capacity' => ($_POST['hide_capacity'] ?? '') === 'on' ? 'on' : '',
        'show_student_breakdown' => ($_POST['show_student_breakdown'] ?? '') === 'on' ? 'on' : '',
    ]);

    header("Location: queries.php?$params");
    exit();
} else {
    header("Location: queries.php?dissolved=error");
    exit();
}

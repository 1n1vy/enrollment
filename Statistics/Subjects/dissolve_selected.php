<?php
    require_once '../../Custom/Handlers/connection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['select_program'])) {
    $selectedPrograms = $_POST['select_program'];

    $conn = new Connection();
    $db = $conn->getConnection();

    // Prepare statement to update each program's subject status to 'dissolved'
    $stmt = $db->prepare("
        UPDATE subjects 
        JOIN program ON subjects.id = program.subject_id 
        SET subjects.status = 'dissolved'
        WHERE program.id = ?
    ");

    foreach ($selectedPrograms as $programId) {
        $stmt->bind_param("i", $programId);
        $stmt->execute();
    }

    $stmt->close();
    $db->close();

    // Redirect back to queries.php with original filters
    $query = http_build_query([
        'schoolyear1' => $_POST['schoolyear1'] ?? '',
        'schoolyear2' => $_POST['schoolyear2'] ?? '',
        'subject_status' => $_POST['subject_status'] ?? 'all',
        'department' => $_POST['department'] ?? ''
    ]);
    header("Location: CoursesPage.php?$query");
    exit();
} else {
    echo "No subjects selected.";
}
?>

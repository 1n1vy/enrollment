<?php
    require_once '../../Custom/Handlers/connection.php';

$conn = new Connection();
$db = $conn->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subjectId = isset($_POST['subject_id']) ? (int)$_POST['subject_id'] : null;

    if ($subjectId) {
        if (isset($_POST['recover'])) {
            // Recover action
            $stmt = $db->prepare("UPDATE subjects SET status = 'open' WHERE id = ?");
            $stmt->bind_param("i", $subjectId);
            $stmt->execute();
            $stmt->close();

            header("Location: view_dissolved.php?success=recovered");
            exit();
        }

        if (isset($_POST['delete'])) {
            // Delete action
            $stmt = $db->prepare("DELETE FROM subjects WHERE id = ?");
            $stmt->bind_param("i", $subjectId);
            $stmt->execute();
            $stmt->close();

            header("Location: view_dissolved.php?success=deleted");
            exit();
        }
    }
}

// Redirect back if no valid action
header("Location: view_dissolved.php?error=invalid");
exit();

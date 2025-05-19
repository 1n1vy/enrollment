<?php
require_once '../includes/db_connection.php';

if (isset($_POST['program_code'])) {
    // Get search parameters
    $program_code = $_POST['program_code'];
    $start_year = $_POST['start_year'];
    $end_year = $_POST['end_year'];
    $term = $_POST['term'];

    // Prepare SQL query to search programs based on program_code and school_year
    $query = "SELECT * FROM programs WHERE program_code LIKE :program_code";
    
    if ($start_year && $end_year) {
        // Filter by school year range
        $query .= " AND school_year BETWEEN :start_year AND :end_year";
    }

    if ($term) {
        $query .= " AND term = :term";
    }

    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':program_code', "%" . $program_code . "%");
    
    if ($start_year && $end_year) {
        $stmt->bindValue(':start_year', $start_year);
        $stmt->bindValue(':end_year', $end_year);
    }
    if ($term) $stmt->bindValue(':term', $term);

    $stmt->execute();
    $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($programs);
}
?>

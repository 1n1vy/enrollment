<?php
function getSubjectData($db) {
    $startYear = $_GET['schoolyear1'] ?? null;
    $endYear = $_GET['schoolyear2'] ?? null;
    $department = $_GET['department'] ?? null;

    $departmentId = null;
    if (!empty($department)) {
        $stmt = $db->prepare("SELECT id FROM departments WHERE department_name = ?");
        $stmt->bind_param("s", $department);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) $departmentId = $row['id'];
        $stmt->close();
    }

    $statusSQL = "SELECT status, COUNT(*) AS count FROM subjects";
    $params = [];
    $types = "";

    if ($departmentId) {
        $statusSQL .= " WHERE department_id = ?";
        $params[] = $departmentId;
        $types .= "i";
    }

    $statusSQL .= " GROUP BY status";
    $stmt = $db->prepare($statusSQL);
    if ($params) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $res = $stmt->get_result();

    $counts = ['open' => 0, 'closed' => 0, 'dissolved' => 0];
    while ($row = $res->fetch_assoc()) {
        $counts[strtolower($row['status'])] = $row['count'];
    }

    $stmt->close();

    $rows = [];
    $sql = "
        SELECT program.id AS program_id, departments.department_code,
            subjects.subject_code, subjects.subject_name, subjects.lec, subjects.lab, subjects.units,
            sections.section_code, sections.schedule_days, sections.start, sections.end,
            sections.min_student, sections.max_student,
            rooms.building_code
        FROM program
        JOIN subjects ON program.subject_id = subjects.id
        JOIN departments ON subjects.department_id = departments.id
        LEFT JOIN sections ON sections.course_id = program.course_id 
            AND sections.school_start_year = program.program_sy_start 
            AND sections.school_end_year = program.program_sy_end
        LEFT JOIN rooms ON rooms.id = program.room_id
        WHERE LOWER(subjects.status) != 'deleted'
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return ['counts' => $counts, 'rows' => $rows];
}
?>
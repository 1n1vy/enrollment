<?php
function getDissolvedSubjects($db) {
    $startYear = isset($_GET['schoolyear1']) ? (int)$_GET['schoolyear1'] : null;
    $endYear = isset($_GET['schoolyear2']) ? (int)$_GET['schoolyear2'] : null;
    $department = isset($_GET['deparment']) ? trim($_GET['deparment']) : null;

    $departmentId = null;
    if (!empty($department)) {
        $stmt = $db->prepare("SELECT id FROM departments WHERE department_name = ?");
        $stmt->bind_param("s", $department);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) $departmentId = $row['id'];
        $stmt->close();
    }

    $sql = "
        SELECT 
            subjects.id AS subject_id,
            subjects.subject_code,
            subjects.subject_name,
            subjects.units,
            subjects.lec,
            subjects.lab,
            departments.department_code,
            sections.section_code,
            sections.schedule_days AS section_schedule,
            sections.start AS section_start_time,
            sections.end AS section_end_time,
            rooms.building_code,
            sections.min_student,
            sections.max_student
        FROM subjects
        JOIN departments ON subjects.department_id = departments.id
        LEFT JOIN program ON subjects.id = program.subject_id
        LEFT JOIN sections ON program.course_id = sections.course_id 
            AND sections.school_start_year = program.program_sy_start 
            AND sections.school_end_year = program.program_sy_end
        LEFT JOIN rooms ON rooms.id = program.room_id
        WHERE LOWER(subjects.status) = 'dissolved'
    ";

    if (!empty($departmentId)) {
        $sql .= " AND subjects.department_id = $departmentId";
    }

    if (!empty($startYear) && !empty($endYear)) {
        $sql .= " AND program.program_sy_start = $startYear AND program.program_sy_end = $endYear";
    }

    $result = $db->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}
?>
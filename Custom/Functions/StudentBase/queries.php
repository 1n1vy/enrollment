<?php
function getLevelNames($mysqli, $level_names) {
    if (count($level_names) == 1 && strtolower(trim($level_names[0])) === 'all') {
        $result = $mysqli->query("SELECT level_name FROM education_levels");
        $level_names = [];
        while ($row = $result->fetch_assoc()) {
            $level_names[] = $row['level_name'];
        }
    }
    return $level_names;
}

function getLevelNamesById($mysqli, $year_level) {
    if ($year_level === 'all') {
        return ['all'];
    }

    $level_names = [];
    $ids = explode(',', $year_level);
    $escaped_ids = implode(',', array_map('intval', $ids));
    $query = "SELECT level_name FROM education_levels WHERE id IN ($escaped_ids)";
    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $level_names[] = $row['level_name'];
    }
    return $level_names;
}

function buildSelectColumns($mysqli, $level_names, $gender) {
    $columns = [];
    foreach ($level_names as $level) {
        $level_clean = trim($level);
        $escaped = $mysqli->real_escape_string($level_clean);

        if ($gender === 'male' || $gender === 'all') {
            $columns[] = "SUM(CASE WHEN e.level_name = '$escaped' AND s.gender = 'male' THEN 1 ELSE 0 END) AS `{$level_clean}_Male`";
        }
        if ($gender === 'female' || $gender === 'all') {
            $columns[] = "SUM(CASE WHEN e.level_name = '$escaped' AND s.gender = 'female' THEN 1 ELSE 0 END) AS `{$level_clean}_Female`";
        }
    }
    return implode(",\n", $columns);
}

function fetchStudentData($mysqli, $where, $select_levels) {
    $query = "
    SELECT 
        d.department_name AS department,
        c.course_code AS course_code,
        m.major_name AS major,
        $select_levels,
        COUNT(*) AS total_students
    FROM students s
    LEFT JOIN departments d ON d.id = s.departments_id
    LEFT JOIN courses c ON c.id = s.course_id
    LEFT JOIN majors m ON m.id = s.major_id
    LEFT JOIN education_levels e ON e.id = s.education_level_id
    LEFT JOIN year_terms yt ON yt.id = s.year_terms_id
    LEFT JOIN semesters sem ON sem.id = yt.term_id
    $where
    GROUP BY d.department_name, c.course_code, m.major_name
    ORDER BY d.department_name, c.course_code, m.major_name
    ";
    return $mysqli->query($query);
}

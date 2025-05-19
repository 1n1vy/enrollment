<?php
function getEnrollmentCounts(
    $conn, $year, $term_id, $gender = 'all', $department_id = 0,
    $course_id = 0, $major_id = 0, $year_level = 'all',
    $student_status = 'all', $age_param = null
) {
    $where = [
        "yt.year = ?",
        "yt.term_id = ?",
        "s.departments_id IS NOT NULL",
        "s.course_id IS NOT NULL"
    ];
    $types = "ii";
    $params = [$year, $term_id];

    if ($gender !== 'all') {
        $where[] = "LOWER(s.gender) = ?";
        $types .= "s";
        $params[] = strtolower($gender);
    }

    if ($department_id !== 0) {
        $where[] = "s.departments_id = ?";
        $types .= "i";
        $params[] = $department_id;
    }

    if ($course_id !== 0) {
        $where[] = "s.course_id = ?";
        $types .= "i";
        $params[] = $course_id;
    }

    if ($major_id !== 0) {
        $where[] = "s.major_id = ?";
        $types .= "i";
        $params[] = $major_id;
    }

    if ($year_level !== 'all') {
        $where[] = "s.education_level_id = ?";
        $types .= "i";
        $params[] = (int)$year_level;
    }

    if ($student_status !== 'all') {
        $where[] = "LOWER(s.student_status) = ?";
        $types .= "s";
        $params[] = strtolower($student_status);
    }

    if ($age_param !== null) {
        $where[] = "s.age = ?";
        $types .= "i";
        $params[] = $age_param;
    }

    $where_sql = "WHERE " . implode(" AND ", $where);

    $query = "
        SELECT 
            COALESCE(d.department_name, 'No Department') AS department,
            COALESCE(c.course_code, 'No Course') AS course,
            COALESCE(m.major_name, 'None') AS major,
            s.gender,
            COUNT(*) AS total
        FROM students s
        LEFT JOIN departments d ON d.id = s.departments_id
        LEFT JOIN courses c ON c.id = s.course_id
        LEFT JOIN majors m ON m.id = s.major_id
        LEFT JOIN year_terms yt ON yt.id = s.year_terms_id
        $where_sql
        GROUP BY d.department_name, c.course_code, m.major_name, s.gender
        ORDER BY d.department_name, c.course_code, m.major_name;
    ";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result();
}

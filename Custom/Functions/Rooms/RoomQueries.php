<?php

function buildRoomQuery($withLocation = true) {
    $sql = "SELECT 
                r.room_type,
                r.building_location,
                r.status,
                COUNT(*) AS total
            FROM rooms r
            JOIN programs p ON p.room_id = r.id
            WHERE 1=1";

    $sql .= $withLocation ? " AND r.building_location IS NOT NULL" : " AND r.building_location IS NULL";
    return $sql;
}

function appendFilters(&$filters, &$types, &$params, $get) {
    if (!empty($get['year1']) && !empty($get['year2'])) {
        $filters .= " AND r.school_year IN (?, ?)";
        $types .= "ii";
        $params[] = $get['year1'];
        $params[] = $get['year2'];
    }

    if (!empty($get['term']) && strtolower($get['term']) !== 'all') {
        $map = [
            '1st' => '1st-Semester',
            '2nd' => '2nd-Semester',
            '1st-se' => '1st-Semester',
            '2nd-se' => '2nd-Semester',
            'summer' => 'Summer'
        ];
        $term = $map[strtolower($get['term'])] ?? $get['term'];
        $filters .= " AND r.term = ?";
        $types .= "s";
        $params[] = $term;
    }

    if (!empty($get['room-status']) && strtolower($get['room-status']) !== 'all') {
        $filters .= " AND r.status = ?";
        $types .= "s";
        $params[] = ucfirst(strtolower($get['room-status']));
    }

    if (!empty($get['room']) && strtolower($get['room']) !== 'all') {
        $filters .= " AND r.room_type LIKE ?";
        $types .= "s";
        $params[] = "%" . $get['room'] . "%";
    }

    if (!empty($get['roomnum']) && strtolower($get['roomnum']) !== 'all') {
        $filters .= " AND r.room_number LIKE ?";
        $types .= "s";
        $params[] = "%" . $get['roomnum'] . "%";
    }

    if (!empty($get['startTime'])) {
        $filters .= " AND p.schedule_time = ?";
        $types .= "s";
        $params[] = $get['startTime'] . ':00';
    }

    if (!empty($get['endTime'])) {
        $filters .= " AND p.schedule_time = ?";
        $types .= "s";
        $params[] = $get['endTime'] . ':00';
    }
}

function fetchGroupedRooms($conn, $sql, $types, $params) {
    $stmt = $conn->prepare($sql);
    if ($types) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $grouped = [];
    while ($row = $result->fetch_assoc()) {
        $status = strtoupper($row['status'] ?? 'Unspecified');
        $grouped[$status][] = $row;
    }
    $stmt->close();
    return $grouped;
}

<?php
require_once '../Handlers/connection.php';

$db = new Connection();
$conn = $db->getConnection();

// Get filters
$year1 = $_GET['year1'] ?? null;
$year2 = $_GET['year2'] ?? null;
$term = $_GET['term'] ?? null;
$room_status = $_GET['room-status'] ?? null;
$room_type = $_GET['room'] ?? null;
$roomnum = $_GET['roomnum'] ?? null;
$startTime = $_GET['startTime'] ?? null;
$endTime = $_GET['endTime'] ?? null;

// Get checkbox states
$showRoomNumbersGrouped = isset($_GET['show_room_numbers_grouped']);
$showRoomNumbersOnly = isset($_GET['show_room_numbers_only']);

// Build SQL base
function buildRoomQuery($includeNullLocation = false) {
    $base = "SELECT 
                r.room_type,
                r.building_location,
                r.status,
                COUNT(*) as total
            FROM rooms r
            JOIN program p ON p.room_id = r.id
            WHERE 1=1";

    if ($includeNullLocation) {
        $base .= " AND r.building_location IS NULL";
    } else {
        $base .= " AND r.building_location IS NOT NULL";
    }

    return $base;
}

// Create query for both sets
$sql1 = buildRoomQuery(false); // with location
$sql2 = buildRoomQuery(true);  // no location

$params = [];
$types = "";

// Apply filters
$filters = "";

if ($year1 !== null && $year2 !== null) {
    $filters .= " AND r.school_year_end IN (?, ?)";
    $types .= "ii";
    $params[] = $year1;
    $params[] = $year2;
}

if (!empty($term) && strtolower($term) !== 'all') {
    $term_map = [
        '1st' => '1st-Semester',
        '2nd' => '2nd-Semester',
        '1st-se' => '1st-Semester',
        '2nd-se' => '2nd-Semester',
        'summer' => 'Summer'
    ];
    $term_value = $term_map[strtolower($term)] ?? $term;
    $filters .= " AND r.term = ?";
    $types .= "s";
    $params[] = $term_value;
}

if (!empty($room_status) && strtolower($room_status) !== 'all') {
    $filters .= " AND r.status = ?";
    $types .= "s";
    $params[] = ucfirst(strtolower($room_status));
}

if (!empty($room_type) && strtolower($room_type) !== 'all') {
    $filters .= " AND r.room_type LIKE ?";
    $types .= "s";
    $params[] = "%" . $room_type . "%";
}

if (!empty($roomnum) && strtolower($roomnum) !== 'all') {
    $filters .= " AND r.room_number LIKE ?";
    $types .= "s";
    $params[] = "%" . $roomnum . "%";
}

if (!empty($startTime)) {
    $filters .= " AND p.schedule_start_time = ?";
    $types .= "s";
    $params[] = $startTime . ':00';
}

if (!empty($endTime)) {
    $filters .= " AND p.schedule_end_time = ?";
    $types .= "s";
    $params[] = $endTime . ':00';
}



// Finalize SQL
$sql1 .= $filters . " GROUP BY r.status, r.room_type, r.building_location ORDER BY r.status IS NULL, r.status, r.room_type";
$sql2 .= $filters . " GROUP BY r.status, r.room_type, r.building_location ORDER BY r.status IS NULL, r.status, r.room_type";

// Function to run and group result
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

// Fetch results
$groupedWithLocation = fetchGroupedRooms($conn, $sql1, $types, $params);
$groupedWithoutLocation = fetchGroupedRooms($conn, $sql2, $types, $params);

// Function to get room numbers
function getRoomNumbers($conn, $room_type, $location, $status) {
    $roomNumbers = [];

    $query = "SELECT room_number FROM rooms 
              WHERE room_type <=> ? AND building_location <=> ? AND status <=> ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $room_type, $location, $status);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($r = $result->fetch_assoc()) {
        $roomNumbers[] = $r['room_number'];
    }
    $stmt->close();

    return implode(', ', $roomNumbers);
}

function displayRoomTable($grouped, $conn, $showRoomNumbersGrouped, $showRoomNumbersOnly, $label = '') {
    if (empty($grouped)) return;

    echo "<h2 class='text-lg font-bold my-4'>$label</h2>";

    foreach ($grouped as $status => $rows) {
        $statusTitle = htmlspecialchars($status);
        $bgHeader = "#174069";
        $bgTotal = "gray";
        $grandTotal = 0;

        echo "<table class='min-w-full bg-white border border-gray-300 text-sm mb-6'>";
        echo "<thead>
                <tr>
                    <th colspan='3' class='py-3 px-5 text-white text-left' style='padding: 3px; background-color: $bgHeader;'>Room Status: <span> $statusTitle</span></th>
                </tr>";

        if ($showRoomNumbersOnly) {
            echo "<tr class='bg-gray-200'>
                    <th class='py-3 px-6 text-center' colspan='3'>ROOM NUMBERS</th>
                  </tr>";
        } else {
            echo "<tr class='bg-gray-200'>
                    <th class='py-3 px-6 text-center'>TYPE</th>
                    <th class='py-3 px-6 text-center'>LOCATION</th>
                    <th class='py-3 px-6 text-center'>TOTAL</th>
                  </tr>";
        }

        echo "</thead><tbody>";

        foreach ($rows as $row) {
            $room_type = $row['room_type'] ?? 'Unspecified';
            $location = $row['building_location'] ?? 'Unassigned';
            $status_val = $row['status'] ?? 'Unspecified';
            $total = $row['total'] ?? 0;
            $grandTotal += $total;

            $roomList = getRoomNumbers($conn, $room_type, $location, $status_val);

            echo "<tr>";

            if ($showRoomNumbersOnly) {
                echo "<td class='py-3 px-6' colspan='3'>Rooms: $roomList</td>";
            } elseif ($showRoomNumbersGrouped) {
                echo "<td class='py-3 px-6'>{$room_type}<br><small>Rooms: $roomList</small></td>
                      <td class='py-3 px-6'>{$location}</td>
                      <td class='py-3 px-6 text-center'>{$total}</td>";
            } else {
                echo "<td class='py-3 px-6'>{$room_type}</td>
                      <td class='py-3 px-6'>{$location}</td>
                      <td class='py-3 px-6 text-center'>{$total}</td>";
            }

            echo "</tr>";
        }

        if (!$showRoomNumbersOnly) {
            echo "<tr>
                    <th colspan='2' class='py-3 px-5 text-white text-right' style='padding: 3px; background-color: $bgTotal;'>Total:</th>
                    <th colspan='1' class='py-3 px-5 text-white text-center' style='padding: 3px; background-color: $bgTotal;'>$grandTotal</th>
                  </tr>";
        }

        echo "</tbody></table>";
    }
}

// Output
echo "<!DOCTYPE html><html><head><title>Room Report</title></head><body>";

displayRoomTable($groupedWithLocation, $conn, $showRoomNumbersGrouped, $showRoomNumbersOnly, "Rooms WITH Building Location");
displayRoomTable($groupedWithoutLocation, $conn, $showRoomNumbersGrouped, $showRoomNumbersOnly, "Rooms WITHOUT Building Location");

echo "</body></html>";

$conn->close();

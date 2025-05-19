<?php

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

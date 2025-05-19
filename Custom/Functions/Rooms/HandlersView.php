<?php

require_once 'HandlersQueries.php';

function displayRoomTable($grouped, $conn, $showGrouped, $showOnly, $label) {
    if (empty($grouped)) return;

    echo "<h2 class='text-lg font-bold my-4'>$label</h2>";

    foreach ($grouped as $status => $rows) {
        $bgHeader = "#174069";
        $bgTotal = "gray";
        $grandTotal = 0;

        echo "<table class='min-w-full bg-white border border-gray-300 text-sm mb-6'>";
        echo "<thead>
                <tr>
                    <th colspan='3' class='py-3 px-5 text-white text-left' style='background:$bgHeader;'>Room Status: <span>$status</span></th>
                </tr>";

        if ($showOnly) {
            echo "<tr class='bg-gray-200'><th class='py-3 px-6 text-center' colspan='3'>ROOM NUMBERS</th></tr>";
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
            if ($showOnly) {
                echo "<td class='py-3 px-6' colspan='3'>Rooms: $roomList</td>";
            } elseif ($showGrouped) {
                echo "<td class='py-3 px-6'>$room_type<br><small>Rooms: $roomList</small></td>
                      <td class='py-3 px-6'>$location</td>
                      <td class='py-3 px-6 text-center'>$total</td>";
            } else {
                echo "<td class='py-3 px-6'>$room_type</td>
                      <td class='py-3 px-6'>$location</td>
                      <td class='py-3 px-6 text-center'>$total</td>";
            }
            echo "</tr>";
        }

        if (!$showOnly) {
            echo "<tr>
                    <th colspan='2' class='py-3 px-5 text-white text-right' style='background:$bgTotal;'>Total:</th>
                    <th class='py-3 px-5 text-white text-center' style='background:$bgTotal;'>$grandTotal</th>
                  </tr>";
        }

        echo "</tbody></table>";
    }
}

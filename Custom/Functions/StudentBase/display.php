<?php
function displayTable($data, $department_counts, $level_names, $gender) {
    echo "<table class='min-w-full bg-white border border-gray-200'>";

    if (count($data) > 0) {
        // Table Header
        echo "<thead>";
        echo "<tr class='bg-blue-900 text-white uppercase text-sm'>";
        echo "<th rowspan='2' class='py-3 px-4 border'>Department</th>";
        echo "<th rowspan='2' class='py-3 px-4 border'>Course Code</th>";
        echo "<th rowspan='2' class='py-3 px-4 border'>Major</th>";

        foreach ($level_names as $level) {
            $colspan = ($gender === 'all') ? 2 : 1;
            echo "<th colspan='{$colspan}' class='py-2 px-4 border'>" . htmlspecialchars(trim($level)) . "</th>";
        }

        echo "<th rowspan='2' class='py-3 px-4 border'>Total Students</th>";
        echo "</tr>";

        echo "<tr class='bg-gray-200 text-gray-600 uppercase text-sm'>";
        foreach ($level_names as $level) {
            if ($gender === 'male' || $gender === 'all') {
                echo "<th class='py-2 px-3 border'>M</th>";
            }
            if ($gender === 'female' || $gender === 'all') {
                echo "<th class='py-2 px-3 border'>F</th>";
            }
        }
        echo "</tr>";
        echo "</thead>";

        echo "<tbody class='text-gray-700 text-sm'>";

        $printed_depts = [];
        $grand_total = 0;

        foreach ($data as $row) {
            echo "<tr class='border-b border-gray-200 hover:bg-gray-100'>";
            $dept = $row['department'];

            if (!in_array($dept, $printed_depts)) {
                echo "<td rowspan='{$department_counts[$dept]}' class='py-3 px-4 border bg-gray-50 font-semibold'>" . htmlspecialchars($dept ?? 'N/A') . "</td>";
                $printed_depts[] = $dept;
            }

            echo "<td class='py-3 px-4 border'>" . htmlspecialchars($row['course_code'] ?? 'N/A') . "</td>";
            echo "<td class='py-3 px-4 border'>" . htmlspecialchars($row['major'] ?? 'N/A') . "</td>";

            foreach ($level_names as $level) {
                if ($gender === 'male' || $gender === 'all') {
                    $male_val = (int) ($row[trim($level).'_Male'] ?? 0);
                    echo "<td class='py-2 px-3 border'>" . htmlspecialchars($male_val) . "</td>";
                }
                if ($gender === 'female' || $gender === 'all') {
                    $female_val = (int) ($row[trim($level).'_Female'] ?? 0);
                    echo "<td class='py-2 px-3 border'>" . htmlspecialchars($female_val) . "</td>";
                }
            }

            $grand_total += (int)$row['total_students'];
            echo "<td class='py-3 px-4 border font-medium'>" . htmlspecialchars($row['total_students'] ?? '0') . "</td>";
            echo "</tr>";
        }

        // Subtotal Row
        $cols = 3 + count($level_names) * (($gender === 'all') ? 2 : 1);
        echo "<tr class='bg-gray-200 text-gray-600 font-semibold'>
                <td colspan='{$cols}' class='text-right py-2 px-4 border'>SUBTOTAL:</td>
                <td class='py-2 px-4 border'>" . htmlspecialchars($grand_total) . "</td>
              </tr>";

        echo "</tbody>";
    } else {
        echo "<tr><td colspan='10' class='py-4 px-4 text-center'>No data found.</td></tr>";
    }

    echo "</table>";
}

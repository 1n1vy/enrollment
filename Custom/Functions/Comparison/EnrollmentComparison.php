<?php
function buildComparisonTable($prevResults, $currResults, $prevTitle, $currTitle, $conn, $department_id) {
    $data = [];
    $department_name = '';

    while ($row = $prevResults->fetch_assoc()) {
        $dept = $row['department'];
        $course = $row['course'];
        $major = $row['major'];
        $gender = strtolower($row['gender']);
        $data[$dept][$course][$major]['prev'][$gender] = $row['total'];
    }

    while ($row = $currResults->fetch_assoc()) {
        $dept = $row['department'];
        $course = $row['course'];
        $major = $row['major'];
        $gender = strtolower($row['gender']);
        $data[$dept][$course][$major]['curr'][$gender] = $row['total'];
    }

    if ($department_id !== 'all' && $department_id != 0) {
        $stmt = $conn->prepare("SELECT department_name FROM departments WHERE id = ?");
        $stmt->bind_param("i", $department_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $department_name = $row['department_name'];
        }
    }

    echo $department_name
        ? "<h3 class='text-lg font-semibold mb-4'>Department: {$department_name}</h3>"
        : "<h3 class='text-lg font-semibold mb-4'>Department: All Departments</h3>";

    echo "<table class='min-w-full bg-white border border-gray-300 text-sm text-left'>";
    echo "<thead>";
    echo "<tr class='bg-gray-200'>";
    echo "<th rowspan='2' class='py-3 px-6'>Department</th>";
    echo "<th rowspan='2' class='py-3 px-6'>Course</th>";
    echo "<th rowspan='2' class='py-3 px-6'>Major</th>";
    echo "<th colspan='3' class='py-3 px-6 text-center'>{$prevTitle}</th>";
    echo "<th colspan='3' class='py-3 px-6 text-center'>{$currTitle}</th>";
    echo "</tr>";
    echo "<tr class='bg-gray-100'>";
    echo "<th class='py-3 px-6'>Male</th><th class='py-3 px-6'>Female</th><th class='py-3 px-6'>Total</th>";
    echo "<th class='py-3 px-6'>Male</th><th class='py-3 px-6'>Female</th><th class='py-3 px-6'>Total</th>";
    echo "</tr>";
    echo "</thead><tbody>";

    $grand_total_prev = 0;
    $grand_total_curr = 0;

    foreach ($data as $dept => $courses) {
        $deptRowCount = 0;
        foreach ($courses as $majors) {
            $deptRowCount += count($majors);
        }

        $firstDeptPrinted = false;

        foreach ($courses as $course => $majors) {
            $courseRowCount = count($majors);
            $firstCoursePrinted = false;

            foreach ($majors as $major => $counts) {
                $prev_male = $counts['prev']['male'] ?? 0;
                $prev_female = $counts['prev']['female'] ?? 0;
                $prev_total = $prev_male + $prev_female;

                $curr_male = $counts['curr']['male'] ?? 0;
                $curr_female = $counts['curr']['female'] ?? 0;
                $curr_total = $curr_male + $curr_female;

                $grand_total_prev += $prev_total;
                $grand_total_curr += $curr_total;

                echo "<tr class='border-b'>";
                if (!$firstDeptPrinted) {
                    echo "<td rowspan='{$deptRowCount}' class='py-3 px-6'>{$dept}</td>";
                    $firstDeptPrinted = true;
                }
                if (!$firstCoursePrinted) {
                    echo "<td rowspan='{$courseRowCount}' class='py-3 px-6'>{$course}</td>";
                    $firstCoursePrinted = true;
                }

                echo "<td class='py-3 px-6'>{$major}</td>";
                echo "<td class='py-3 px-6'>{$prev_male}</td><td class='py-3 px-6'>{$prev_female}</td><td class='py-3 px-6'>{$prev_total}</td>";
                echo "<td class='py-3 px-6'>{$curr_male}</td><td class='py-3 px-6'>{$curr_female}</td><td class='py-3 px-6'>{$curr_total}</td>";
                echo "</tr>";
            }
        }
    }

    echo "<tr class='font-bold'>";
    echo "<td colspan='3' class='py-3 px-6 text-right'>Total</td>";
    echo "<td colspan='3' class='py-3 px-6'>{$grand_total_prev}</td>";
    echo "<td colspan='3' class='py-3 px-6'>{$grand_total_curr}</td>";
    echo "</tr>";

    echo "<tr class='font-bold'>";
    echo "<td colspan='3' class='py-3 px-6 text-right'>Grand Total</td>";
    echo "<td colspan='6' class='py-3 px-6 text-center'>" . ($grand_total_prev + $grand_total_curr) . "</td>";
    echo "</tr>";

    echo "</tbody></table>";
}

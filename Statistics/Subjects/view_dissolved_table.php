<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View All Dissolved Subjects</title>
    <style>
        .btn {
            padding: 6px 12px;
            border: none;
            cursor: pointer;
            color: #fff;
        }
        .btn-recover {
            background-color: #28a745;
        }
        .btn-delete {
            background-color: #dc3545;
        }
        .header-row th {
            padding: 10px;
            background-color: #f2f2f2;
            border: 1px solid #aaa;
            text-align: center;
        }
        td {
            padding: 10px;
            border: 1px solid #aaa;
            text-align: center;
        }
    </style>
</head>
<body>

<h2>View All Dissolved Subjects</h2>

<table class="min-w-full bg-white" id="deletedCoursesTable">
    <thead>
        <tr class="header-row">
            <th>OFFERING COLLEGE</th>
            <th>COURSE CODE (DESCRIPTION)</th>
            <th>SECTION / SCHEDULE</th>
            <th>LEC/LAB</th>
            <th>MIN CAPACITY</th>
            <th>MAX CAPACITY</th>
            <th>ACTIONS</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($data)) {
            $queryParams = [
                'schoolyear1' => $_GET['schoolyear1'] ?? '',
                'schoolyear2' => $_GET['schoolyear2'] ?? '',
                'subject_status' => $_GET['subject_status'] ?? '',
                'department' => $_GET['department'] ?? '',
                'course' => $_GET['course'] ?? '',
                'sort_by' => $_GET['sort_by'] ?? '',
                'sort_order' => $_GET['sort_order'] ?? '',
                'hide_schedule' => isset($_GET['hide_schedule']) ? 'on' : '',
                'show_leclab' => isset($_GET['show_leclab']) ? 'on' : '',
                'hide_college' => isset($_GET['hide_college']) ? 'on' : '',
                'hide_capacity' => isset($_GET['hide_capacity']) ? 'on' : '',
                'show_student_breakdown' => isset($_GET['show_student_breakdown']) ? 'on' : '',
            ];

            foreach ($data as $row) {
                $scheduleTime = (!empty($row['section_start_time']) && !empty($row['section_end_time']))
                    ? date("g:i A", strtotime($row['section_start_time'])) . "–" . date("g:i A", strtotime($row['section_end_time']))
                    : "T.B.A.";

                $fullSchedule = htmlspecialchars($row['section_code'] ?? '---') . " ::: "
                              . htmlspecialchars($row['section_schedule'] ?? '-') . " "
                              . $scheduleTime . " (" . htmlspecialchars($row['building_code'] ?? 'T.B.A.') . ")";

                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['department_code'] ?? '-') . "</td>";
                echo "<td>" . htmlspecialchars($row['subject_code']) . " - " . htmlspecialchars($row['subject_name']) . "</td>";
                echo "<td>$fullSchedule</td>";
                echo "<td>" . (int)$row['lec'] . "/" . (int)$row['lab'] . "</td>";
                echo "<td>" . htmlspecialchars($row['min_student'] ?? '-') . "</td>";
                echo "<td>" . htmlspecialchars($row['max_student'] ?? '-') . "</td>";
                echo "<td>
                        <form method='POST' action='subject_action.php' style='display:inline;'>";
                foreach ($queryParams as $name => $val) {
                    echo "<input type='hidden' name='" . htmlspecialchars($name) . "' value='" . htmlspecialchars($val) . "'>";
                }
                echo "<input type='hidden' name='subject_id' value='" . $row['subject_id'] . "'>
                            <button type='submit' name='recover' class='btn btn-recover'>Recover</button>
                        </form>
                        <form method='POST' action='subject_action.php' style='display:inline;'>";
                foreach ($queryParams as $name => $val) {
                    echo "<input type='hidden' name='" . htmlspecialchars($name) . "' value='" . htmlspecialchars($val) . "'>";
                }
                echo "<input type='hidden' name='subject_id' value='" . $row['subject_id'] . "'>
                            <button type='submit' name='delete' class='btn btn-delete'>Delete Permanently</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No dissolved subjects found.</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>

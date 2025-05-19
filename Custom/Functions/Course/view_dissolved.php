<?php
require_once '../Handlers/connection.php';

$conn = new Connection();
$db = $conn->getConnection();

$startYear = isset($_GET['schoolyear1']) ? (int)$_GET['schoolyear1'] : null;
$endYear = isset($_GET['schoolyear2']) ? (int)$_GET['schoolyear2'] : null;
$department = isset($_GET['deparment']) ? trim($_GET['deparment']) : null;

$departmentId = null;
if (!empty($department)) {
    $deptStmt = $db->prepare("SELECT id FROM departments WHERE department_name = ?");
    $deptStmt->bind_param("s", $department);
    $deptStmt->execute();
    $deptResult = $deptStmt->get_result();
    if ($deptRow = $deptResult->fetch_assoc()) {
        $departmentId = $deptRow['id'];
    }
    $deptStmt->close();
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View All Dissolved Subjects</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #aaa;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
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
    </style>
</head>
<body>

<h2>View All Dissolved Subjects</h2>

<table>
    <thead>
        <tr>
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
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
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
                        <form method='POST' action='subject_action.php' style='display:inline;'>
                            <input type='hidden' name='subject_id' value='" . $row['subject_id'] . "'>
                            <button type='submit' name='recover' class='btn btn-recover'>Recover</button>
                        </form>
                        <form method='POST' action='subject_action.php' style='display:inline;'>
                            <input type='hidden' name='subject_id' value='" . $row['subject_id'] . "'>
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

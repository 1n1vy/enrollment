<?php
require_once '../../Custom/Handlers/connection.php';

$conn = new Connection();
$db = $conn->getConnection();

$startYear = isset($_GET['schoolyear1']) ? (int)$_GET['schoolyear1'] : null;
$endYear = isset($_GET['schoolyear2']) ? (int)$_GET['schoolyear2'] : null;
$subject_status = isset($_GET['subject_status']) ? trim($_GET['subject_status']) : null;
$department = isset($_GET['department']) ? trim($_GET['department']) : null;
$course = isset($_GET['course']) ? trim($_GET['course']) : null;
$sort_by = isset($_GET['sort_by']) ? trim($_GET['sort_by']) : '';
$sort_order = isset($_GET['sort_order']) ? strtolower(trim($_GET['sort_order'])) : 'asc';
$hide_schedule = isset($_GET['hide_schedule']);
$show_leclab = isset($_GET['show_leclab']);
$hide_college = isset($_GET['hide_college']);
$hide_capacity = isset($_GET['hide_capacity']);
$show_student_breakdown = isset($_GET['show_student_breakdown']);
$term = isset($_GET['term']) ? trim($_GET['term']) : null;

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

$openCount = $closedCount = $dissolvedCount = 0;
$statusParams = [];
$statusTypes = "";
$statusSQL = "SELECT subjects.status, COUNT(*) AS count FROM subjects";

if (!is_null($departmentId)) {
    $statusSQL .= " WHERE subjects.department_id = ?";
    $statusParams[] = $departmentId;
    $statusTypes .= "i";
}

$statusSQL .= " GROUP BY subjects.status";
$statusStmt = $db->prepare($statusSQL);
if (!empty($statusParams)) {
    $statusStmt->bind_param($statusTypes, ...$statusParams);
}
$statusStmt->execute();
$statusResult = $statusStmt->get_result();

while ($row = $statusResult->fetch_assoc()) {
    switch (strtolower($row['status'])) {
        case 'open': $openCount = $row['count']; break;
        case 'closed': $closedCount = $row['count']; break;
        case 'dissolved': $dissolvedCount = $row['count']; break;
    }
}
$statusStmt->close();

$totalLec = 0;
$totalLab = 0;

if ($startYear && $endYear) {
    $allowedSortFields = ['subject_code', 'subject_name'];
    $allowedSortOrders = ['asc', 'desc'];
    $sortClause = '';

    if (in_array($sort_by, $allowedSortFields) && in_array($sort_order, $allowedSortOrders)) {
        $sortClause = " ORDER BY subjects.$sort_by $sort_order";
    }

    echo "<form method='GET' style='margin-bottom: 20px;'>";
    echo "<label>Arrange Result</label> ";
    echo "<select name='sort_by'>";
    echo "<option value=''>N/A</option>";
    echo "<option value='subject_code'" . ($sort_by == 'subject_code' ? ' selected' : '') . ">Course Code</option>";
    echo "<option value='subject_name'" . ($sort_by == 'subject_name' ? ' selected' : '') . ">Course Name</option>";
    echo "</select> ";
    echo "<select name='sort_order'>";
    echo "<option value='asc'" . ($sort_order == 'asc' ? ' selected' : '') . ">Ascending</option>";
    echo "<option value='desc'" . ($sort_order == 'desc' ? ' selected' : '') . ">Descending</option>";
    echo "</select>";

    echo "<div style='margin-top: 10px;'>";
    echo "<label><input type='checkbox' name='hide_schedule'" . ($hide_schedule ? ' checked' : '') . "> Remove Schedule Info</label><br>";
    echo "<label><input type='checkbox' name='show_leclab'" . ($show_leclab ? ' checked' : '') . "> Show Lec/Lab Units</label><br>";
    echo "<label><input type='checkbox' name='hide_college'" . ($hide_college ? ' checked' : '') . "> Remove Offering College</label><br>";
    echo "<label><input type='checkbox' name='hide_capacity'" . ($hide_capacity ? ' checked' : '') . "> Remove Max/Min Capacity Info</label><br>";
    echo "<label><input type='checkbox' name='show_student_breakdown'" . ($show_student_breakdown ? ' checked' : '') . "> Show Regular/Irregular Student enrolled</label>";
    echo "</div>";
    echo "<input type='hidden' name='schoolyear1' value='" . htmlspecialchars((string)($startYear ?? '')) . "'>";
    echo "<input type='hidden' name='schoolyear2' value='" . htmlspecialchars((string)($endYear ?? '')) . "'>";
    echo "<input type='hidden' name='subject_status' value='" . htmlspecialchars($subject_status ?? '') . "'>";
    echo "<input type='hidden' name='department' value='" . htmlspecialchars($department ?? '') . "'>";
    echo "<input type='hidden' name='course' value='" . htmlspecialchars($course ?? '') . "'>";
    echo "<input type='hidden' name='term' value='" . htmlspecialchars($term ?? '') . "'>";

    echo "<br><button type='submit' class='bg-blue-900 text-white rounded-md p-2'>Apply</button>";
    echo "</form>";

    echo "<table style='width: 100%; border-collapse: collapse; margin-bottom: 10px; border: 1px solid #000;'>";
    echo "<tr><td style='padding: 8px; font-weight: bold; border: 1px solid #000;'>COURSE STATUS: OPEN</td><td style='border: 1px solid #000;'>TOTAL OPEN SUBJECTS: $openCount</td></tr>";
    echo "<tr><td style='padding: 8px; font-weight: bold; border: 1px solid #000;'>COURSE STATUS: CLOSED</td><td style='border: 1px solid #000;'>TOTAL CLOSED SUBJECTS: $closedCount</td></tr>";
    echo "<tr><td style='padding: 8px; font-weight: bold; border: 1px solid #000;'>COURSE STATUS: DISSOLVED</td><td style='border: 1px solid #000;'>TOTAL DISSOLVED SUBJECTS: $dissolvedCount</td>
<td style='padding: 8px; font-weight: bold; border: 1px solid #000;'>TOTAL LEC/LAB: <span id='total-leclab'>0/0</span></td>
    </tr>";
    echo "</table>";

    echo "<script>";
    echo "document.addEventListener('DOMContentLoaded', function () {";
    echo "  const rows = document.querySelectorAll('table tbody tr');";
    echo "  let lec = 0, lab = 0;";
    echo "  rows.forEach(row => {";
    echo "    const leclab = row.querySelector('td:nth-child(4)')?.innerText.split('/');";
    echo "    if (leclab?.length === 2) { lec += parseInt(leclab[0]) || 0; lab += parseInt(leclab[1]) || 0; }";
    echo "  });";
    echo "  const span = document.getElementById('total-leclab');";
    echo "  if (span) span.innerText = lec + '/' + lab;";
    echo "});";
    echo "</script>";


    $sql = "
        SELECT 
            program.id AS program_id,
            subjects.lec,
            subjects.lab,
            subjects.subject_code,
            subjects.subject_name,
            subjects.units,
            departments.department_code,
            courses.course_code,
            sections.id AS section_id,
            sections.section_code,
            sections.schedule_days AS section_schedule,
            sections.start AS section_start_time,
            sections.end AS section_end_time,
            rooms.building_code,
            sections.min_student,
            sections.max_student
        FROM program
        JOIN subjects ON program.subject_id = subjects.id
        JOIN departments ON subjects.department_id = departments.id
        JOIN courses ON program.course_id = courses.id
        LEFT JOIN sections 
            ON sections.course_id = program.course_id 
            AND sections.school_start_year = program.program_sy_start 
            AND sections.school_end_year = program.program_sy_end
        LEFT JOIN rooms ON rooms.id = program.room_id
        WHERE program.program_sy_start = ? AND program.program_sy_end = ?";

    $params = [$startYear, $endYear];
    $types = "ii";

    if (!empty($subject_status) && strtolower($subject_status) !== 'all') {
        $sql .= " AND LOWER(subjects.status) = ?";
        $params[] = strtolower($subject_status);
        $types .= "s";
    }

    if (!empty($department)) {
        $sql .= " AND departments.id = ?";
        $params[] = $departmentId;
        $types .= "i";
    }
    if (!empty($term)) {
        $sql .= " AND program.term = ?";
        $params[] = $term;
        $types .= "s";
    }

    if (!empty($course)) {
        $sql .= " AND courses.course_name = ?";
        $params[] = $course;
        $types .= "s";
    }

    $sql .= $sortClause;
    $stmt = $db->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();


    echo "<form method='POST' action='/capst/Statistics/Subjects/dissolve_selected.php'>";
    echo "<input type='hidden' name='schoolyear1' value='" . htmlspecialchars((string)($startYear ?? '')) . "'>";
    echo "<input type='hidden' name='schoolyear2' value='" . htmlspecialchars((string)($endYear ?? '')) . "'>";
    echo "<input type='hidden' name='subject_status' value='" . htmlspecialchars($subject_status ?? '') . "'>";
    echo "<input type='hidden' name='department' value='" . htmlspecialchars($department ?? '') . "'>";
    echo "<input type='hidden' name='course' value='" . htmlspecialchars($course ?? '') . "'>";
    if (isset($_GET['hide_schedule']) && $_GET['hide_schedule'] === 'on')
        echo "<input type='hidden' name='hide_schedule' value='on'>";
    if (isset($_GET['show_leclab']) && $_GET['show_leclab'] === 'on')
        echo "<input type='hidden' name='show_leclab' value='on'>";
    if (isset($_GET['hide_college']) && $_GET['hide_college'] === 'on')
        echo "<input type='hidden' name='hide_college' value='on'>";
    if (isset($_GET['hide_capacity']) && $_GET['hide_capacity'] === 'on')
        echo "<input type='hidden' name='hide_capacity' value='on'>";
    if (isset($_GET['show_student_breakdown']) && $_GET['show_student_breakdown'] === 'on')
        echo "<input type='hidden' name='show_student_breakdown' value='on'>";
    if (!empty($sort_by))
        echo "<input type='hidden' name='sort_by' value='" . htmlspecialchars($sort_by) . "'>";
    if (!empty($sort_order))
        echo "<input type='hidden' name='sort_order' value='" . htmlspecialchars($sort_order) . "'>";


    echo "<table border='1' style='width:100%; border-collapse: collapse; border: 1px solid #000;'>";
    echo "<thead><tr>";
    if (!$hide_college) echo "<th style='border: 1px solid #000;'>OFFERING COLLEGE</th>";
    echo "<th style='border: 1px solid #000;'>COURSE CODE (DESCRIPTION)</th>";
    echo "<th style='border: 1px solid #000;'>SECTION / SCHEDULE</th>";
    echo "<th style='border: 1px solid #000;'>LEC/LAB</th>";
    echo "<th style='border: 1px solid #000;'>Units</th>";
    if (!$hide_capacity) {
        echo "<th style='border: 1px solid #000;'>MIN CAPACITY</th>";
        echo "<th style='border: 1px solid #000;'>MAX CAPACITY (ROOM)</th>";
    }
    echo $show_student_breakdown ?"<th style='border: 1px solid #000;'>Total Stud. Enrolled</th>" : null;
    echo "<th style='border: 1px solid #000;'>Select</th>";
    echo "</tr></thead><tbody>";

    while ($row = $result->fetch_assoc()) {
        $totalLec += (int)$row['lec'];
        $totalLab += (int)$row['lab'];

        $sectionCode = htmlspecialchars($row['section_code'] ?? '---');
        $room = htmlspecialchars($row['building_code'] ?? 'T.B.A.');

        if ($hide_schedule) {
            $fullSchedule = "$sectionCode ::: ($room)";
        } else {
            $days = htmlspecialchars($row['section_schedule'] ?? '-');
            $time = (!empty($row['section_start_time']) && !empty($row['section_end_time']))
                ? date("g:i A", strtotime($row['section_start_time'])) . "–" . date("g:i A", strtotime($row['section_end_time']))
                : "T.B.A.";
            $fullSchedule = "$sectionCode ::: $days $time ($room)";
        }

        $totalEnrolled = $irregular = $regular = 0;
        if (!empty($row['section_id'])) {
            $enrolledStmt = $db->prepare("SELECT COUNT(*) AS total, SUM(CASE WHEN regular = 0 THEN 1 ELSE 0 END) AS irregular, SUM(CASE WHEN regular = 1 THEN 1 ELSE 0 END) AS regular FROM students WHERE section_id = ?");
            $enrolledStmt->bind_param("i", $row['section_id']);
            $enrolledStmt->execute();
            $enrolledData = $enrolledStmt->get_result()->fetch_assoc();
            $totalEnrolled = $enrolledData['total'] ?? 0;
            $irregular = $enrolledData['irregular'] ?? 0;
            $regular = $enrolledData['regular'] ?? 0;
            $enrolledStmt->close();
        }

        echo "<tr>";
        if (!$hide_college) echo "<td style='border: 1px solid #000;'>" . htmlspecialchars($row['department_code']) . "</td>";
        echo "<td style='border: 1px solid #000;'>" . htmlspecialchars($row['subject_code']) . " - " . htmlspecialchars($row['subject_name']) . "</td>";
        echo "<td style='border: 1px solid #000;'>$fullSchedule</td>";
        echo "<td style='border: 1px solid #000;'>" . ($show_leclab ? (int)$row['lec'] . "/" . (int)$row['lab'] : "—") . "</td>";
        echo "<td style='border: 1px solid #000;'>" . htmlspecialchars($row['units']) . "</td>";
        if (!$hide_capacity) {
            echo "<td style='border: 1px solid #000;'>" . htmlspecialchars($row['min_student']) . "</td>";
            echo "<td style='border: 1px solid #000;'>" . htmlspecialchars($row['max_student']) . "</td>";
        }
        // echo "<td style='border: 1px solid #000;'>$totalEnrolled<br>$irregular|$regular</td>";
        if($show_student_breakdown){
            echo "<td style='border: 1px solid #000; text-align: center;'>";
            echo "  <div style='font-weight: bold;'>$totalEnrolled</div>";
            echo "  <div style='display: flex; justify-content: space-between; font-size: 12px;'>";
            echo "    <div><b>regular</b><br>$regular</div>";
            echo "    <div><b>Irregular</b><br>$irregular</div>";
            echo "  </div>";
            echo "</td>";
        }
        echo "<td><input type='checkbox' name='select_program[]' value='{$row['program_id']}'></td>";
        echo "</tr>";
    }

    echo "</tbody></table>";

    echo "<div style='margin-top: 15px;'>";
    echo "<br><button type='submit' name='dissolve' class='bi bi-trash ml-4 bg-red-500 text-white rounded-md p-1'>Dissolve Selected</button><br>Remove dissolve course offering, room assignment, faculty load and students enrolled<br>";
    echo "</form>";

    if (!empty($department)) {
        $viewLink = "deletedCourses_page.php?" . http_build_query([
            'schoolyear1' => $startYear,
            'schoolyear2' => $endYear,
            'department' => $department
        ]);
        echo "<a href='$viewLink' class='ml-4 bg-yellow-500 text-white rounded-md p-1'>View All Dissolved Subjects</a><br> List of deleted courses";
    }
    echo "</div>";

    echo "</form>";
    $stmt->close();
} else {
    echo "<p>Please provide schoolyear1 and schoolyear2.</p>";
}
?>

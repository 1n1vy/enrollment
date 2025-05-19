<?php
$totalLec = $totalLab = 0;

foreach ($data['rows'] as $row) {
    $totalLec += (int)$row['lec'];
    $totalLab += (int)$row['lab'];

    $sectionTime = (!empty($row['start']) && !empty($row['end']))
        ? date("g:iA", strtotime($row['start'])) . "-" . date("g:iA", strtotime($row['end']))
        : "T.B.A.";

    $schedule = htmlspecialchars($row['section_code'] ?? '---') . " ::: "
        . htmlspecialchars($row['schedule_days'] ?? '-') . " "
        . $sectionTime . " (" . htmlspecialchars($row['building_code'] ?? 'T.B.A.') . ")";

    echo "<tr>";
    echo "<td>{$row['department_code']}</td>";
    echo "<td>{$row['subject_code']} - {$row['subject_name']}</td>";
    echo "<td>$schedule</td>";
    echo "<td>{$row['lec']}/{$row['lab']}</td>";
    echo "<td>{$row['units']}</td>";
    echo "<td>{$row['min_student']}</td>";
    echo "<td>{$row['max_student']}</td>";
    echo "<td><input type='checkbox' name='select_program[]' value='{$row['program_id']}'></td>";
    echo "</tr>";
}
?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('total-leclab').textContent = '<?= $totalLec ?>/<?= $totalLab ?>';
});
</script>
</tbody>
</table>
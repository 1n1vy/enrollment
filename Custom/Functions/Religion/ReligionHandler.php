<?php
// ReligionHandler.php
require_once '../../Custom/Handlers/connection.php';

$conn = new Connection();
$mysqli = $conn->getConnection();

$yearFrom = isset($_GET['year1']) ? (int)$_GET['year1'] : date('Y') - 1;
$yearTo   = isset($_GET['year2']) ? (int)$_GET['year2'] : date('Y');
$levelFilter = isset($_GET['yearlevel']) && is_numeric($_GET['yearlevel']) ? (int)$_GET['yearlevel'] : 0;

if ($yearFrom === $yearTo) {
    $yearTo = $yearFrom - 1;
}

$levelCondition = $levelFilter > 0 ? "AND s.education_level_id = $levelFilter" : '';
$yearLevelCondition = ''; // year_level column doesn't exist yet

$levelName = "All Levels";
if ($levelFilter > 0) {
    $levelQuery = $mysqli->query("SELECT level_name as name FROM education_levels WHERE id = $levelFilter LIMIT 1");
    if ($levelQuery && $row = $levelQuery->fetch_assoc()) {
        $levelName = $row['name'];
    } else {
        $levelName = "Unknown Level";
    }
}

$query = "
    SELECT 
        d.department_name,
        c.course_name,
        y.year,
        COUNT(CASE WHEN s.religion = 1 THEN 1 END) AS inc_count,
        COUNT(CASE WHEN s.religion = 0 THEN 1 END) AS non_inc_count
    FROM 
        departments d
    INNER JOIN 
        courses c ON c.department_id = d.id
    INNER JOIN 
        students s ON s.course_id = c.id
    INNER JOIN 
        year_terms y ON y.id = s.year_terms_id
    WHERE 
        y.year IN ($yearFrom, $yearTo)
        $levelCondition
        $yearLevelCondition
    GROUP BY 
        d.id, c.id, y.year
    ORDER BY 
        d.department_name, c.course_name, y.year
";

$result = $mysqli->query($query);

$data = [];
$totals = [
    $yearFrom => ['inc' => 0, 'non_inc' => 0, 'total' => 0],
    $yearTo   => ['inc' => 0, 'non_inc' => 0, 'total' => 0],
    'overall' => ['inc' => 0, 'non_inc' => 0, 'total' => 0],
];

while ($row = $result->fetch_assoc()) {
    $dept = $row['department_name'];
    $course = $row['course_name'];
    $year = $row['year'];
    $inc = (int)$row['inc_count'];
    $non_inc = (int)$row['non_inc_count'];
    $total = $inc + $non_inc;

    $data[$dept][$course][$year] = [
        'inc' => $inc,
        'non_inc' => $non_inc,
        'total' => $total,
    ];

    $totals[$year]['inc'] += $inc;
    $totals[$year]['non_inc'] += $non_inc;
    $totals[$year]['total'] += $total;

    $totals['overall']['inc'] += $inc;
    $totals['overall']['non_inc'] += $non_inc;
    $totals['overall']['total'] += $total;
}
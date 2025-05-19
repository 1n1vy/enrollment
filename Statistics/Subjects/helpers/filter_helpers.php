<?php
function getFilterValues() {
    return [
        'startYear' => isset($_GET['schoolyear1']) ? (int)$_GET['schoolyear1'] : null,
        'endYear' => isset($_GET['schoolyear2']) ? (int)$_GET['schoolyear2'] : null,
        'subject_status' => isset($_GET['subject_status']) ? trim($_GET['subject_status']) : null,
        'department' => isset($_GET['department']) ? trim($_GET['department']) : null,
        'course' => isset($_GET['course']) ? trim($_GET['course']) : null,
        'sort_by' => isset($_GET['sort_by']) ? trim($_GET['sort_by']) : '',
        'sort_order' => isset($_GET['sort_order']) ? strtolower(trim($_GET['sort_order'])) : 'asc',
        'hide_schedule' => isset($_GET['hide_schedule']),
        'show_leclab' => isset($_GET['show_leclab']),
        'hide_college' => isset($_GET['hide_college']),
        'hide_capacity' => isset($_GET['hide_capacity']),
        'show_student_breakdown' => isset($_GET['show_student_breakdown']),
    ];
}
?>
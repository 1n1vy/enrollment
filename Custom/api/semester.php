<?php
header('Content-Type: application/json');
require_once '../classes/Semester.php';

$education_level = new Semester();
echo json_encode($education_level->getApiSemester());
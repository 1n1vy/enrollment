<?php
header('Content-Type: application/json');
require_once '../classes/EducationLevel.php';

$education_level = new EducationLevel();
echo json_encode($education_level->getApiEducationlevel());
<?php
header('Content-Type: application/json');
require_once '../classes/Departments.php';

$department = new Departments();
echo json_encode($department->getApiDepartments());
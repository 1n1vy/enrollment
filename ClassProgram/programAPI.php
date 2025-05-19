<?php
require __DIR__ . '/../includes/db_connection.php';
header('Content-Type: application/json');
ob_clean();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$action = $_REQUEST['action'] ?? '';

switch ($action) {
    case 'getFacultyInfo':
        getFacultyInfo($conn);
        break;
    case 'getCanTeach':
        getCanTeach($conn);
        break;
    case 'getFacultyLoad':
        getFacultyLoad($conn);
        break;
    case 'getDepartments':
        getDepartments($conn);
        break;
    case 'getCourses':
        getCourses($conn);
        break;
    case 'getProgram':
        getProgram($conn);
        break;
    case 'getProfessorSuggestions':
        getProfessorSuggestions($conn);
        break;
    case 'getSubjects':
        getSubjects($conn);
    case 'getSubjects2':
        getSubjects2($conn);
        break;
    case 'getRooms':
        getRooms($conn);
        break;
        
    case 'fetchRooms':
        fetchRooms($conn);
        break;
    case 'getSubjectDetails':
        getSubjectDetails($conn);
        break;
    case 'getSections':
        getSections($conn);
        break;
    case 'getCurriculum':
        getCurriculum($conn);
        break;
    case 'getMajor':
        getMajor($conn);
        break;
    case 'insertProgram':
        insertProgram($conn);
        break;
    case 'updateProgram':
        updateProgram($conn);
        break;
    case 'retrieveRecord':
        insertProgram($conn);
        break;
    case 'getFilteredPrograms':
        getFilteredPrograms($conn);
        break;
    case 'getFilteredPrograms2':
        getFilteredPrograms2($conn);
        break;
    case 'deleteProgram':
        deleteProgram($conn);
        break;
    case 'getProgrambyID':
        getProgrambyID($conn);
        break;
    case 'getEnrolled':
        fetchEnrolled($conn);
        break;
        
    default:
        echo json_encode(['error' => 'Invalid action.']);
        break;
}

function fetchEnrolled($conn) {
    ob_clean();
    header('Content-Type: application/json');

    $query = "
        SELECT 
            sc.*, p.*, s.*, sub.*, r.*,
            sc.term AS termz
        FROM student_course sc
        LEFT JOIN students s ON s.id = sc.student_id
        LEFT JOIN programs p ON p.program_id = sc.program_id
        LEFT JOIN subjects sub ON sub.id = p.subject_id
        LEFT JOIN rooms r ON r.id = p.room_id
    ";

    $result = $conn->query($query);

    if ($result) {
        $rooms = [];
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
        echo json_encode(['data' => $rooms]);
    } else {
        echo json_encode(['data' => []]);
    }
}


function fetchRooms($conn) {
    ob_clean();
    header('Content-Type: application/json');

    $query = "
        SELECT 
            programs.section, 
            programs.schedule_day AS days, 
            programs.schedule_time AS time, 
            programs.term, 
            programs.school_year, 
            programs.subject_id, 
            subjects.subject_name AS subject, 
            rooms.building_code, 
            rooms.floor, 
            rooms.room_number, 
            rooms.room_type
        FROM programs
        LEFT JOIN rooms ON rooms.id = programs.room_id
        LEFT JOIN subjects ON subjects.id = programs.subject_id;
    ";

    $result = $conn->query($query);

    if ($result) {
        $rooms = [];
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
        echo json_encode(['data' => $rooms]);
    } else {
        echo json_encode(['data' => []]);
    }
}

function getProgrambyID($conn) {

    $program_id = $_GET['program_id'];

    $query = "
        SELECT *
        FROM programs p
        WHERE p.program_id = ?
    ";

    executeAndReturn($conn, $query, 'i', [$program_id]);

}


function getRooms($conn) {

    $compo = $_GET['subjectCompo'];
    $dept = $_GET['dept'];

    $query = "
        SELECT *
        FROM rooms
        WHERE room_type = ? AND department_id = ?
    ";

    executeAndReturn($conn, $query, 'si', [$compo, $dept]);

}
function deleteProgram($conn) {

    $program_id = $_POST['program_id'] ?? 0;

    $query = "DELETE FROM programs WHERE program_id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('i', $program_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Program deleted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error deleting program.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error preparing the SQL statement.']);
    }

}
function getFilteredPrograms2($conn) {
    
    $department_id = $_GET['department_id'] ?? 0;
    $school_year = $_GET['school_year'] ?? 0;

    $query = "
            SELECT p.*,
                d.department_name, 
                c.course_name, 
                s.*
            FROM programs p
            JOIN departments d ON p.department_id = d.id
            JOIN courses c ON p.course_id = c.id
            JOIN subjects s ON p.subject_id = s.id
            WHERE p.department_id = ? AND p.school_year = ?
        ";

    executeAndReturn($conn, $query, 'is', [$department_id, $school_year]);

}

function getFilteredPrograms($conn) {
    
    $department_id = $_GET['department_id'] ?? 0;
    $course_id = $_GET['course_id'] ?? 0;
    $subject_id = $_GET['subject_id'] ?? 0;

    $query = "
            SELECT p.*,
                d.department_name, 
                c.course_name, 
                s.*
            FROM programs p
            JOIN departments d ON p.department_id = d.id
            JOIN courses c ON p.course_id = c.id
            JOIN subjects s ON p.subject_id = s.id
            WHERE p.department_id = ? AND p.course_id = ? AND p.subject_id = ?
        ";

    executeAndReturn($conn, $query, 'iii', [$department_id, $course_id, $subject_id]);

}


function updateProgram($conn) {
    $department_id     = $_POST['progDept'];
    $course_id         = $_POST['progCourses'];
    $course_program    = $_POST['progProgram'];
    $subject_id        = $_POST['subject_autocomp'];
    $major_id          = $_POST['progMajor'];
    $curriculum_id     = $_POST['progCurr'];
    $section_id        = $_POST['progSection'];
    $year_level        = $_POST['progYear'];
    $term              = $_POST['progTerm'];
    $acad_start        = $_POST['progAcadstart'];
    $acad_end          = $_POST['progAcadend'];
    $school_year       = $acad_start . '-' . $acad_end;
    $subject_type      = $_POST['subjectofferingtype'];
    $subject_component = $_POST['subcomponent'];
    $schedule_day      = $_POST['schedulewk'];
    $room              = $_POST['room'];
    $time_from         = $_POST['time_input1'];
    $time_to           = $_POST['time_input2'];
    $time_from_12hr = date('h:i A', strtotime($time_from));
    $time_to_12hr = date('h:i A', strtotime($time_to));
    $schedule_time = $time_from_12hr . ' - ' . $time_to_12hr;
    $is_international  = isset($_POST['international']) ? 0 : 1;

    $program_id        = $_POST['program_id'];

    $sql = "UPDATE programs SET
                department_id = ?, 
                course_id = ?, 
                course_program = ?, 
                subject_id = ?, 
                major_id = ?, 
                curriculum_id = ?, 
                section_id = ?, 
                year_level = ?, 
                term = ?, 
                school_year = ?, 
                subject_type = ?, 
                subject_component = ?, 
                schedule_day = ?, 
                schedule_time = ?, 
                is_international = ?,
                room_id = ?
            WHERE program_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('iiissssssssssssii', 
            $department_id, 
            $course_id, 
            $course_program, 
            $subject_id, 
            $major_id, 
            $curriculum_id, 
            $section_id, 
            $year_level, 
            $term, 
            $school_year, 
            $subject_type, 
            $subject_component, 
            $schedule_day, 
            $schedule_time, 
            $is_international,
            $room,
            $program_id, 
        );

        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Substitution added successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error adding substitution.']);
        }

        $stmt->close();
    } else {
        echo "Error preparing the SQL statement: " . $conn->error;
    }
}

function insertProgram($conn) {

    $department_id     = $_POST['progDept'];
    $course_id         = $_POST['progCourses'];
    $course_program    = $_POST['progProgram'];
    $subject_id        = $_POST['subject_autocomp'];
    $major_id          = $_POST['progMajor'];
    $curriculum_id     = $_POST['progCurr'];
    $section_id        = $_POST['progSection'];
    $year_level        = $_POST['progYear'];
    $term              = $_POST['progTerm'];
    $acad_start        = $_POST['progAcadstart'];
    $acad_end          = $_POST['progAcadend'];
    $subjectcomp          = $_POST['subjectcompo'];
    $school_year       = $acad_start . '-' . $acad_end;
    $schedule_day      = strtoupper($_POST['schedulewk']);
    $time_from         = $_POST['time_input1'];
    $time_to           = $_POST['time_input2'];
    $room              = $_POST['room'];
    $time_from_12hr = date('h:i A', strtotime($time_from));
    $time_to_12hr = date('h:i A', strtotime($time_to));
    $schedule_time = $time_from_12hr . ' - ' . $time_to_12hr;
    $is_international  = isset($_POST['international']) ? 0 : 1;

    // $check = "
    //         SELECT * FROM programs 
    //         WHERE room_id = ? 
    //         AND FIND_IN_SET(?, schedule_day) > 0 
    //         AND schedule_time = ?
    //     ";

    // if ($stmt = $conn->prepare($check)) {
    //     $stmt->bind_param('iss', $room, $schedule_day, $schedule_time);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     if ($result->num_rows > 0) {
    //         echo json_encode(['status' => 'error', 'message' => 'Schedule already exists!']);
    //         return;
    //     } else {

    //         $sql = "INSERT INTO programs (
    //                     department_id, course_id, course_program, subject_id, major_id, curriculum_id,
    //                     section, year_level, term, school_year, subject_type, subject_component,
    //                     schedule_day, schedule_time, is_international, room_id
    //                 ) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    //         if ($stmt = $conn->prepare($sql)) {

    //             $stmt->bind_param('iiissssssssssssi', 

    //                 $department_id, 
    //                 $course_id, 
    //                 $course_program, 
    //                 $subject_id, 
    //                 $major_id, 
    //                 $curriculum_id, 
    //                 $section_id, 
    //                 $year_level, 
    //                 $term, 
    //                 $school_year, 
    //                 $subject_type,
    //                 $subjectcomp,  
    //                 $schedule_day, 
    //                 $schedule_time, 
    //                 $is_international,
    //                 $room,

    //             );
                
    //             if ($stmt->execute()) {
    //                 echo json_encode(['status' => 'success', 'message' => 'Substitution added successfully!']);
    //             } else {
    //                 echo json_encode(['status' => 'error', 'message' => 'Error adding substitution.']);
    //             }

    //             $stmt->close();
    //         } else {
    //             echo "Error preparing the SQL statement: " . $conn->error;
    //         }

    //     }
    // } else {
    //     echo json_encode(['status' => 'error', 'message' => 'Error preparing the SQL statement.']);
    //     return;
    // }

    $days = explode(',', $schedule_day);

    foreach ($days as $day) {
        $check = "
            SELECT * FROM programs 
            WHERE room_id = ? 
            AND FIND_IN_SET(?, schedule_day) > 0 
            AND schedule_time = ?
        ";

        if ($stmt = $conn->prepare($check)) {
            $stmt->bind_param('iss', $room, $day, $schedule_time);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                // Conflict detected
                ob_clean(); // Clear any previous output
                echo json_encode(['status' => 'error', 'message' => "Schedule conflict on day $day!"]);
                $stmt->close();
                return;
            }
            $stmt->close();
        } else {
            ob_clean();
            echo json_encode(['status' => 'error', 'message' => 'Error preparing the SQL statement.']);
            return;
        }
    }

    // No conflicts, proceed to insert
    $sql = "INSERT INTO programs (
        department_id, course_id, course_program, subject_id, major_id, curriculum_id,
        section, year_level, term, school_year, subject_component,
        schedule_day, schedule_time, is_international, room_id
    ) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('iiisssssssssssi', 
            $department_id, 
            $course_id, 
            $course_program, 
            $subject_id, 
            $major_id, 
            $curriculum_id, 
            $section_id, 
            $year_level, 
            $term, 
            $school_year, 
            $subjectcomp,  
            $schedule_day, 
            $schedule_time, 
            $is_international,
            $room
        );

        if ($stmt->execute()) {
            ob_clean();
            echo json_encode(['status' => 'success', 'message' => 'Program added successfully!']);
        } else {
            ob_clean();
            echo json_encode(['status' => 'error', 'message' => 'Error executing insert statement.']);
        }

        $stmt->close();
    } else {
        ob_clean();
        echo json_encode(['status' => 'error', 'message' => 'Error preparing the insert statement.']);
    }
    
}



function getSections($conn)
{
    
    $course_id = $_GET['course_id'] ?? 0;

    $query = "SELECT * FROM sections WHERE course_id = ? ORDER BY section_name ASC";

    executeAndReturn($conn, $query, 'i', [$course_id]);
    
}

function getSubjects($conn) {

    $search = $_GET['search'] ?? '';
    $term = $_GET['term'] ?? '';
    $course = $_GET['course'] ?? '';

    $query = "
        SELECT *
        FROM subjects
        WHERE (subject_code LIKE ? OR subject_name LIKE ?) AND term = ? AND course_id = ?
        ORDER BY subject_name ASC
    ";

    $search_param = '%' . $search . '%';
    executeAndReturn($conn, $query, 'sssi', [$search_param, $search_param, $term, $course]);
}


function getSubjects2($conn) {

    $search = $_GET['search'] ?? '';
    $term = $_GET['term'] ?? '';
    $course = $_GET['course'] ?? '';

    $query = "
        SELECT *
        FROM subjects
        WHERE (subject_code LIKE ? OR subject_name LIKE ?) and term = ? AND course_id = ?
        ORDER BY subject_name ASC
    ";

    $search_param = '%' . $search . '%';
    executeAndReturn($conn, $query, 'sssi', [$search_param, $search_param, $term, $course]);
}

function getMajor($conn) {

    $course_id = $_GET['course_id'] ?? 0;

    $query = "SELECT id, major_name FROM majors WHERE course_id = ? ORDER BY major_name ASC";

    executeAndReturn($conn, $query, 'i', [$course_id]);

}

function getCurriculum($conn) {

    $query = "SELECT * FROM curriculum_years ORDER BY id ASC";

    executeAndReturn($conn, $query,'', []);

}

function getSubjectDetails($conn) {
    $subject_code = $_GET['subject_code'] ?? '';

    $query = "SELECT subject_code, subject_name, units FROM subjects WHERE subject_code = ?";
    executeAndReturn($conn, $query, 's', [$subject_code]);
}

function getDepartments($conn) {
    $query = "SELECT id, department_name FROM departments ORDER BY department_name ASC";
    executeAndReturn($conn, $query, '', []);
}
function getCourses($conn) {

    $collegeID = $_GET['id'] ?? 0;

    $query = "SELECT * FROM courses ORDER BY course_name ASC";

    executeAndReturn($conn, $query, '', []);
}

function getProgram($conn) {

    $query = "SELECT id, level_name FROM education_levels ORDER BY id ASC";

    executeAndReturn($conn, $query);
}
function executeAndReturn($conn, $query, $param_types = '', $params = []) {
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        echo json_encode(['error' => 'Query preparation failed: ' . $conn->error]);
        return;
    }

    if (!empty($params)) {
        $stmt->bind_param($param_types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    ob_clean();
    echo json_encode($data);
    $stmt->close();
}
?>

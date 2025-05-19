<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'enrollment_1';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = $_POST['table'];

    switch ($table) {
        case 'buildings':
            $code = $_POST['code'] ?? ''; // The code might be empty for a new record
            $building_code = $_POST['building_code'];
            $building_name = $_POST['building_name'];
            $description = $_POST['description'];

            // Check if the code is empty (new entry)
            if ($code !== '') {
                // Update existing building
                $stmt = $conn->prepare("UPDATE buildings SET code=?, name=?, description=? WHERE code=?");
                $stmt->bind_param('ssss', $building_code, $building_name, $description, $code);
            } else {
                // Insert new building
                $stmt = $conn->prepare("INSERT INTO buildings (code, name, description) VALUES (?, ?, ?)");
                $stmt->bind_param('sss', $building_code, $building_name, $description);
            }
            break;

        case 'rooms':
            $id = $_POST['id'] ?? '';
            $building_code = $_POST['building_code'];
            $floor = $_POST['floor'];
            $room_number = $_POST['room_number'];
            $room_capacity = $_POST['room_capacity'];
            $room_type = $_POST['room_type'];
            $status = $_POST['status'];
            $last_inspection_date = $_POST['last_inspection_date'];
            $no_subject = isset($_POST['no_subject']) ? 1 : 0;
            $room_conflict = isset($_POST['room_conflict']) ? 1 : 0;
            $description = $_POST['description'];

            if ($id !== '') {
                $stmt = $conn->prepare("UPDATE rooms SET building_code=?, floor=?, room_number=?, room_capacity=?, room_type=?, status=?, last_inspection_date=?, no_subject=?, room_conflict=?, description=? WHERE id=?");
                $stmt->bind_param('sssisssiisi', $building_code, $floor, $room_number, $room_capacity, $room_type, $status, $last_inspection_date, $no_subject, $room_conflict, $description, $id);
            } else {
                $stmt = $conn->prepare("INSERT INTO rooms (building_code, floor, room_number, room_capacity, room_type, status, last_inspection_date, no_subject, room_conflict, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('sssisssiis', $building_code, $floor, $room_number, $room_capacity, $room_type, $status, $last_inspection_date, $no_subject, $room_conflict, $description);
            }
            break;

        case 'departments':
            $id = $_POST['id'] ?? '';
            $department_name = $_POST['department_name'];
            $department_code = $_POST['department_code'];
            $description = $_POST['description'];

            if ($id !== '') {
                $stmt = $conn->prepare("UPDATE departments SET department_name=?, department_code=?, description=? WHERE id=?");
                $stmt->bind_param('sssi', $department_name, $department_code, $description, $id);
            } else {
                $stmt = $conn->prepare("INSERT INTO departments (department_name, department_code, description) VALUES (?, ?, ?)");
                $stmt->bind_param('sss', $department_name, $department_code, $description);
            }
            break;

        case 'education_levels':
            $id = $_POST['id'] ?? '';
            $level_name = $_POST['level_name'];
            $description = $_POST['description'];

            if ($id !== '') {
                $stmt = $conn->prepare("UPDATE education_levels SET level_name=?, description=? WHERE id=?");
                $stmt->bind_param('ssi', $level_name, $description, $id);
            } else {
                $stmt = $conn->prepare("INSERT INTO education_levels (level_name, description) VALUES (?, ?)");
                $stmt->bind_param('ss', $level_name, $description);
            }
            break;

        case 'majors':
            $id = $_POST['id'] ?? '';
            $education_level_id = $_POST['education_level_id'];
            $major_name = $_POST['major_name'];
            $description = $_POST['description'];

            if ($id !== '') {
                $stmt = $conn->prepare("UPDATE majors SET education_level_id=?, major_name=?, description=? WHERE id=?");
                $stmt->bind_param('issi', $education_level_id, $major_name, $description, $id);
            } else {
                $stmt = $conn->prepare("INSERT INTO majors (education_level_id, major_name, description) VALUES (?, ?, ?)");
                $stmt->bind_param('iss', $education_level_id, $major_name, $description);
            }
            break;
        case 'roles':
            $id = $_POST['id'] ?? '';
            $role_name = $_POST['role_name'];
            $description = $_POST['description'];

            if ($id !== '') {
                $stmt = $conn->prepare("UPDATE roles SET role_name=?, description=? WHERE id=?");
                $stmt->bind_param('ssi', $role_name, $description, $id);
            } else {
                $stmt = $conn->prepare("INSERT INTO roles (role_name, description) VALUES (?, ?)");
                $stmt->bind_param('ss', $role_name, $description);
            }
            break;
        case 'specificRole':
            $id = $_POST['id'] ?? '';
            $role_id = $_POST['role_id'];
            $specific_name = $_POST['specific_name'];
            $description = $_POST['description'];

            if ($id !== '') {
                $stmt = $conn->prepare("UPDATE specific_roles SET role_id=?, specific_name=?, description=? WHERE id=?");
                $stmt->bind_param('issi', $role_id, $specific_name, $description, $id);
            } else {
                $stmt = $conn->prepare("INSERT INTO specific_roles (role_id, specific_name, description) VALUES (?, ?, ?)");
                $stmt->bind_param('iss', $role_id, $specific_name, $description);
            }
            break;
        default:
            echo "<p>Invalid table selection.</p>";
            exit;
    }

    if ($stmt->execute()) {
        echo "<p>Record saved successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
        echo "<p>SQL: " . $stmt->error_list[0]['sqlstate'] . "</p>";
    }


    $stmt->close();
}

if (isset($_GET['delete']) && isset($_GET['table'])) {
    $id = (int) $_GET['delete']; // Ensure the id is treated as an integer
    $table = $_GET['table']; // Determine the table to delete from
    $allowedTables = ['buildings', 'rooms', 'departments', 'education_levels', 'majors', 'roles', 'specific_roles']; // List of valid tables

    if (in_array($table, $allowedTables)) {
        $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            header("Location: " . strtok($_SERVER["REQUEST_URI"], '?')); // Redirect to the same page without query string
            exit;
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Invalid table specified.</p>";
    }
}


// Fetch data for dropdowns
$buildings = $conn->query("SELECT * FROM buildings");
$rooms = $conn->query("SELECT * FROM rooms");
$departments = $conn->query("SELECT * FROM departments");
$education_levels = $conn->query("SELECT * FROM education_levels");
$majors = $conn->query("SELECT * FROM majors");
$roles = $conn->query("SELECT * FROM roles");
$specific_roles = $conn->query("SELECT * FROM specific_roles");

function fetchOptions($table, $valueField, $displayField, $conn, $selectedValue = null)
{
    $options = "";
    $query = "SELECT $valueField, $displayField FROM $table";
    $result = $conn->query($query);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $isSelected = ($row[$valueField] == $selectedValue) ? "selected" : "";
            $options .= "<option value='{$row[$valueField]}' $isSelected>{$row[$displayField]}</option>";
        }
    } else {
        echo "Error: " . $conn->error;
    }

    return $options;
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Forms</title>
</head>

<body>
    <h1>Management Forms</h1>

    <!-- Form for Buildings -->
    <div>
        <h2>Building Management</h2>
        <form method="POST">
            <input type="hidden" name="table" value="buildings">
            <input type="hidden" id="code" name="code">
            <label for="building_code">Building Code:</label>
            <input type="text" id="building_code" name="building_code" required><br>

            <label for="building_name">Building Name:</label>
            <input type="text" id="building_name" name="building_name" required><br>

            <label for="description">Description:</label>
            <textarea id="description" name="description"></textarea><br>

            <button type="submit">Save</button>
        </form>


        <!-- Display table of buildings -->
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $buildings->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['code']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td>
                            <button onclick="editRecordBuilding(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                            <a href="?delete=<?php echo $row['id']; ?>&table=buildings" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <hr>
    <!-- Form for Rooms -->
    <div>
        <h2>Rooms Management</h2>
        <form method="POST">
            <input type="hidden" name="table" value="rooms">
            <input type="hidden" id="roomID" name="id">
            <label for="buildingCodeRoom">Building Code:</label>
            <select id="roomBuildingCode1" name="building_code">
                <option value="">Select Building</option>
                <?= fetchOptions('buildings', 'code', 'name', $conn, isset($row['building_code']) ? $row['building_code'] : null); ?>
            </select>
            <br>

            <label for="floor">Floor:</label>
            <input type="text" id="roomFloor" name="floor" required><br>

            <label for="roomNumber">Room Number:</label>
            <input type="text" id="roomNumber" name="room_number" required><br>

            <label for="roomCapacity">Room Capacity</label>
            <input type="number" id="roomCapacity" name="room_capacity" required><br>

            <label for="roomType">Room Type</label>
            <input type="text" id="roomType" name="room_type" required><br>

            <label for="roomStatus">Status</label>
            <input type="text" id="roomStatus" name="status"><br>

            <label for="lastInspection">Last Inspection Date</label>
            <input type="date" id="lastInspection" name="last_inspection_date"><br>

            <input type="checkbox" id="noSubject" name="no_subject">
            <label for="noSubject">No Subject</label><br>

            <input type="checkbox" id="roomConflict" name="room_conflict">
            <label for="roomConflict">Room Conflict</label><br>

            <label for="roomDescription">Description:</label>
            <textarea id="roomDescription" name="description"></textarea><br>

            <button type="submit">Save</button>
        </form>

        <!-- Display table of buildings -->
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Building</th>
                    <th>Floor</th>
                    <th>Room Number</th>
                    <th>Room Capacity</th>
                    <th>Room Type</th>
                    <th>Status</th>
                    <th>Last Inspection Date</th>
                    <th>No Subject</th>
                    <th>Room Conflict</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $rooms->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['building_code']; ?></td>
                        <td><?php echo $row['floor']; ?></td>
                        <td><?php echo $row['room_number']; ?></td>
                        <td><?php echo $row['room_capacity']; ?></td>
                        <td><?php echo $row['room_type']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['last_inspection_date']; ?></td>
                        <td><?php echo $row['no_subject']; ?></td>
                        <td><?php echo $row['room_conflict']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td>
                            <button onclick="editRecordRoom(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                            <a href="?delete=<?php echo $row['id']; ?>&table=rooms" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <hr>
    <!-- Form for Department -->
    <div>
        <h2>Department Management</h2>
        <form method="POST">
            <input type="hidden" name="table" value="departments">
            <input type="hidden" name="id" id="departmentID">
            <label for="departmentName">Department Name</label>
            <input type="text" id="departmentName" name="department_name" required><br>

            <label for="departmentCode">Department Code</label>
            <input type="text" id="departmentCode" name="department_code" required><br>

            <label for="departmentDescription">Description</label>
            <textarea id="departmentDescription" name="description" rows="3"></textarea><br>

            <button type="submit">Save</button>
        </form>

        <!-- Display table of buildings -->
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Department Name</th>
                    <th>Department Code</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $departments->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['department_name']; ?></td>
                        <td><?php echo $row['department_code']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td>
                            <button onclick="editRecordDepartment(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                            <a href="?delete=<?php echo $row['id']; ?>&table=departments" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <hr>
    <!-- Form for Education Level-->
    <div>
        <h2>Education Level Management</h2>
        <form method="POST">
            <input type="hidden" name="table" value="education_levels">
            <input type="hidden" id="educationLevelID" name="id">
            <label for="levelName">Level Name</label>
            <input type="text" id="levelName" name="level_name" required>

            <label for="levelDescription">Description</label>
            <textarea id="levelDescription" name="description" rows="3"></textarea>

            <button type="submit">Save</button>
        </form>

        <!-- Display table of buildings -->
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Level Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $education_levels->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['level_name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td>
                            <button onclick="editRecordEducationLevel(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                            <a href="?delete=<?php echo $row['id']; ?>&table=education_levels" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <hr>
    <!-- Form for Major -->
    <div>
        <h2>Major Management</h2>
        <form method="POST">
            <input type="hidden" name="table" value="majors">
            <input type="" id="majorID" name="id">
            <label for="educationLevel">Education Level</label>
            <input type="text" id="testMajor">
            <select id="educationLevelIDRoom" name="education_level_id">
                <option value="">Select Level</option>
                <?= fetchOptions('education_levels', 'id', 'level_name', $conn, isset($row['education_level_id']) ? $row['education_level_id'] : null); ?>
            </select>

            <label for="majorName">Major Name</label>
            <input type="text" id="majorName" name="major_name" required>

            <label for="majorDescription">Description</label>
            <textarea id="majorDescription" name="description" rows="3"></textarea>

            <button type="submit">Save</button>
        </form>

        <!-- Display table of buildings -->
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Education Level ID</th>
                    <th>Major Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $majors->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['education_level_id']; ?></td>
                        <td><?php echo $row['major_name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td>
                            <button onclick="editRecordMajor(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                            <a href="?delete=<?php echo $row['id']; ?>&table=majors" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <hr>
    <!-- Form for Role -->
    <div>
        <h2>Role Management</h2>
        <form method="POST">
            <input type="hidden" name="table" value="roles">
            <input type="hidden" id="roleID" name="id">
            <label for="roleName">Role Name</label>
            <input type="text" id="roleName" name="role_name" required>

            <label for="roleDescription">Description</label>
            <textarea id="roleDescription" name="description" rows="3"></textarea>

            <button type="submit">Save</button>
        </form>


        <!-- Display table of buildings -->
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Role Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $roles->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['role_name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td>
                            <button onclick="editRecordRole(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                            <a href="?delete=<?php echo $row['id']; ?>&table=roles" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <hr>
    <!-- Form for Specific Role  -->
    <div>
        <h2>Specific Role Management</h2>
        <form method="POST">
            <input type="hidden" name="table" value="specificRole">
            <input type="hidden" id="specificRoleID" name="id">

            <label for="role">Role</label>
            <select id="role" name="role_id" required>
                <option value="">Select Role</option>
                <?= fetchOptions('roles', 'id', 'role_name', $conn); ?>
            </select>

            <label for="specificName">Specific Role Name</label>
            <input type="text" id="specificName" name="specific_name" required>

            <label for="specificDescription">Description</label>
            <textarea id="specificDescription" name="description" rows="3"></textarea>

            <button type="submit">Save</button>
        </form>


        <!-- Display table of buildings -->
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Role ID</th>
                    <th>Specific Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $specific_roles->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['role_id']; ?></td>
                        <td><?php echo $row['specific_name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td>
                            <button onclick="editRecordSpecificRole(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                            <a href="?delete=<?php echo $row['id']; ?>&table=specific_roles" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        function editRecordBuilding(record) {
            // Populate the hidden code field and other form fields
            document.getElementById('code').value = record.code; // Hidden field for code
            document.getElementById('building_code').value = record.code; // Editable field for code
            document.getElementById('building_name').value = record.name;
            document.getElementById('description').value = record.description;
        }

        function editRecordRoom(record) {
            document.getElementById('roomID').value = record.id;
            document.getElementById('roomBuildingCode1').value = record.building_code;
            document.getElementById('roomFloor').value = record.floor;
            document.getElementById('roomNumber').value = record.room_number;
            document.getElementById('roomCapacity').value = record.room_capacity;
            document.getElementById('roomType').value = record.room_type;
            document.getElementById('roomStatus').value = record.status;
            document.getElementById('lastInspection').value = record.last_inspection_date;
            document.getElementById('noSubject').checked = record.no_subject == 1;
            document.getElementById('roomConflict').checked = record.room_conflict == 1;
            document.getElementById('roomDescription').value = record.description;
        }

        function editRecordDepartment(record) {
            document.getElementById('departmentID').value = record.id;
            document.getElementById('departmentName').value = record.department_name;
            document.getElementById('departmentCode').value = record.department_code;
            document.getElementById('departmentDescription').value = record.description;
        }

        function editRecordEducationLevel(record) {
            document.getElementById('educationLevelID').value = record.id;
            document.getElementById('levelName').value = record.level_name;
            document.getElementById('levelDescription').value = record.description;
        }

        function editRecordMajor(record) {
            document.getElementById('majorID').value = record.id; // Set the ID
            document.getElementById('educationLevelIDRoom').value = record.education_level_id; // Set the selected value for dropdown
            document.getElementById('majorName').value = record.major_name; // Set the major name
            document.getElementById('majorDescription').value = record.description; // Set the description
        }

        function editRecordRole(record) {
            document.getElementById('roleID').value = record.id;
            document.getElementById('roleName').value = record.role_name;
            document.getElementById('roleDescription').value = record.description;
        }

        function editRecordSpecificRole(record) {
            document.getElementById('specificRoleID').value = record.id;
            document.getElementById('role').value = record.role_id;
            document.getElementById('specificName').value = record.specific_name;
            document.getElementById('specificDescription').value = record.description;
        }
    </script>
</body>

</html>
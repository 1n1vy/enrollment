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
    $subject_code = $_POST['subject_code'];
    $subject_name = $_POST['subject_name'];
    $department_code = $_POST['department_code'];
    $units = $_POST['units'];
    $is_requested_subject = isset($_POST['is_requested_subject']) ? 1 : 0;
    $description = $_POST['description'];
    $created_by = $_POST['created_by'];
    $status = $_POST['status'];

    if (isset($_POST['id']) && $_POST['id'] !== '') {
        // Update record
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE subjects SET subject_code=?, subject_name=?, department_code=?, units=?, is_requested_subject=?, description=?, created_by=?, status=? WHERE id=?");
        $stmt->bind_param('sssiisssi', $subject_code, $subject_name, $department_code, $units, $is_requested_subject, $description, $created_by, $status, $id);
    } else {
        // Insert new record
        $stmt = $conn->prepare("INSERT INTO subjects (subject_code, subject_name, department_code, units, is_requested_subject, description, created_by, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssiisss', $subject_code, $subject_name, $department_code, $units, $is_requested_subject, $description, $created_by, $status);
    }

    if ($stmt->execute()) {
        echo "<p>Record saved successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Delete record
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM students WHERE id=?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo "<p>Record deleted successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Fetch all records
$result = $conn->query("SELECT * FROM subjects");
$department = $conn->query("SELECT * FROM departments");

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
    <title>Subject Form</title>

</head>

<body>
    <h2>Subject Form</h2>
    <form method="POST">
        <input type="hidden" id="id" name="id">

        <label for="subject_code">Subject Code</label>
        <input type="text" id="subject_code" name="subject_code" placeholder="Enter subject code" required><br>

        <label for="subject_name">Subject Name</label>
        <input type="text" id="subject_name" name="subject_name" placeholder="Enter subject name" required><br>

        <label for="department_code">Department</label>
        <select id="department_code" name="department_code" required>
            <option value="">Select Department</option>
            <?= fetchOptions('departments', 'department_code', 'department_name', $conn); ?>
        </select><br>

        <label for="units">Units</label>
        <input type="number" id="units" name="units" min="1" value="1" required><br>

        <label for="created_by">Created By</label>
        <input type="text" id="created_by" name="created_by" placeholder="Enter creator's name" required><br>

        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="Active" selected>Active</option>
            <option value="Inactive">Inactive</option>
        </select><br>

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4" placeholder="Enter description"></textarea><br>

        <label for="is_requested_subject">Requested Subject?</label>
        <input type="checkbox" id="is_requested_subject" name="is_requested_subject"><br>

        <button type="submit">Save Subject</button>
    </form>

    <hr>

    <!-- Display table of students -->
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Subject Code</th>
                <th>Subject Name</th>
                <th>Department Code</th>
                <th>Unit</th>
                <th>Is Request Subject</th>
                <th>Description</th>
                <th>Created By</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['subject_code']; ?></td>
                    <td><?php echo $row['subject_name']; ?></td>
                    <td><?php echo $row['department_code']; ?></td>
                    <td><?php echo $row['units']; ?></td>
                    <td><?php echo $row['is_requested_subject']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['created_by']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <button onclick="editRecord(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                        <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        function editRecord(record) {
            document.getElementById('id').value = record.id;
            document.getElementById('subject_code').value = record.subject_code;
            document.getElementById('subject_name').value = record.subject_name;
            document.getElementById('department_code').value = record.department_code;
            document.getElementById('units').value = record.units;
            document.getElementById('is_requested_subject').checked = record.is_requested_subject == 1;
            document.getElementById('description').value = record.description;
            document.getElementById('created_by').value = record.created_by;
            document.getElementById('status').value = record.status;
        }
    </script>
</body>

</html>
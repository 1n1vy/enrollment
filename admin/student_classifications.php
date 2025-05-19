<?php
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
    $classification_code = $_POST['classification_code'];
    $classification_name = $_POST['classification_name'];
    $description = $_POST['description'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (isset($_POST['id']) && $_POST['id'] !== '') {
        // Update record
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE student_classifications SET classification_code=?, classification_name=?, description=?, is_active=? WHERE id=?");
        $stmt->bind_param('sssii', $classification_code, $classification_name, $description, $is_active, $id);
    } else {
        // Insert new record
        $stmt = $conn->prepare("INSERT INTO student_classifications (classification_code, classification_name, description, is_active) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('sssi', $classification_code, $classification_name, $description, $is_active);
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
    $stmt = $conn->prepare("DELETE FROM student_classifications WHERE id=?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo "<p>Record deleted successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Fetch all records
$result = $conn->query("SELECT * FROM student_classifications");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Classifications</title>
</head>

<body>
    <h1>Student Classifications</h1>

    <!-- Form for adding/updating classifications -->
    <form method="POST">
        <input type="hidden" name="id" id="id">
        <label for="classification_code">Classification Code:</label><br>
        <input type="text" name="classification_code" id="classification_code" required><br>

        <label for="classification_name">Classification Name:</label><br>
        <input type="text" name="classification_name" id="classification_name" required><br>

        <label for="description">Description:</label><br>
        <textarea name="description" id="description"></textarea><br>

        <label for="is_active">Active:</label>
        <input type="checkbox" name="is_active" id="is_active" value="1"><br>

        <button type="submit">Save</button>
    </form>

    <hr>

    <!-- Display table of classifications -->
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Name</th>
                <th>Description</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['classification_code']; ?></td>
                    <td><?php echo $row['classification_name']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['is_active'] ? 'Yes' : 'No'; ?></td>
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
            document.getElementById('classification_code').value = record.classification_code;
            document.getElementById('classification_name').value = record.classification_name;
            document.getElementById('description').value = record.description;
            document.getElementById('is_active').checked = record.is_active == 1;
        }
    </script>
</body>

</html>
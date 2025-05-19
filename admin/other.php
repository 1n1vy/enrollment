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
    $course_code = $_POST['course_code'];
    $course_name = $_POST['course_name'];
    $description = $_POST['description'];
    $is_international = isset($_POST['is_international']) ? 1 : 0;

    $curriculum_year_start = $_POST['curriculum_year_start'];
    $curriculum_year_end = $_POST['curriculum_year_end'];

    $id = isset($_POST['id']) ? $_POST['id'] : null;

    // Start a transaction to ensure both operations (course and curriculum year) are successful
    $conn->begin_transaction();
    try {
        if ($id) {
            // Update existing course record
            $stmt = $conn->prepare("UPDATE courses SET course_code=?, course_name=?, description=?, is_international=? WHERE id=?");
            $stmt->bind_param('sssii', $course_code, $course_name, $description, $is_international, $id);
            $stmt->execute();
            $stmt->close();

            // Update related curriculum year record
            $stmt = $conn->prepare("UPDATE curriculum_years SET curriculum_year_start=?, curriculum_year_end=?, description=? WHERE course_id=?");
            $stmt->bind_param('iiss', $curriculum_year_start, $curriculum_year_end, $description, $id);
            $stmt->execute();
            $stmt->close();
        } else {
            // Insert new course record
            $stmt = $conn->prepare("INSERT INTO courses (course_code, course_name, description, is_international) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('sssi', $course_code, $course_name, $description, $is_international);
            $stmt->execute();
            $course_id = $conn->insert_id; // Get the last inserted course ID
            $stmt->close();

            // Insert new curriculum year record
            $stmt = $conn->prepare("INSERT INTO curriculum_years (course_id, curriculum_year_start, curriculum_year_end, description) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('iiss', $course_id, $curriculum_year_start, $curriculum_year_end, $description);
            $stmt->execute();
            $stmt->close();
        }

        // Commit the transaction if all operations are successful
        $conn->commit();
        echo "<p>Course and curriculum year saved successfully!</p>";
    } catch (Exception $e) {
        // Rollback if any error occurs
        $conn->rollback();
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
}

// Delete record
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Start a transaction to ensure both operations (course and curriculum year) are deleted successfully
    $conn->begin_transaction();
    try {
        // First, delete from curriculum_years table
        $stmt = $conn->prepare("DELETE FROM curriculum_years WHERE course_id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();

        // Then, delete from courses table
        $stmt = $conn->prepare("DELETE FROM courses WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();

        // Commit the transaction if both delete operations are successful
        $conn->commit();
        echo "<p>Record deleted successfully!</p>";
    } catch (Exception $e) {
        // Rollback if any error occurs
        $conn->rollback();
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
}

// Fetch all courses and curriculum years
$result = $conn->query("SELECT courses.id, courses.course_code, courses.course_name, courses.description, courses.is_international, curriculum_years.curriculum_year_start, curriculum_years.curriculum_year_end
FROM `courses`
INNER JOIN curriculum_years
ON courses.id=curriculum_years.course_id");

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management</title>
</head>

<body>
    <h1>Course Management</h1>

    <!-- Form for adding/updating courses -->
    <form method="POST">
        <input type="hidden" name="id" id="id">
        <label for="course_code">Course Code:</label><br>
        <input type="text" name="course_code" id="course_code" required><br>

        <label for="course_name">Course Name:</label><br>
        <input type="text" id="course_name" name="course_name" required><br>

        <label for="description">Description:</label><br>
        <textarea name="description" id="description"></textarea><br>

        <label for="is_international">Is International:</label>
        <input type="checkbox" id="is_international" name="is_international"><br>

        <label for="curriculum_year_start">Curriculum Year Start:</label>
        <input type="number" id="curriculum_year_start" name="curriculum_year_start" required><br>

        <label for="curriculum_year_end">Curriculum Year End:</label>
        <input type="number" id="curriculum_year_end" name="curriculum_year_end"><br>

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
                <th>Is International</th>
                <th>Curriculum Year</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['course_code']; ?></td>
                    <td><?php echo $row['course_name']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['is_international'] ? 'Yes' : 'No'; ?></td>
                    <td><?php echo $row['curriculum_year_start']; ?> - <?php echo $row['curriculum_year_end']; ?></td>
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
            document.getElementById('course_code').value = record.course_code;
            document.getElementById('course_name').value = record.course_name;
            document.getElementById('description').value = record.description;
            document.getElementById('is_international').checked = record.is_international == 1;
            document.getElementById('curriculum_year_start').value = record.curriculum_year_start;
            document.getElementById('curriculum_year_end').value = record.curriculum_year_end;
        }
    </script>

</body>

</html>
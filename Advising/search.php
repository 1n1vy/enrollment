<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
if (file_exists('../includes/db_connection.php')) {
    require_once '../includes/db_connection.php';
} else {
    die('Database connection file not found!');
}
// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Only process POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data with defaults
    $student_number = $_POST['student_number'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $departments_id = $_POST['departments_id'] ?? 0;
    $course_id = $_POST['course_id'] ?? 0;
    $major_id = $_POST['major_id'] ?? 0;
    $classification_code = $_POST['classification_code'] ?? 0;

    // Call the stored procedure
    $sql = "CALL SearchStudent(?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters to the statement
        $stmt->bind_param("ssssiiii", $student_number, $first_name, $last_name, $gender, $departments_id, $course_id, $major_id, $classification_code);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Check if results exist
        if ($result->num_rows > 0) {
            // Output results as a table
            echo "<table border='1'>
                    <tr>
                        <th>Student Number</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Gender</th>
                        <th>Department ID</th>
                        <th>Course ID</th>
                        <th>Major ID</th>
                        <th>Classification Code</th>
                    </tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['student_number']) . "</td>
                        <td>" . htmlspecialchars($row['first_name']) . "</td>
                        <td>" . htmlspecialchars($row['last_name']) . "</td>
                        <td>" . htmlspecialchars($row['gender']) . "</td>
                        <td>" . htmlspecialchars($row['departments_id']) . "</td>
                        <td>" . htmlspecialchars($row['course_id']) . "</td>
                        <td>" . htmlspecialchars($row['major_id']) . "</td>
                        <td>" . htmlspecialchars($row['classification_code']) . "</td>
                    </tr>";
            }

            echo "</table>";
        } else {
            echo "No results found.";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<form method="POST" action="">
    <input type="text" name="student_number" placeholder="Student Number">
    <input type="text" name="first_name" placeholder="First Name">
    <input type="text" name="last_name" placeholder="Last Name">
    <select name="gender">
        <option value="N/A">Any Gender</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
    </select>
    <input type="number" name="departments_id" placeholder="Department ID">
    <input type="number" name="course_id" placeholder="Course ID">
    <input type="number" name="major_id" placeholder="Major ID">
    <input type="number" name="classification_code" placeholder="Year Level Code">
    <button type="submit">Search</button>
</form>

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
    $student_number = $_POST['student_number'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $middle_name = $_POST['middle_name'];
    $gender = $_POST['gender'];
    $birth_date = $_POST['birth_date'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $classification_code = $_POST['classification_code'];

    if (isset($_POST['id']) && $_POST['id'] !== '') {
        // Update record
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE students SET student_number=?, first_name=?, last_name=?, middle_name=?, gender=?, birth_date=?, email=?, phone_number=?, address=?, classification_code=? WHERE id=?");
        $stmt->bind_param('ssssssssssi', $student_number, $first_name, $last_name, $middle_name, $gender, $birth_date, $email, $phone_number, $address, $classification_code, $id);
    } else {
        // Insert new record
        $stmt = $conn->prepare("INSERT INTO students (student_number, first_name, last_name, middle_name, gender, birth_date, email, phone_number, address, classification_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssss', $student_number, $first_name, $last_name, $middle_name, $gender, $birth_date, $email, $phone_number, $address, $classification_code);
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
$result = $conn->query("SELECT * FROM students");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Creation Form</title>
</head>

<body>
    <h1>Student Creation Form</h1>

    <!-- Form for adding/updating students -->
    <form method="POST">
        <input type="hidden" name="id" id="id">

        <label for="student_number">Student Number:</label><br>
        <input type="text" name="student_number" id="student_number" required><br>

        <label for="first_name">First Name:</label><br>
        <input type="text" name="first_name" id="first_name" required><br>

        <label for="last_name">Last Name:</label><br>
        <input type="text" name="last_name" id="last_name" required><br>

        <label for="middle_name">Middle Name:</label><br>
        <input type="text" name="middle_name" id="middle_name"><br>

        <label for="gender">Gender:</label><br>
        <select name="gender" id="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select><br>

        <label for="birth_date">Date of Birth:</label><br>
        <input type="date" name="birth_date" id="birth_date"><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" id="email"><br>

        <label for="phone_number">Phone Number:</label><br>
        <input type="text" name="phone_number" id="phone_number"><br>

        <label for="address">Address:</label><br>
        <textarea name="address" id="address"></textarea><br>

        <label for="classification_code">Classification:</label><br>
        <select name="classification_code" id="classification_code">
            <option value="">Select Classification</option>
            <!-- Populate with classification codes dynamically from the database -->
            <?php
            $classification_result = $conn->query("SELECT * FROM student_classifications");
            while ($classification = $classification_result->fetch_assoc()) {
                echo "<option value='" . $classification['classification_code'] . "'>" . $classification['classification_name'] . "</option>";
            }
            ?>
        </select><br>

        <button type="submit">Save</button>
    </form>

    <hr>

    <!-- Display table of students -->
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Student Number</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['student_number']; ?></td>
                    <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone_number']; ?></td>
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
            document.getElementById('student_number').value = record.student_number;
            document.getElementById('first_name').value = record.first_name;
            document.getElementById('last_name').value = record.last_name;
            document.getElementById('middle_name').value = record.middle_name;
            document.getElementById('gender').value = record.gender;
            document.getElementById('birth_date').value = record.birth_date;
            document.getElementById('email').value = record.email;
            document.getElementById('phone_number').value = record.phone_number;
            document.getElementById('address').value = record.address;
            document.getElementById('classification_code').value = record.classification_code;
        }
    </script>
</body>

</html>
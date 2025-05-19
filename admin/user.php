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

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submissions (Add/Update User)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $username = $_POST['username'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $gender = $_POST['gender'];
    $date_of_birth = $_POST['date_of_birth'];
    $department_code = $_POST['department_code'];
    $role_id = $_POST['role_id'];
    $specific_role_id = $_POST['specific_role_id'] ?? null;

    // Handle file upload
    $profile_picture = null;
    if (!empty($_FILES['profile_picture']['name'])) {
        $profile_picture = $_FILES['profile_picture']['name'];
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], 'uploads/' . $profile_picture);
    }

    // Check if it's an update or insert
    if ($id) {
        // Update query without password by default
        $query = "UPDATE users SET username=?, first_name=?, last_name=?, email=?, phone_number=?, gender=?, date_of_birth=?, department_code=?, role_id=?, specific_role_id=?, profile_picture=?";
        $params = [
            $username,
            $first_name,
            $last_name,
            $email,
            $phone_number,
            $gender,
            $date_of_birth,
            $department_code,
            $role_id,
            $specific_role_id,
            $profile_picture,
        ];

        // If a new password is provided, include it in the query
        if (!empty($_POST['password'])) {
            $query .= ", password=?";
            $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        $query .= " WHERE id=?";
        $params[] = $id;

        $stmt = $conn->prepare($query);
        $stmt->bind_param(str_repeat('s', count($params) - 1) . 'i', ...$params);
    } else {
        // Insert query
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password, first_name, last_name, email, phone_number, gender, date_of_birth, department_code, role_id, specific_role_id, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            'ssssssssssis',
            $username,
            $password,
            $first_name,
            $last_name,
            $email,
            $phone_number,
            $gender,
            $date_of_birth,
            $department_code,
            $role_id,
            $specific_role_id,
            $profile_picture
        );
    }

    // Execute the query
    if ($stmt->execute()) {
        echo "<p>User saved successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

if (isset($_GET['action']) && $_GET['action'] === 'get_specific_roles' && isset($_GET['role_id'])) {
    $role_id = (int)$_GET['role_id'];

    $stmt = $conn->prepare("SELECT id, specific_name FROM specific_roles WHERE role_id = ?");
    $stmt->bind_param('i', $role_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $roles = [];

        while ($row = $result->fetch_assoc()) {
            $roles[] = $row;
        }

        echo json_encode(['success' => true, 'roles' => $roles]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }

    $stmt->close();
    exit; // End the script here for AJAX response
}


// Delete user record
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo "<p>User deleted successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Fetch all users for displaying in table
$result = $conn->query("SELECT * FROM users");
// Fetch data for dropdowns
$department = $conn->query("SELECT * FROM departments");
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
    <title>User Management</title>
</head>

<body>
    <h1>User Management</h1>

    <!-- Form for adding/updating users -->
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" id="id">
        <label for="username">Username:</label><br>
        <input type="text" name="username" id="username" required><br>

        <label for="password">Password:</label><br>
        <input type="password" name="password" id="password" placeholder="Leave blank to keep existing password"><br>


        <label for="first_name">First Name:</label><br>
        <input type="text" name="first_name" id="first_name"><br>

        <label for="last_name">Last Name:</label><br>
        <input type="text" name="last_name" id="last_name"><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" id="email"><br>

        <label for="phone_number">Phone Number:</label><br>
        <input type="text" name="phone_number" id="phone_number"><br>

        <label for="gender">Gender:</label><br>
        <select name="gender" id="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select><br>

        <label for="date_of_birth">Date of Birth:</label><br>
        <input type="date" name="date_of_birth" id="date_of_birth"><br>

        <label for="department_code">Department:</label><br>
        <select name="department_code" id="department_code">
            <option value="">Select Department</option>
            <?= fetchOptions('departments', 'department_code', 'department_name', $conn); ?>
        </select><br>

        <label for="role_id">Role:</label><br>
        <select name="role_id" id="role_id" onchange="updateSpecificRoles()">
            <option value="">Select Role</option>
            <?= fetchOptions('roles', 'id', 'role_name', $conn); ?>
        </select><br>

        <label for="specific_role_id">Specific Role:</label><br>
        <select name="specific_role_id" id="specific_role_id">
            <option value="">Select Specific Role</option>
            <!-- Populate with specific roles from the database -->
        </select><br>

        <label for="profile_picture">Profile Picture:</label><br>
        <input type="file" name="profile_picture" id="profile_picture"><br>

        <button type="submit">Save</button>
    </form>

    <hr>

    <!-- Display table of users -->
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Profile Picture</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['first_name']; ?></td>
                    <td><?php echo $row['last_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone_number']; ?></td>
                    <td><?php echo $row['gender']; ?></td>
                    <td><?php echo $row['date_of_birth']; ?></td>
                    <td>
                        <?php if ($row['profile_picture']): ?>
                            <img src="uploads/<?php echo $row['profile_picture']; ?>" alt="Profile Picture" width="50" height="50">
                        <?php else: ?>
                            No Picture
                        <?php endif; ?>
                    </td>
                    <td>
                        <button onclick="editUser(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                        <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        function updateSpecificRoles() {
            const roleId = document.getElementById('role_id').value;
            const specificRoleDropdown = document.getElementById('specific_role_id');

            // Clear existing options
            specificRoleDropdown.innerHTML = '<option value="">Select Specific Role</option>';

            if (roleId) {
                // Make an AJAX call to fetch specific roles
                fetch(`user.php?action=get_specific_roles&role_id=${roleId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            data.roles.forEach(role => {
                                const option = document.createElement('option');
                                option.value = role.id;
                                option.textContent = role.specific_name;
                                specificRoleDropdown.appendChild(option);
                            });
                        } else {
                            console.error(data.message || 'Failed to fetch specific roles.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }


        function editUser(user) {
            document.getElementById('id').value = user.id;
            document.getElementById('username').value = user.username;
            // document.getElementById('password').value = user.password;
            document.getElementById('first_name').value = user.first_name;
            document.getElementById('last_name').value = user.last_name;
            document.getElementById('email').value = user.email;
            document.getElementById('phone_number').value = user.phone_number;
            document.getElementById('gender').value = user.gender;
            document.getElementById('date_of_birth').value = user.date_of_birth;
            document.getElementById('department_code').value = user.department_code;
            document.getElementById('role_id').value = user.role_id;

            const specificRoleDropdown = document.getElementById('specific_role_id');

            // Fetch specific roles for the selected role_id
            fetch(`user.php?action=get_specific_roles&role_id=${user.role_id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Clear the dropdown and populate with fetched roles
                        specificRoleDropdown.innerHTML = '<option value="">Select Specific Role</option>';
                        data.roles.forEach(role => {
                            const option = document.createElement('option');
                            option.value = role.id;
                            option.textContent = role.specific_name;
                            if (role.id == user.specific_role_id) {
                                option.selected = true; // Select the correct option
                            }
                            specificRoleDropdown.appendChild(option);
                        });
                    } else {
                        console.error(data.message || 'Failed to fetch specific roles.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>

</body>

</html>
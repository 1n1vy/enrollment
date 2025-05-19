<?php
header('Content-Type: application/json');
require '../includes/db_connection.php';

// Get the raw POST data (JSON format)
$input = json_decode(file_get_contents('php://input'), true);

// Check if input data is valid
if (isset($input['student_id']) && isset($input['payment_mode'])) {
    $student_id = $input['student_id'];
    $payment_mode = $input['payment_mode'];

    // Make sure payment_mode is a valid value
    $valid_payment_modes = ['installment', 'fully-paid', 'Awaiting-Payment'];
    if (in_array($payment_mode, $valid_payment_modes)) {
        // Assuming you have a DB connection already set up (replace this with your actual DB connection code)
        try {
            // Example: assuming $conn is your database connection
            $query = "UPDATE students SET payment_mode = ? WHERE student_number = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $payment_mode, $student_id); // Assuming 'student_number' is the identifier
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(["success" => true, "message" => "Payment mode updated successfully"]);
            } else {
                echo json_encode(["success" => false, "message" => "No changes made"]);
            }

            $stmt->close();
        } catch (Exception $e) {
            // Handle DB connection errors
            echo json_encode(["success" => false, "message" => "Error updating payment mode: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid payment mode value"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Missing student_id or payment_mode"]);
}
?>
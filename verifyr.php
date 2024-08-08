<?php
header('Content-Type: application/json');

// Include the database connection file
include 'conn.php';

$response = ['status' => 'error', 'message' => 'Unknown error'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decode JSON payload
    $input = json_decode(file_get_contents('php://input'), true);

    // Extract parameters
    $patientId = $input['patient_id'] ?? '';
    $relation = $input['relation'] ?? '';
    $caretakerId = $input['c_id'] ?? '';

    if ($patientId && $relation && $caretakerId) {
        // Verify the relationship
        $query = "SELECT * FROM relation WHERE patient_id = ? AND c_id = ? AND relation = ?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            $response = ['status' => 'error', 'message' => 'Prepare failed: ' . htmlspecialchars($conn->error)];
        } else {
            $stmt->bind_param("sss", $patientId, $caretakerId, $relation);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $response = ['status' => 'success', 'message' => 'Verification successful'];
            } else {
                $response = ['status' => 'error', 'message' => 'Invalid relationship data'];
            }

            $stmt->close();
        }
    } else {
        $response = ['status' => 'error', 'message' => 'Invalid input data'];
    }
}

$conn->close();
echo json_encode($response);
?>

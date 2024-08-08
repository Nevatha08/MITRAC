<?php

header('Content-Type: application/json');

// Include the database connection file
include 'conn.php';

$response = ['status' => 'error', 'message' => 'Unknown error'];

// Check if data is received via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from POST request
    $patientId = $_POST['patient_id'] ?? '';
    $caretakerId = $_POST['c_id'] ?? '';
    $relation = $_POST['relation'] ?? '';

    if ($patientId && $caretakerId && $relation) {
        // Check if the relationship already exists in the table for the given patient and caretaker
        $checkQuery = "SELECT * FROM relation WHERE patient_id = ? AND c_id = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("ss", $patientId, $caretakerId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            // Relationship already exists, so don't insert new data
            $response = ['status' => 'error', 'message' => 'Data already exists for this patient and caretaker'];
        } else {
            // Prepare SQL statement to insert data into the relation table
            $insertQuery = "INSERT INTO relation (patient_id, c_id, relation) VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);

            // Bind parameters
            $insertStmt->bind_param("sss", $patientId, $caretakerId, $relation);

            // Execute the statement
            if ($insertStmt->execute()) {
                $response = ['status' => 'success', 'message' => 'Data inserted successfully'];
            } else {
                $response = ['status' => 'error', 'message' => 'Error inserting data: ' . $insertStmt->error];
            }

            // Close statement
            $insertStmt->close();
        }

        // Close check statement
        $checkStmt->close();
    } else {
        $response = ['status' => 'error', 'message' => 'Invalid input data'];
    }
}

// Close connection
$conn->close();

// Output response as JSON
echo json_encode($response);

?>

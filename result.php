<?php

include 'conn.php';

// Check if 'patient_id' and 'score' are set in the POST request
if (isset($_POST['patient_id']) && isset($_POST['score'])) {
    // Get the patient_id and score from the request
    $patient_id = $_POST['patient_id'];
    $score = $_POST['score'];

    // Get the current date
    $current_date = date('Y-m-d');

    // Insert the score and date into the d_score table
    $insert_sql = "INSERT INTO d_score (id, score, date) VALUES (?, ?, ?)";

    if ($stmt = $conn->prepare($insert_sql)) {
        // Bind parameters
        $stmt->bind_param('sis', $patient_id, $score, $current_date);

        // Execute the statement
        if ($stmt->execute()) {
            // Prepare the success response
            $jsonResponse = json_encode([
                'success' => true,
                'message' => 'Score inserted successfully',
                'score' => $score,
                'date' => $current_date
            ]);
        } else {
            // Handle the error if the insert query fails
            $jsonResponse = json_encode(['success' => false, 'error' => $stmt->error]);
        }

        // Close the statement
        $stmt->close();
    } else {
        // Handle the error if the statement preparation fails
        $jsonResponse = json_encode(['success' => false, 'error' => $conn->error]);
    }

    // Send the JSON response
    header('Content-Type: application/json');
    echo $jsonResponse;
} else {
    // Send error response if 'patient_id' or 'score' is not set in the request
    $errorResponse = json_encode(['success' => false, 'error' => 'Patient ID or score is not set']);
    header('Content-Type: application/json');
    echo $errorResponse;
}

// Close the database connection
mysqli_close($conn);
?>

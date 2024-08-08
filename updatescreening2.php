<?php
// Include your connection file
include 'conn.php';

// Initialize response array
$response = array();

// Check if patient_id and q1 to q10 values are received via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if patient_id is set and not empty
    if (isset($_POST['patient_id']) && !empty($_POST['patient_id'])) {
        $patient_id = $_POST['patient_id'];
        $current_date = date('Y-m-d');

        // Initialize an array to store the columns and values to be updated
        $update_values = array();
        $update_params = array();
        $update_types = '';

        // Assuming q1 to q10 values are received as POST parameters
        for ($i = 1; $i <= 10; $i++) {
            $q = 'q' . $i;
            if (isset($_POST[$q])) {
                $q_value = $_POST[$q];
                $update_values[] = "$q = ?";
                $update_params[] = $q_value;
                $update_types .= 's'; // 's' for string
            }
        }

        // Add the date field
        $update_values[] = "`date` = ?";
        $update_params[] = $current_date;
        $update_types .= 's'; // 's' for string

        // Combine the update values into a string
        if (!empty($update_values)) {
            $update_string = implode(', ', $update_values);

            // Prepare the SQL statement
            $sql = "UPDATE screening2 SET $update_string WHERE patient_id = ?";
            $stmt = $conn->prepare($sql);

            // Add the patient_id to the parameters
            $update_params[] = $patient_id;
            $update_types .= 's'; // 's' for string

            // Bind parameters
            $stmt->bind_param($update_types, ...$update_params);

            // Execute the SQL statement
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Records updated successfully for patient ID: $patient_id";
            } else {
                $response['success'] = false;
                $response['message'] = "Error updating records: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            $response['success'] = false;
            $response['message'] = "No fields to update";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "patient_id is not set or empty";
    }

    // Close the connection
    mysqli_close($conn);
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method";
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
`
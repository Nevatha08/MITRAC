<?php
// Include your connection file
include 'conn.php';

// Initialize response array
$response = array();

// Check if patient_id is received via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you have received patient_id as a POST parameter
    $patient_id = $_POST['patient_id'];

    // Prepare default values for q1 to q8
    $q_values = array('0', '0', '0', '0', '0', '0', '0', '0');

    // Convert q_values array into a comma-separated string
    $q_values_str = implode("', '", $q_values);

    // Get today's date
    $today_date = date('Y-m-d');

    // Check if there's already an entry for the patient for today's date
    $check_sql = "SELECT * FROM screening WHERE patient_id = '$patient_id' AND `date` = '$today_date'";
    $check_result = mysqli_query($conn, $check_sql);

    if(mysqli_num_rows($check_result) == 0) {
        // Insert default values into the table for the specific patient
        $sql = "INSERT INTO screening (patient_id, q1, q2, q3, q4, q5, q6, q7, q8, `date`) 
                VALUES ('$patient_id', '$q_values_str', '$today_date')";

        if (mysqli_query($conn, $sql)) {
            // Return success response
            $response['success'] = true;
            $response['message'] = "Initial values inserted successfully for patient ID: $patient_id";
        } else {
            // Return error response
            $response['success'] = false;
            $response['message'] = "Error inserting initial values: " . mysqli_error($conn);
        }
    } else {
        // Return message if there's already an entry for today's date
        $response['success'] = false;
        $response['message'] = "Data already exists for patient ID: $patient_id for today's date";
    }

    // Close the connection
    mysqli_close($conn);
} else {
    // If request method is not POST
    $response['success'] = false;
    $response['message'] = "Invalid request method";
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>

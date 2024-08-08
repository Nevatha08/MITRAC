<?php
// Include your connection file
include 'conn.php';

// Initialize response array
$response = array();

// Check if patient_id is received via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you have received patient_id as a POST parameter
    $patient_id = $_POST['patient_id'];

    // Get today's date
    $today_date = date('Y-m-d');

    // Fetch the responses for the patient for today's date
    $fetch_sql = "SELECT q1, q2, q3, q4, q5, q6, q7, q8 FROM screening WHERE patient_id = '$patient_id' AND `date` = '$today_date'";
    $fetch_result = mysqli_query($conn, $fetch_sql);

    if (mysqli_num_rows($fetch_result) > 0) {
        $row = mysqli_fetch_assoc($fetch_result);

        // Calculate the score by counting the number of 1s
        $score = 0;
        for ($i = 1; $i <= 8; $i++) {
            if ($row["q$i"] == '1') {
                $score++;
            }
        }

        // Store the score in the database
        $insert_sql = "INSERT INTO w_score (id, score, `date`) VALUES ('$patient_id', '$score', '$today_date')";
        if (mysqli_query($conn, $insert_sql)) {
            // Return success response
            $response['success'] = true;
            $response['message'] = "Score calculated and stored successfully";
        } else {
            // Return error response
            $response['success'] = false;
            $response['message'] = "Error storing score: " . mysqli_error($conn);
        }
    } else {
        // Return message if no responses found
        $response['success'] = false;
        $response['message'] = "No responses found for patient ID: $patient_id for today's date";
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

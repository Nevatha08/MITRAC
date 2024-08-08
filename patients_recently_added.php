<?php
include("conn.php");

// Check if POST data is received
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve data from the POST request
    // Assuming patient_id is a unique identifier for each patient
    $sql = "SELECT patient_id, name, sex, mobile_number, img FROM patient_details ORDER BY insertion_timestamp DESC LIMIT 5";

    
    $result = $conn->query($sql);

    if ($result === false) {
        // Handle query execution error
        echo json_encode(array('status' => 'failure', 'message' => 'Query execution error: ' . $conn->error));
    } else {
        if ($result->num_rows > 0) {
            $patients = array();

            while ($row = $result->fetch_assoc()) {
                // Read the image file and encode it as base64
                $imgData = file_get_contents($row['img']);
                $base64Img = base64_encode($imgData);
                
                // Add the base64 encoded image to the row
                $row['img'] = $base64Img;

                $patients[] = $row;
            }

            echo json_encode(array('status' => 'success', 'patients' => $patients));
        } else {
            echo json_encode(array('status' => 'failure', 'message' => 'No recent patients found'));
        }
    }
} else {
    echo json_encode(array('status' => 'failure', 'message' => 'Invalid request method'));
}
?>

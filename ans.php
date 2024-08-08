<?php
// Include the database connection file
require 'conn.php';

// Decode the JSON data sent from the Android application
$data = json_decode(file_get_contents('php://input'), true);

// Check if the patient_id key exists in the JSON data
if(isset($data['patient_id'])) {
    // Sanitize the input to prevent SQL injection
    $patient_id = mysqli_real_escape_string($conn, $data['patient_id']);
    
    // SQL query to select values from the screening table filtered by patient_id
    $sql = "SELECT * FROM screening WHERE patient_id = '$patient_id'";
    
    // Execute the query
    $result = mysqli_query($conn, $sql);
    
    // Check if there are rows returned
    if ($result) {
        // Create an array to store the results
        $response = array();
        
        // Loop through each row
        while($row = mysqli_fetch_assoc($result)) {
            // Add each row to the response array
            $response[] = $row;
        }
        
        // Return the response as JSON
        echo json_encode(array("success" => true, "data" => $response));
    } else {
        // No results found
        echo json_encode(array("success" => false, "message" => "No results found for the given patient ID"));
    }
} else {
    // patient_id parameter is not set
    echo json_encode(array("success" => false, "message" => "Patient ID parameter is not set"));
}

// Close connection
mysqli_close($conn);
?>
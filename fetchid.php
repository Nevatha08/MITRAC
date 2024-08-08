<?php

// Include the database connection file
include 'conn.php';

// SQL query to fetch patient ID from the table
$sql = "SELECT patient_id FROM patient_details";

$result = $conn->query($sql);

// Check if any rows were returned
if ($result->num_rows > 0) {
    // Store results in an array
    $patientIds = array();
    while ($row = $result->fetch_assoc()) {
        $patientIds[] = $row['patient_id'];
    }
    // Output JSON response
    echo json_encode($patientIds);
} else {
    // No results found
    echo json_encode(array("error" => "No patient IDs found"));
}

// Close connection
$conn->close();

?>

<?php
require_once "conn.php";
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $q1 = "SELECT patient_id FROM patient_details";
    $result = mysqli_query($conn, $q1);

    $patientList = array(); // Initialize an array to hold patient IDs

    // Fetch data from the database and store it in the array
    while ($row = mysqli_fetch_assoc($result)) {
        $patientList[] = $row['patient_id'];
    }

    // Convert the PHP array to JSON format
    $jsonPatientList = json_encode($patientList);

    // Output the JSON data
    echo $jsonPatientList;
}
?>

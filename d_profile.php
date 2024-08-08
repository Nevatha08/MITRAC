<?php
require("conn.php"); // Include your database connection script

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Extract data from the POST request
    $d_id = $_POST['d_id'];
    $name = $_POST['name'];
    $designation = $_POST['designation'];
    $sex = $_POST['sex'];
    $mobile_number = $_POST['mobile_number'];
    $specialization = $_POST['specialization'];
    $address = $_POST['address'];
    $marital_status = $_POST['marital_status'];

    // Insert data into your database (adjust table name as needed)
    $sql = "INSERT INTO doctor_profile (d_id, name, designation, sex, mobile_number, specialization, address, marital_status)
            VALUES ('$d_id', '$name', '$designation', '$sex', '$mobile_number', '$specialization', '$address', '$marital_status')";

    if ($conn->query($sql) === true) {
        $response = array('status' => 'Success', 'message' => 'Data inserted successfully');
    } else {
        $response = array('status' => 'Error', 'message' => 'Data not inserted: ' . $conn->error);
    }

    // Send a JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Invalid request method
    $response = array('status' => 'Error', 'message' => 'Invalid request method');
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>

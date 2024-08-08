<?php
// Include your database connection code here (e.g., db_conn.php)
require_once('conn.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input data from the application
    $caretaker_id = $_POST['c_id'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $mobile_number = $_POST['mobile_number'];
    $qualification = $_POST['qualification'];
    $address = $_POST['address'];
    $marital_status = $_POST['marital_status'];

    // Check if caretaker already exists
    $check_sql = "SELECT c_id FROM caretaker_profile WHERE c_id = '$caretaker_id'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Caretaker already exists
        $response = array('status' => 'error', 'message' => 'Caretaker already exists.');
        echo json_encode($response);
    } else {
        // Insert data into the database
        $sql = "INSERT INTO caretaker_profile (c_id, password, name, age, sex, mobile_number, qualification, address, marital_status) 
                VALUES ('$caretaker_id', '$password', '$name', '$age', '$sex', '$mobile_number', '$qualification', '$address', '$marital_status')";

        if ($conn->query($sql) === TRUE) {
            // Successful insertion
            $response = array('status' => 'success', 'message' => 'Caretaker registration successful.');
            echo json_encode($response);
        } else {
            // Error in database insertion
            $response = array('status' => 'error', 'message' => 'Error: ' . $conn->error);
            echo json_encode($response);
        }
    }
} else {
    // Handle non-POST requests (e.g., return an error response)
    $response = array('status' => 'error', 'message' => 'Invalid request method.');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($response);
}

// Close the database connection
$conn->close();
?>

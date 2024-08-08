<?php
// Include your database connection code here (e.g., db_conn.php)
require_once('conn.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input data from the application
    $d_id = $_POST['d_id'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $designation = $_POST['designation'];
    $sex = $_POST['sex'];
    $mobile_number = $_POST['mobile_number'];
    $email = $_POST['email'];
    $specialization = $_POST['specialization'];
    $address = $_POST['address'];
    $marital_status = $_POST['marital_status'];

    $check_sql = "SELECT d_id FROM doctor_profile WHERE d_id = '$d_id'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // User already exists
        $response = array('status' => 'error', 'message' => 'User already exists.');
        echo json_encode($response);
    } else {
        // Convert base64 image to JPEG and save it
        if (isset($_POST['img'])) {
            $base64_image = $_POST['img'];
            $image_data = base64_decode($base64_image);
            $image_path = 'img/' . $d_id. '.jpg'; // Save with username as filename
            $image_file = fopen($image_path, 'wb');
            fwrite($image_file, $image_data);
            fclose($image_file);
        } else {
            $image_path = null;
        }

        // Insert data into the database
        $sql = "INSERT INTO doctor_profile (d_id, password, name, designation, sex, mobile_number, email, specialization, address, marital_status, img)
        VALUES ('$d_id', '$password', '$name', '$designation', '$sex', '$mobile_number', '$email', '$specialization', '$address', '$marital_status','$image_path')"; // Corrected variable name
        if ($conn->query($sql) === TRUE) {
            // Successful insertion
            $response = array('status' => 'success', 'message' => 'User registration successful.');
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
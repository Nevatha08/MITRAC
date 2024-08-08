<?php
// Include your database connection file
require "conn.php";

// Check if the necessary parameters are provided
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON input
    $jsonInput = file_get_contents("php://input");

    // Decode JSON data into an associative array
    $postData = json_decode($jsonInput, true);

    // Check if JSON decoding was successful
    if ($postData !== null) {
        // Sanitize input data to prevent SQL injection
        $id = $postData['patient_id'];
        $name = $postData['name'];
        $sex = $postData['sex'];
        $age = $postData['age'];
        $mn = $postData['mobile_number'];
        $ms = $postData['marital_status'];
        $ds = $postData['disease_status'];
        $edu = $postData['education'];
        $dur = $postData['duration'];
        $addr = $postData['address'];

        // Directory to save uploaded profile pictures
        $uploadDirectory = "img/" . $id . '.jpg';

        // Get the uploaded file details
        $profilePicBase64 = $postData['profile_pic'];
        $profilePicBinary = base64_decode($profilePicBase64);

        // Save the image
        if (file_put_contents($uploadDirectory, $profilePicBinary)) {
            // Update the database with the new information including the profile picture path
            $profilePicPath = $uploadDirectory;
            $timestamp = date('Y-m-d H:i:s');
            $query = "INSERT INTO patient_details (patient_id, name,age, sex, education, mobile_number, address, marital_status,disease_status,duration,img,insertion_timestamp)
                      VALUES ('$id', '$name', '$age', '$sex', '$edu', '$mn', '$addr', '$ms','$ds','$dur','$uploadDirectory','$timestamp')";

            if (mysqli_query($conn, $query)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save the image']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON format']);    
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

// Close the database connection
mysqli_close($conn);
?>

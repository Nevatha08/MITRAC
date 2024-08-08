
<?php
require("conn.php"); // Include your database connection script

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Extract data from the POST request
        $c_id = $_POST['c_id'];
        $name = $_POST['name'];
        $age = $_POST['age'];
        $sex = $_POST['sex'];
        $mobileNumber = $_POST['mobile_number'];
        $qualification = $_POST['qualification'];  
        $address = $_POST['address'];
        $maritalStatus = $_POST['marital_status'];

    // Insert data into your database (adjust table name as needed)
    $sql = "INSERT INTO caretaker_profile( c_id,name, age,sex, mobile_number, qualification,  address, marital_status)
    VALUES ( '$c_id','$name', '$age', '$sex', '$mobileNumber', '$qualification','$address', '$maritalStatus')";

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

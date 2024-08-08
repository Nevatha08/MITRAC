
<?php
require("conn.php"); // Include your database connection script

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Extract data from the POST request
    $patient_id = $_POST["patient_id"];
    $name = $_POST["name"];
    $age = $_POST["age"];
    $sex = $_POST["sex"];
    $mobile_number = $_POST["mobile_number"];
    $education = $_POST["education"];
    $address = $_POST["address"];
    $marital_status = $_POST["marital_status"];
    $disease_status = $_POST["disease_status"];
    $duration = $_POST["duration"];

    // Update data in your database (adjust table name as needed)
    $sql = "UPDATE patient_details SET 
    name = '$name', 
    age = '$age', 
    sex = '$sex', 
    mobile_number = '$mobile_number', 
    education = '$education', 
    address = '$address', 
    marital_status = '$marital_status', 
    disease_status = '$disease_status', 
    duration = '$duration' 
    WHERE patient_id = '$patient_id'";

    if ($conn->query($sql) === true) {
        echo "Success";
    } else {
        echo "error";
    }

    // Send a JSON response


} else {
    // Invalid request method
   echo "error";
  
}
?>


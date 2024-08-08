
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

    // Update data in your database (adjust table name as needed)
    $sql = "UPDATE doctor_profile 
            SET name='$name', designation='$designation', sex='$sex', 
                mobile_number='$mobile_number', specialization='$specialization', 
                address='$address', marital_status='$marital_status'
            WHERE d_id='$d_id'";

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


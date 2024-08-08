
<?php
require("conn.php"); // Include your database connection script

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Extract data from the POST request
    $c_id = $_POST['c_id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $mobile_number = $_POST['mobile_number'];
    $qualification = $_POST['qualification'];
    $address = $_POST['address'];
    $marital_status = $_POST['marital_status'];

    // Update data in your database (adjust table name as needed)
    $sql = "UPDATE caretaker_profile 
            SET name='$name',age='$age',sex='$sex', 
                mobile_number='$mobile_number', qualification='$qualification', 
                address='$address', marital_status='$marital_status'
            WHERE c_id='$c_id'";

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


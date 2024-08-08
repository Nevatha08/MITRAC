<?php
// Include the database connection file
include 'conn.php';

// Fetch patient data based on the received ID
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];

    $sql = "SELECT * FROM patient_details WHERE patient_id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Return data in JSON format
        $response = array(
            "tx1" => $row["patient_id"],
            "tx2" => $row["name"],
            "tx3" => $row["age"],
            "tx4" => $row["sex"],
            "tx5" => $row["education"],
            "tx6" => $row["mobile_number"],
            "tx7" => $row["address"],
            "tx8" => $row["marital_status"],
            "tx9" => $row["disease_status"],
            "tx10" => $row["duration"],
            "img1" => $row["img"]
        );

        echo json_encode($response);
    } else {
        // No patient found with the given ID
        echo "Patient not found";
    }
} else {
    // Invalid request method
    echo "Invalid request method";
}

// Close the database connection
$conn->close();
?>
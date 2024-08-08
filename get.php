<?php

// Database connection details
require("conn.php");

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Add any necessary parameters for fetching data
    $patientId = $_POST['id'];
   // $type = $_POST['type'];  // Correctly use 'patientId' key

    // Fetch all data for the given id from your database (adjust table name as needed)
    $sql = "SELECT Medicine_name, Dose, Type FROM medicine_timings WHERE id = '$patientId'";
    //and Type = '$type'";
    $result = $conn->query($sql);

    if ($result !== false) {
        $data = array();

        if ($result->num_rows > 0) {
            // Fetch and store all rows in an array
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            // Return data as JSON
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            // No data found
            $response = array('status' => 'Error', 'message' => 'No data found');
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    } else {
        // Query execution error
        $response = array('status' => 'Error', 'message' => $conn->error);
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    // Invalid request method
    $response = array('status' => 'Error', 'message' => 'Invalid request method');
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Close the connection
$conn->close();

?>

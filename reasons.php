<?php
require("conn.php"); // Include your database connection file

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get the text from the POST request
    $text = $_POST['reasons'];
    $pid = $_POST['patient_id'];

    // Insert the data into the database with explicit column names
    $sql = "INSERT INTO reasons (patient_id, reasons) VALUES ('$pid', '$text')";

    if (mysqli_query($conn, $sql)) {
        // Insert successful
        echo json_encode(['status' => 'success', 'message' => 'Data inserted successfully']);
    } else {
        // Insert failed
        echo json_encode(['status' => 'error', 'message' => 'Data not inserted. Error: ' . mysqli_error($conn)]);
    }
} else {
    // Request method is not POST
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

// Close the database connection (if necessary)
mysqli_close($conn);
?>

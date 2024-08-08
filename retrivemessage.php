<?php
// Include the database connection file
include 'conn.php';

// Calculate the date for two days ago from the current date
$twoDaysAgo = date("Y-m-d", strtotime("-2 days"));

// Get the current date
$currentDate = date("Y-m-d");

// Prepare and execute SQL query to fetch patient_id and message for the last two days
$sql = "SELECT id, message FROM notification WHERE date BETWEEN '$twoDaysAgo' AND '$currentDate' ORDER BY date DESC";
$result = $conn->query($sql);

// Array to hold messages
$messages = array();

// Check if there are any rows returned
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        // Add message and patient_id to the array
        $messages[] = array(
            "patient_id" => $row["id"] ?? '',  // Ensure patient_id is not null
            "message" => $row["message"] ?? ''  // Ensure message is not null
        );
    }

    // Return success response along with the fetched messages
    $response = array(
        "status" => "success",
        "messages" => $messages
    );
} else {
    // No results
    $response = array(
        "status" => "success",
        "messages" => [],
        "message" => "No messages found for the last two days"
    );
}

// Convert the response array to JSON format and output
echo json_encode($response);

// Close the connection (optional if you want to close it explicitly)
$conn->close();
?>

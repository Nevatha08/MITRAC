<?php
require("conn.php");

// Delete records older than 5 days
$delete_sql = "DELETE FROM reasons WHERE time1 < (NOW() - INTERVAL 5 DAY)";
$conn->query($delete_sql);

// Check if the request is a GET request
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Assuming you receive the patient_id via GET
    $patient_id = $_GET['patient_id'];

    // Fetch all reasons and their corresponding dates from your database for the specified patient ID
    $sql = "SELECT reasons, time1 FROM reasons WHERE patient_id = '$patient_id' ORDER BY time1 DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch and return data as JSON
        $reasons = array();
        while ($row = $result->fetch_assoc()) {
            $reasons[] = array('reason' => $row['reasons'], 'date' => $row['time1']);
        }
        header('Content-Type: application/json');
        echo json_encode($reasons);
    } else {
        // No data found
        $response = array('status' => 'Error', 'message' => 'No reasons found for the specified patient ID');
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    // Invalid request method
    $response = array('status' => 'Error', 'message' => 'Invalid request method');
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
    
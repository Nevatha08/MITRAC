<?php
require("conn.php"); // Include your database connection script

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Add any necessary parameters for fetching data
    // For example, you might have a p_id parameter in the POST request
    $id = $_POST['id'];

    // Corrected from $Type to $_POST['Type']

    // Fetch all data for the given id from your database (adjust table name as needed)
    $sql = "SELECT Medicine_name, Dose, Type FROM medicine_timings WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch and return data as JSON
        $rows = array();
        while($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        header('Content-Type: application/json');   
        echo json_encode($rows);
    } else {
        // No data found
        $response = array('status' => 'Error', 'message' => 'No data found');
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

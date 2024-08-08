<?php
require "conn.php";

// Retrieve the JSON data from the request
$data = json_decode(file_get_contents("php://input"), true);

$id = isset($data['id']) ? $data['id'] : null;
$medicineName = isset($data['Medicine_name']) ? $data['Medicine_name'] : null;

if ($id && $medicineName) {
    // Prepare the SQL statement to delete the specific record
    if ($stmt = $conn->prepare("DELETE FROM medicine_timings WHERE id = ? AND Medicine_name = ?")) {
        $stmt->bind_param("ss", $id, $medicineName);

        // Execute the query
        if ($stmt->execute()) {
            $response = array("status" => "success", "message" => "Record deleted successfully.");
        } else {
            $response = array("status" => "error", "message" => "Failed to delete record: " . $stmt->error);
        }

        $stmt->close();
    } else {
        $response = array("status" => "error", "message" => "Failed to prepare the SQL statement.");
    }
} else {
    $response = array("status" => "error", "message" => "Required fields are missing.");
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);

// Close the database connection
$conn->close();
?>

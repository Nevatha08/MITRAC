<?php
require "conn.php";

// Retrieve the JSON data from the request
$data = json_decode(file_get_contents("php://input"), true);

$id = isset($data['id']) ? $data['id'] : null;
$medicineName = isset($data['Medicine_name']) ? $data['Medicine_name'] : null;
$status = isset($data['status']) ? $data['status'] : null;

// Get the current date
$currentDate = date('Y-m-d');

if ($id && $medicineName && ($status === "0" || $status === "1")) {
    // Update the status of the specific medicine for the specific id and current date
    $sql = "UPDATE medicine_timings SET status = ? WHERE id = ? AND Medicine_name = ? AND Date = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $status, $id, $medicineName, $currentDate);

    if ($stmt->execute()) {
        $response = array("status" => "success");
    } else {
        $response = array("status" => "error", "message" => "Failed to update status: " . $stmt->error);
    }

    $stmt->close();
} else {
    $response = array("status" => "error", "message" => "Required fields are missing or invalid");
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);

// Close the database connection
$conn->close();
?>

<?php
require "conn.php";

// Function to delete records older than 15 days
function deleteOldRecords($conn) {
    $deleteRecordsQuery = "DELETE FROM medicine_timings WHERE Date < DATE_SUB(CURDATE(), INTERVAL 15 DAY)";
    if (mysqli_query($conn, $deleteRecordsQuery)) {
        return true;
    } else {
        return mysqli_error($conn); // Return error message for debugging
    }
}

// Retrieve the JSON data from the request
$data = json_decode(file_get_contents("php://input"), true);

$id = isset($data['id']) ? $data['id'] : null;
$medicineName = isset($data['Medicine_name']) ? $data['Medicine_name'] : null;
$dose = isset($data['Dose']) ? $data['Dose'] : null;
$type = isset($data['Type']) ? $data['Type'] : null;

$response = array();

if ($id && $medicineName && $dose && $type) {
    // Call function to delete old records
    $deleteResult = deleteOldRecords($conn);
    if ($deleteResult === true) {
        $response[] = array("status" => "success", "message" => "Old records deleted successfully");
    } else {
        $response[] = array("status" => "error", "message" => "Failed to delete old records: " . $deleteResult);
    }

    // Insert the record with different dates from current date to 15 days into the future
    for ($i = 0; $i < 15; $i++) {
        $date = date("Y-m-d", strtotime("+$i day"));

        $stmt = $conn->prepare("INSERT INTO medicine_timings (id, Medicine_name, Dose, Type, Date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $id, $medicineName, $dose, $type, $date);

        if ($stmt->execute()) {
            $response[] = array("status" => "success", "message" => "Record inserted successfully");
        } else {
            $response[] = array("status" => "error", "message" => "Failed to insert record: " . $stmt->error);
        }

        $stmt->close();
    }
} else {
    $response[] = array("status" => "error", "message" => "Required fields are missing");
}

// Retrieve and display all records
$retrieveQuery = "SELECT * FROM medicine_timings ORDER BY Date DESC";
$result = mysqli_query($conn, $retrieveQuery);

if ($result) {
    $records = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $records[] = $row;
    }
    $response[] = array("status" => "success", "records" => $records);
} else {
    $response[] = array("status" => "error", "message" => "Failed to retrieve records: " . mysqli_error($conn));
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);

// Close the database connection
$conn->close();
?>

<?php
require "conn.php";

// Retrieve the JSON data from the request
$data = json_decode(file_get_contents("php://input"), true);

$id = isset($data['id']) ? $data['id'] : null;
$date = isset($data['date']) ? $data['date'] : null;

if ($id && $date) {
    // Fetch medicine details for the specific patient id and date
    $sql = "SELECT Medicine_name, Dose, Type, status, Date FROM medicine_timings WHERE id = ? AND Date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $id, $date);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $medicines = [];

        while ($row = $result->fetch_assoc()) {
            $medicines[] = $row;
        }

        if (!empty($medicines)) {
            echo json_encode([
                "status" => "success",
                "data" => $medicines
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "No medicines found for this date"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to fetch medicines: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Required fields are missing"]);
}

// Close the database connection
$conn->close();
?>

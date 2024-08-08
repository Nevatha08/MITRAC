<?php
header('Content-Type: application/json');
include 'conn.php'; // Include your database connection file

// Get the raw POST data and decode it
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$patient_id = $data['id'] ?? '';

// Validate the patient_id
if (empty($patient_id)) {
    echo json_encode(array('success' => false, 'message' => 'Patient ID is required.'));
    exit();
}

$response = array();
$response['success'] = false;

// Fetch scores for the given patient_id
$sql = "SELECT * FROM w_score WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $patient_id);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $scores = array();
        while ($row = $result->fetch_assoc()) {
            $scores[] = $row;
        }
        $response['success'] = true;
        $response['data'] = $scores;
    } else {
        $response['message'] = 'No scores found for this patient.';
    }
} else {
    $response['message'] = 'Failed to execute query.';
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>

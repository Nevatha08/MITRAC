<?php
require_once('conn.php');

function jsonResponse($status, $message, $data = null) {
    $response = array('status' => $status, 'message' => $message);
    if ($data !== null) {
        $response['doctorDetails'] = $data;
    }
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($response);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if 'd_id' is set
    if (isset($_POST['d_id'])) {
        // Get the d_id from the POST data
        $d_id = trim($_POST['d_id']);

        // SQL query to retrieve doctor information based on d_id
        $sql = "SELECT * FROM doctor_profile WHERE d_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $d_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch doctor details as an associative array
            $doctorDetails = $result->fetch_assoc();

            // Add image URL to doctor details if the image path is set and not empty
            if (!empty($doctorDetails['img'])) {
                $doctorDetails['img_url'] = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $doctorDetails['img'];
            } else {
                // If no image path is set, use a default image URL or set it to null
                $doctorDetails['img_url'] = null; // Or you could use a default image URL
                // Example: $doctorDetails['img_url'] = 'http://' . $_SERVER['HTTP_HOST'] . '/path/to/default/image.jpg';
            }

            // Return doctor details as JSON with proper Content-Type header
            jsonResponse(true, "Doctor details retrieved successfully.", $doctorDetails);
        } else {
            // No doctor found with the provided d_id
            jsonResponse(false, "No doctor found with the provided d_id.");
        }
    } else {
        // 'd_id' not provided
        jsonResponse(false, "Please provide a d_id.");
    }
} else {
    // Handle non-POST requests (e.g., return an error response)
    jsonResponse(false, "Invalid request method.");
}

// Close the database connection
$conn->close();
?>

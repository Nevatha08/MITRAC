<?php
// send-notification.php

// Database connection
include 'conn.php';

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);

$patientId = $data['id'];
$message = $data['message'];
$currentDate = date('Y-m-d');

$sql = "INSERT INTO notification (id, message, read_status, date) VALUES ('$patientId', '$message', 0, '$currentDate' )";

if ($conn->query($sql) === TRUE) {
  echo json_encode(['status' => 'success']);
} else {
  echo json_encode(['status' => 'error', 'message' => $conn->error]);
}

$conn->close();
?>

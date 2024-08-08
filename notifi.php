<?php
// get-unread-notifications.php

// Database connection
include 'conn.php';

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT COUNT(*) AS unreadCount FROM notification WHERE read_status = 0";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  echo json_encode(['unreadCount' => $row['unreadCount']]);
} else {
  echo json_encode(['unreadCount' => 0]);
}

$conn->close();
?>

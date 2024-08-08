<?php
// markread.php

include 'conn.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = "UPDATE notification SET read_status = 1 WHERE read_status = 0";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update read status']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

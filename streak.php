 <?php
// Include your database connection script here
require("conn.php");

// Assuming you already have a valid $conn object from your connection script

// Get values from POST request
$patientId = $_POST['id'];
$totalCount = $_POST['maxScore'];

// Use the current date
$currentDate = date("Y-m-d");

// Uncomment the line below if you want to insert a new record for each update
// $insertNewRecordQuery = "INSERT INTO streak (id, total_count, date) VALUES ('$patientId', '$totalCount', '$currentDate')";
// $conn->query($insertNewRecordQuery);

// Update total_count for the current date
$updateTotalCountQuery = "UPDATE streak SET total_count = '$totalCount' WHERE id = '$patientId' AND date = '$currentDate'";
if ($conn->query($updateTotalCountQuery) === TRUE) {
    //echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

// Example data
// $patientId = 1434; // Replace with actual patient ID
// $dateTaken = date("Y-m-d"); // Current date
// $tabletsTaken = 1; // Replace with 1 or 0 based on tablet intake

// Check if the patient missed three consecutive days
$missedDaysQuery = "SELECT COUNT(*) as missed_days FROM streak WHERE id = $patientId AND date >= DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND total_count = 0";
$result = $conn->query($missedDaysQuery);
$row = $result->fetch_assoc();
$missedDays = $row['missed_days'];

if ($missedDays >= 3) {
    // Reset progress bar by deleting previous records
    $resetQuery = "DELETE FROM streak WHERE id = $patientId";
    $conn->query($resetQuery);
}

// Fetch and calculate total streak count
$totalStreakQuery = "SELECT COUNT(*) as total_streaks FROM streak WHERE id = $patientId AND total_count = '1'";
$resultTotalStreak = $conn->query($totalStreakQuery);
$rowTotalStreak = $resultTotalStreak->fetch_assoc();
$totalStreaks = $rowTotalStreak['total_streaks'];

echo "Total Streaks: $totalStreaks";

$conn->close();
?>

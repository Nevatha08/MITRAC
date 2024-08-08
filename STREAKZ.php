<?php
require "conn.php"; // Assuming this file sets up the database connection

// Check if id is provided and numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Get the earliest date for this id from the database
    $earliestDateQuery = "SELECT MIN(Date) FROM medicine_timings WHERE id = ?";
    if ($stmt = $conn->prepare($earliestDateQuery)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($earliestDate);
        $stmt->fetch();
        $stmt->close();
    } else {
        echo json_encode(array("error" => "Failed to prepare the SQL statement to fetch earliest date."));
        exit;
    }

    // If earliestDate is null, set it to a distant past date or handle accordingly
    if (!$earliestDate) {
        $earliestDate = '1970-01-01'; // Or set to any default date
    }

    $currentDate = date('Y-m-d'); // Get current date in YYYY-MM-DD format

    // Get the list of dates and statuses for this patient
    $medicationQuery = "SELECT Date, status FROM medicine_timings WHERE id = ? ORDER BY Date ASC";
    if ($stmt = $conn->prepare($medicationQuery)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dates = [];
        while ($row = $result->fetch_assoc()) {
            $dates[] = $row;
        }
        $stmt->close();

        // Initialize variables to track the medication status
        $totalDays = 0;
        $successfulDays = 0;
        $missedDaysCount = 0;
        $missedStreak = false;
        $resumeDate = null;

        // Iterate over each day from the earliest date to the current date
        $period = new DatePeriod(
            new DateTime($earliestDate),
            new DateInterval('P1D'),
            new DateTime($currentDate . ' +1 day')
        );

        foreach ($period as $date) {
            $totalDays++;
            $dateString = $date->format('Y-m-d');

            // Check if the date is in the medication timings
            $status = null;
            foreach ($dates as $d) {
                if ($d['Date'] == $dateString) {
                    $status = (int)$d['status']; // Ensure status is an integer
                    break;
                }
            }

            // If status is '1', increment successful days and reset missed days count
            if ($status === 1) {
                if ($missedStreak) {
                    // If a missed streak was previously detected, set the resume date
                    $resumeDate = $dateString;
                    $missedStreak = false; // Reset missed streak
                }
                $successfulDays++;
                $missedDaysCount = 0;
            } else {
                $missedDaysCount++;
                if ($missedDaysCount == 3) {
                    $missedStreak = true;
                }
            }
            
        }

        // If the streak was missed, calculate streak percentage from the resume date
        if ($missedStreak) {
            $responseData = array(
                "streakPercentage" => 0,
                "notification" => "The patient has missed medication for three consecutive days and lost the streak."
            );
        } else {
            // Calculate streak percentage
            $streakPercentage = ($totalDays > 0) ? ($successfulDays / $totalDays) * 100 : 0;

            // Prepare data to return as JSON
            $responseData = array(
                "streakPercentage" => $streakPercentage
            );
        }

        // Output data as JSON
        echo json_encode($responseData);
    } else {
        echo json_encode(array("error" => "Failed to prepare the SQL statement to check medication dates."));
        exit;
    }
} else {
    echo json_encode(array("error" => "Invalid ID.")); // Output error message if id parameter is missing or not numeric
}

// Close connection
$conn->close();
?>

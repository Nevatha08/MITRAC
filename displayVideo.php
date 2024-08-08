<?php
require("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to get all video information
    $sql = "SELECT uVideos, Title, Description FROM videos";
    $result = mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        $videosArray = array();

        // Fetch all rows and store in an array
        while ($row = $result->fetch_assoc()) {
            $videoItem = array(
                'url' => 'http://192.168.214.213/app/' . $row['uVideos'],
                'title' => $row['Title'],
                'description' => $row['Description']
            );
            $videosArray[] = $videoItem;
        }

        // Return videos data as JSON response
        header('Content-Type: application/json');
        echo json_encode(array("videos" => $videosArray));
    } else {
        echo json_encode(array("videos" => []));
    }

    $conn->close();
} else {
    echo json_encode(array("error" => "Invalid request"));
}
?>

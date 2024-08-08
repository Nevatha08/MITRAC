<?php
require("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Directory where uploaded videos will be saved
    $uploadDir = "videos/";

    // Check if the uploaded file exists in the request
    if (isset($_FILES['uploaded_file'])) {
        $file = $_FILES['uploaded_file'];
        $fileName = basename($file['name']);
        $uploadPath = $uploadDir . $fileName;

        // Move the uploaded file to the designated directory
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Get title and description from POST request
            $title = isset($_POST['title']) ? $_POST['title'] : '';
            $description = isset($_POST['description']) ? $_POST['description'] : '';

            // Insert path, title, and description into the database using prepared statements
            $stmt = $conn->prepare("INSERT INTO videos (uVideos, Title, Description) VALUES (?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sss", $uploadPath, $title, $description);
                if ($stmt->execute()) {
                    echo "Video path, title, and description inserted into the database successfully";
                } else {
                    echo "Error inserting video path, title, and description into the database: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Error preparing the SQL statement: " . $conn->error;
            }
        } else {
            echo "Error uploading the file";
        }
    } else {
        echo "No file uploaded";
    }

    $conn->close();
} else {
    echo "Invalid request";
}
?>

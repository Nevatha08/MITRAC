<?php
require("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decode the JSON data
    $json = file_get_contents('php://input');
    $data1 = json_decode($json, true);

    // Check if "pid" key exists in the JSON data
    if (isset($data1["pid"])) {
        $pid = $data1["pid"];

        // Continue with your code...

        $sql = "SELECT * FROM appointment WHERE patient_id='$pid'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $data = array();
            while ($row = mysqli_fetch_assoc($result)) {
                // Append each row to the $data array
                $data[] = $row;
            }
            echo json_encode(array("data" => $data));
        } else {
            echo json_encode(array("message" => "Database query error"));
        }
    } else {
        echo json_encode(array("message" => "Invalid JSON data: 'pid' key not found"));
    }
} else {
    echo json_encode(array("message" => "Invalid request method"));
}
?>

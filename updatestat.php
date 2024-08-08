<?php
require("conn.php");

// Check if the request method is POST and the request contains a JSON payload
if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
    // Decode the JSON data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    print_r($data);

    
        // Check if the required fields are present in the JSON data
        if (isset($_POST["patient_id"])) {
            $patient_id = $_POST["patient_id"];
           
            $status = "pending";

            $sql = "update appointment set status='Approved' where patient_id='$patient_id'";

            if (mysqli_query($conn, $sql)) {
                $response = array('status' => 'success', 'message' => 'data inserted succesfully');
              
                echo json_encode($response);
            } else {
                $response = array('status' => 'failure', 'message' => 'data not inserted');
                echo json_encode($response);
            }
        } else {
             $response = array('status' => 'failure', 'message' => 'Missing required fields in JSON data');

            echo json_encode($response);
        }
    } else {
        $response = array('status' => 'failure', 'message' => 'Invalid JSON data');

        echo json_encode($response);
    }
?>

<?php
require("conn.php");

// Check if the request method is POST and the request contains a JSON payload
if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
    // Decode the JSON data
    $json = file_get_contents('php://input');
    $data1 = json_decode($json, true);
    print_r($data1);
    $data = array(); 
    // Perform the database query to select data from the tempappo table
    $sql = "SELECT * FROM appointment where status='pending'";
            if (mysqli_query($conn, $sql)) {
                $result = mysqli_query($conn, $sql);
                    // Initialize an array to store the results
            
                    // Fetch data from the database and add it to the array
                    $i=0;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $data[$i] = $row;
                        $i++;                    }
            
                    // Return the data as JSON
                    echo json_encode($data,JSON_PRETTY_PRINT);
                
            } else {
                $response = array('status' => 'failure', 'message' => 'data not inserted');
                echo json_encode($response);
            }
        } else {
             $response = array('status' => 'failure', 'message' => 'Missing required fields in JSON data');

            echo json_encode($response);
        }
   
?>



















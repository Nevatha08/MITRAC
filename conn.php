<?php
$conn=new mysqli("localhost","root","","nevz");
if(!$conn){
    echo mysqli_error($conn);
}
else{
    //echo "connected successfull";
}

?>
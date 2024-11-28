<?php
    $host = 'localhost';
    $user = "root";
    $password = "";
    $db = "cobaresp";

    $conn = new mysqli($host, $user, $password, $db);
    if($conn->connect_error){
        die("Connection failed: ");
    }

?>
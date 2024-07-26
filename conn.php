<?php
$con = new mysqli("localhost", "root", "", "quiz_app");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
} else {
    // echo "Connected successfully";
}
?>

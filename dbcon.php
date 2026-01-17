<?php
$HOST = "localhost";
$USERNAME = "root";
$PASSWORD = "";
$DB_NAME = "wd";
$conn = new mysqli($HOST, $USERNAME, $PASSWORD, $DB_NAME);
if($conn->connect_error){
    die("Error: ".$conn->connect_error);
}
?>
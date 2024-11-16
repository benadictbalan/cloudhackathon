<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "Ben@d!ct18";
$db_port = "3307";
$db_name = "cloudhackathon";

$conn = new mysqli($db_host,$db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully";
}

$conn->close();
?>

<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "baby_products_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<?php
session_start();
include_once '../helper/db_connection.php';
include_once '../business/login_b.php';

$login = new Login($conn);

// Check if the login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $login->authenticate($username, $password);
    if ($result == "Login Successfull") {
        header("refresh:1; url=../home.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Authentication Failed";
    }
}
?>
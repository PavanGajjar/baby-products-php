<?php
session_start();
include_once '../helper/db_connection.php';
include_once '../business/register_b.php';

$registration = new Registration($conn);

// Example usage:

// Check if the registration form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Register a new user
    $result = $registration->registerUser($username, $email, $password, $role);
    if ($result) {
        // Registration successful
        $_SESSION['success_message'] = "Registration successful. Redirecting to login page...";
        header("refresh:3; url=../login.php"); // Redirect to login page after 3 seconds
        exit();
    } else {
        // Registration failed
        $_SESSION['error_message'] = "Registration failed";
    }
}
?>
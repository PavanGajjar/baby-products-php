<?php
class Registration {
    private $conn;

    public function __construct($db_conn) {
        $this->conn = $db_conn;
    }

    // Function to register a new user
    public function registerUser($username, $email, $password, $role) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);
        return $stmt->execute();
    }
}
?>
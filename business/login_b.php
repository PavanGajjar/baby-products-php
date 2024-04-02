<?php
class Login {
    private $conn;

    public function __construct($db_conn) {
        $this->conn = $db_conn;
    }

    public function authenticate($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows >= 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
                return "Login Successfull";
            } else {
                return "Incorrect password";
            }
        } else {
            return "User not found";
        }
    }
}
?>
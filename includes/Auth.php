<?php
class Auth {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function register($first_name, $last_name, $email, $password) {
        $conn = $this->db->getConnection();
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $email = $conn->real_escape_string($email);
        $first_name = $conn->real_escape_string($first_name);
        $last_name = $conn->real_escape_string($last_name);

        $stmt = $conn->prepare('INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)');
        $role = 'student';
        $stmt->bind_param('sssss', $first_name, $last_name, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function login($email, $password) {
        $conn = $this->db->getConnection();
        $email = $conn->real_escape_string($email);

        $result = $conn->query("SELECT id, first_name, last_name, email, password, role, status FROM users WHERE email = '{$email}'");

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if ($user['status'] === 'suspended') {
                return ['success' => false, 'message' => 'Your account has been suspended.'];
            }

            if (password_verify($password, $user['password'])) {
                Session::setUser($user['id'], $user['first_name'], $user['last_name'], $user['email'], $user['role']);
                return ['success' => true, 'message' => 'Login successful'];
            }
        }
        return ['success' => false, 'message' => 'Invalid email or password'];
    }

    public function emailExists($email) {
        $conn = $this->db->getConnection();
        $email = $conn->real_escape_string($email);
        $result = $conn->query("SELECT id FROM users WHERE email = '{$email}'");
        return $result && $result->num_rows > 0;
    }
}
?>
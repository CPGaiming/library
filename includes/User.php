<?php
class User {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function getById($id) {
        $id = intval($id);
        $result = $this->conn->query("SELECT * FROM users WHERE id = {$id}");
        return $result ? $result->fetch_assoc() : null;
    }

    public function getAll($page = 1, $per_page = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $per_page;
        $result = $this->conn->query("SELECT * FROM users ORDER BY created_at DESC LIMIT {$per_page} OFFSET {$offset}");
        return $result;
    }

    public function getTotalUsers() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM users");
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function update($id, $first_name, $last_name, $email) {
        $id = intval($id);
        $first_name = $this->conn->real_escape_string($first_name);
        $last_name = $this->conn->real_escape_string($last_name);
        $email = $this->conn->real_escape_string($email);

        $stmt = $this->conn->prepare('UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?');
        $stmt->bind_param('sssi', $first_name, $last_name, $email, $id);
        return $stmt->execute();
    }

    public function suspend($id) {
        $id = intval($id);
        return $this->conn->query("UPDATE users SET status = 'suspended' WHERE id = {$id}");
    }

    public function activate($id) {
        $id = intval($id);
        return $this->conn->query("UPDATE users SET status = 'active' WHERE id = {$id}");
    }

    public function getActiveStudents() {
        $result = $this->conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'student' AND status = 'active'");
        $row = $result->fetch_assoc();
        return $row['count'];
    }
}
?>
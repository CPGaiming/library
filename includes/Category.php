<?php
class Category {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function getAll() {
        $result = $this->conn->query("SELECT * FROM categories ORDER BY name");
        return $result;
    }

    public function getById($id) {
        $id = intval($id);
        $result = $this->conn->query("SELECT * FROM categories WHERE id = {$id}");
        return $result ? $result->fetch_assoc() : null;
    }

    public function add($name, $description = '') {
        $name = $this->conn->real_escape_string($name);
        $description = $this->conn->real_escape_string($description);

        $stmt = $this->conn->prepare('INSERT INTO categories (name, description) VALUES (?, ?)');
        $stmt->bind_param('ss', $name, $description);
        return $stmt->execute();
    }

    public function update($id, $name, $description = '') {
        $id = intval($id);
        $name = $this->conn->real_escape_string($name);
        $description = $this->conn->real_escape_string($description);

        $stmt = $this->conn->prepare('UPDATE categories SET name = ?, description = ? WHERE id = ?');
        $stmt->bind_param('ssi', $name, $description, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $id = intval($id);
        return $this->conn->query("DELETE FROM categories WHERE id = {$id}");
    }
}
?>
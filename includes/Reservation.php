<?php
class Reservation {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function addReservation($user_id, $book_id) {
        $user_id = intval($user_id);
        $book_id = intval($book_id);

        // Check if already reserved
        $result = $this->conn->query("SELECT id FROM reservations WHERE user_id = {$user_id} AND book_id = {$book_id} AND status IN ('pending', 'approved')");
        if ($result && $result->num_rows > 0) {
            return ['success' => false, 'message' => 'You have already reserved this book'];
        }

        $stmt = $this->conn->prepare('INSERT INTO reservations (user_id, book_id) VALUES (?, ?)');
        $stmt->bind_param('ii', $user_id, $book_id);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Reservation created successfully'];
        }
        return ['success' => false, 'message' => 'Failed to create reservation'];
    }

    public function getReservations($user_id) {
        $user_id = intval($user_id);
        $result = $this->conn->query("SELECT r.*, b.title, b.author FROM reservations r
                                      JOIN books b ON r.book_id = b.id
                                      WHERE r.user_id = {$user_id}
                                      ORDER BY r.reservation_date DESC");
        return $result;
    }

    public function cancelReservation($reservation_id, $user_id) {
        $reservation_id = intval($reservation_id);
        $user_id = intval($user_id);

        $stmt = $this->conn->prepare('UPDATE reservations SET status = ? WHERE id = ? AND user_id = ?');
        $status = 'cancelled';
        $stmt->bind_param('sii', $status, $reservation_id, $user_id);
        return $stmt->execute();
    }

    public function approveReservation($reservation_id) {
        $reservation_id = intval($reservation_id);
        $stmt = $this->conn->prepare('UPDATE reservations SET status = ? WHERE id = ?');
        $status = 'approved';
        $stmt->bind_param('si', $status, $reservation_id);
        return $stmt->execute();
    }
}
?>
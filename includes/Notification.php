<?php
class Notification {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function addNotification($user_id, $title, $message, $type = 'general') {
        $user_id = intval($user_id);
        $title = $this->conn->real_escape_string($title);
        $message = $this->conn->real_escape_string($message);
        $type = $this->conn->real_escape_string($type);

        $stmt = $this->conn->prepare('INSERT INTO notifications (user_id, title, message, type) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('isss', $user_id, $title, $message, $type);
        return $stmt->execute();
    }

    public function getUserNotifications($user_id, $limit = 10) {
        $user_id = intval($user_id);
        $limit = intval($limit);
        $result = $this->conn->query("SELECT * FROM notifications WHERE user_id = {$user_id}
                                      ORDER BY created_at DESC LIMIT {$limit}");
        return $result;
    }

    public function getUnreadCount($user_id) {
        $user_id = intval($user_id);
        $result = $this->conn->query("SELECT COUNT(*) as count FROM notifications WHERE user_id = {$user_id} AND is_read = FALSE");
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    public function markAsRead($notification_id) {
        $notification_id = intval($notification_id);
        return $this->conn->query("UPDATE notifications SET is_read = TRUE WHERE id = {$notification_id}");
    }

    public function checkDueDates() {
        $now = date('Y-m-d H:i:s');
        $tomorrow = date('Y-m-d H:i:s', time() + 24 * 60 * 60);

        // Get books due tomorrow
        $result = $this->conn->query("SELECT b.id, b.user_id, books.title, b.due_date FROM borrowings b
                                      JOIN books ON b.book_id = books.id
                                      WHERE b.status = 'borrowed' AND b.due_date BETWEEN '{$now}' AND '{$tomorrow}'");

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $this->addNotification($row['user_id'], 'Book Due Soon', 'The book "' . $row['title'] . '" is due on ' . $row['due_date'], 'due_date');
            }
        }
    }
}
?>
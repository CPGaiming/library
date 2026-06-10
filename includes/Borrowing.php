<?php
class Borrowing {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function borrowBook($user_id, $book_id) {
        $user_id = intval($user_id);
        $book_id = intval($book_id);

        // Check available copies
        $result = $this->conn->query("SELECT available_copies FROM books WHERE id = {$book_id}");
        $book = $result->fetch_assoc();

        if ($book['available_copies'] <= 0) {
            return ['success' => false, 'message' => 'Book is not available'];
        }

        // Check if already borrowed
        $result = $this->conn->query("SELECT id FROM borrowings WHERE user_id = {$user_id} AND book_id = {$book_id} AND status = 'borrowed'");
        if ($result && $result->num_rows > 0) {
            return ['success' => false, 'message' => 'You have already borrowed this book'];
        }

        $due_date = date('Y-m-d H:i:s', time() + BORROW_DURATION);
        $stmt = $this->conn->prepare('INSERT INTO borrowings (user_id, book_id, due_date, status) VALUES (?, ?, ?, ?)');
        $status = 'borrowed';
        $stmt->bind_param('iiss', $user_id, $book_id, $due_date, $status);

        if ($stmt->execute()) {
            // Update available copies
            $this->conn->query("UPDATE books SET available_copies = available_copies - 1 WHERE id = {$book_id}");
            return ['success' => true, 'message' => 'Book borrowed successfully'];
        }
        return ['success' => false, 'message' => 'Failed to borrow book'];
    }

    public function returnBook($borrowing_id, $user_id) {
        $borrowing_id = intval($borrowing_id);
        $user_id = intval($user_id);

        $result = $this->conn->query("SELECT book_id FROM borrowings WHERE id = {$borrowing_id} AND user_id = {$user_id}");
        if (!$result || $result->num_rows === 0) {
            return ['success' => false, 'message' => 'Borrowing record not found'];
        }

        $row = $result->fetch_assoc();
        $book_id = $row['book_id'];

        $return_date = date('Y-m-d H:i:s');
        $stmt = $this->conn->prepare('UPDATE borrowings SET return_date = ?, status = ? WHERE id = ?');
        $status = 'returned';
        $stmt->bind_param('ssi', $return_date, $status, $borrowing_id);

        if ($stmt->execute()) {
            // Update available copies
            $this->conn->query("UPDATE books SET available_copies = available_copies + 1 WHERE id = {$book_id}");
            return ['success' => true, 'message' => 'Book returned successfully'];
        }
        return ['success' => false, 'message' => 'Failed to return book'];
    }

    public function getBorrowedBooks($user_id) {
        $user_id = intval($user_id);
        $result = $this->conn->query("SELECT b.*, books.title, books.author FROM borrowings b
                                      JOIN books ON b.book_id = books.id
                                      WHERE b.user_id = {$user_id} AND b.status = 'borrowed'
                                      ORDER BY b.due_date ASC");
        return $result;
    }

    public function getOverdueBooks() {
        $now = date('Y-m-d H:i:s');
        $result = $this->conn->query("SELECT b.id, b.user_id, b.book_id, b.due_date, books.title FROM borrowings b
                                      JOIN books ON b.book_id = books.id
                                      WHERE b.status = 'borrowed' AND b.due_date < '{$now}'");
        return $result;
    }
}
?>
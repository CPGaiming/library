<?php
class Book {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function getBooks($page = 1, $per_page = ITEMS_PER_PAGE, $search = '', $category = '', $sort = 'title') {
        $offset = ($page - 1) * $per_page;
        $search = $this->conn->real_escape_string($search);
        $category = intval($category);

        $where = "WHERE 1=1";
        if ($search) {
            $where .= " AND (books.title LIKE '%{$search}%' OR books.author LIKE '%{$search}%' OR books.isbn LIKE '%{$search}%')";
        }
        if ($category > 0) {
            $where .= " AND books.category_id = {$category}";
        }

        $order_by = 'books.title ASC';
        if ($sort === 'author') {
            $order_by = 'books.author ASC';
        } elseif ($sort === 'newest') {
            $order_by = 'books.created_at DESC';
        } elseif ($sort === 'oldest') {
            $order_by = 'books.created_at ASC';
        }

        $query = "SELECT books.*, categories.name as category_name FROM books
                  LEFT JOIN categories ON books.category_id = categories.id
                  {$where}
                  ORDER BY {$order_by}
                  LIMIT {$per_page} OFFSET {$offset}";

        return $this->conn->query($query);
    }

    public function getTotalBooks($search = '', $category = '') {
        $search = $this->conn->real_escape_string($search);
        $category = intval($category);

        $where = "WHERE 1=1";
        if ($search) {
            $where .= " AND (books.title LIKE '%{$search}%' OR books.author LIKE '%{$search}%' OR books.isbn LIKE '%{$search}%')";
        }
        if ($category > 0) {
            $where .= " AND books.category_id = {$category}";
        }

        $result = $this->conn->query("SELECT COUNT(*) as total FROM books {$where}");
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function getBookById($id) {
        $id = intval($id);
        $result = $this->conn->query("SELECT books.*, categories.name as category_name FROM books
                                       LEFT JOIN categories ON books.category_id = categories.id
                                       WHERE books.id = {$id}");
        return $result ? $result->fetch_assoc() : null;
    }

    public function getFeaturedBooks($limit = 8) {
        $limit = intval($limit);
        $result = $this->conn->query("SELECT books.*, categories.name as category_name FROM books
                                       LEFT JOIN categories ON books.category_id = categories.id
                                       ORDER BY RAND()
                                       LIMIT {$limit}");
        return $result;
    }

    public function addBook($title, $author, $isbn, $description, $category_id, $publication_year, $total_copies) {
        $title = $this->conn->real_escape_string($title);
        $author = $this->conn->real_escape_string($author);
        $isbn = $this->conn->real_escape_string($isbn);
        $description = $this->conn->real_escape_string($description);
        $category_id = intval($category_id);
        $publication_year = intval($publication_year);
        $total_copies = intval($total_copies);

        $stmt = $this->conn->prepare('INSERT INTO books (title, author, isbn, description, category_id, publication_year, total_copies, available_copies) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssssiiii', $title, $author, $isbn, $description, $category_id, $publication_year, $total_copies, $total_copies);
        return $stmt->execute();
    }

    public function updateBook($id, $title, $author, $isbn, $description, $category_id, $publication_year, $total_copies) {
        $id = intval($id);
        $title = $this->conn->real_escape_string($title);
        $author = $this->conn->real_escape_string($author);
        $isbn = $this->conn->real_escape_string($isbn);
        $description = $this->conn->real_escape_string($description);
        $category_id = intval($category_id);
        $publication_year = intval($publication_year);
        $total_copies = intval($total_copies);

        $stmt = $this->conn->prepare('UPDATE books SET title=?, author=?, isbn=?, description=?, category_id=?, publication_year=?, total_copies=? WHERE id=?');
        $stmt->bind_param('ssssiiii', $title, $author, $isbn, $description, $category_id, $publication_year, $total_copies, $id);
        return $stmt->execute();
    }

    public function deleteBook($id) {
        $id = intval($id);
        return $this->conn->query("DELETE FROM books WHERE id = {$id}");
    }
}
?>
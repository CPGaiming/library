<?php
class Database {
    private $connection;
    private $host;
    private $db_name;
    private $db_user;
    private $db_pass;

    public function __construct() {
        $this->host = DB_HOST;
        $this->db_name = DB_NAME;
        $this->db_user = DB_USER;
        $this->db_pass = DB_PASS;
        $this->connect();
    }

    private function connect() {
        $this->connection = new mysqli($this->host, $this->db_user, $this->db_pass, $this->db_name);

        if ($this->connection->connect_error) {
            die('Database connection failed: ' . $this->connection->connect_error);
        }

        $this->connection->set_charset('utf8mb4');
    }

    public function getConnection() {
        return $this->connection;
    }

    public function query($sql) {
        $result = $this->connection->query($sql);
        if (!$result && $this->connection->error) {
            error_log('Database Error: ' . $this->connection->error . ' SQL: ' . $sql);
        }
        return $result;
    }

    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }

    public function escape($data) {
        return $this->connection->real_escape_string($data);
    }

    public function lastInsertId() {
        return $this->connection->insert_id;
    }

    public function affectedRows() {
        return $this->connection->affected_rows;
    }

    public function close() {
        $this->connection->close();
    }
}
?>
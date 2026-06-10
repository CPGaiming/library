<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'unilibrary');

// Application Configuration
define('SITE_NAME', 'UniLibrary');
define('BASE_URL', 'http://localhost:8000/');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('ASSETS_PATH', BASE_URL . 'assets/');

// Session Configuration
define('SESSION_TIMEOUT', 3600);

// Pagination
define('ITEMS_PER_PAGE', 12);

// Role Constants
define('ROLE_STUDENT', 'student');
define('ROLE_ADMIN', 'admin');

// Book Statuses
define('AVAILABLE', 'Available');
define('UNAVAILABLE', 'Unavailable');

// Reservation Statuses
define('RESERVATION_PENDING', 'pending');
define('RESERVATION_APPROVED', 'approved');
define('RESERVATION_CANCELLED', 'cancelled');

// Borrowing Duration (14 days)
define('BORROW_DURATION', 14 * 24 * 60 * 60);
?>
<?php
// Utility Functions

function redirect($url) {
    header('Location: ' . $url);
    exit();
}

function requireLogin() {
    if (!Session::isLoggedIn()) {
        redirect(BASE_URL . 'login.php');
    }
}

function requireAdmin() {
    if (!Session::isAdmin()) {
        redirect(BASE_URL . 'index.php');
    }
}

function displayError($message) {
    return '<div class="alert alert-error">' . htmlspecialchars($message) . '</div>';
}

function displaySuccess($message) {
    return '<div class="alert alert-success">' . htmlspecialchars($message) . '</div>';
}

function formatDate($date) {
    return date('M d, Y', strtotime($date));
}

function formatDateTime($datetime) {
    return date('M d, Y H:i', strtotime($datetime));
}

function getTimeUntilDue($due_date) {
    $now = time();
    $due = strtotime($due_date);
    $diff = $due - $now;

    if ($diff < 0) {
        $days = ceil(abs($diff) / (24 * 60 * 60));
        return "<span class='overdue'>{$days} days overdue</span>";
    }

    $days = floor($diff / (24 * 60 * 60));
    if ($days <= 3) {
        return "<span class='due-soon'>{$days} days remaining</span>";
    }
    return "{$days} days remaining";
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function uploadFile($file, $upload_dir) {
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $filename = $file['name'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        return ['success' => false, 'message' => 'Invalid file format'];
    }

    $new_filename = uniqid() . '.' . $ext;
    $upload_path = $upload_dir . $new_filename;

    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        return ['success' => true, 'filename' => $new_filename];
    }
    return ['success' => false, 'message' => 'Failed to upload file'];
}
?>
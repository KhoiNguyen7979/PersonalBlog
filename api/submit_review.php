<?php
/**
 * api/submit_review.php
 * Lưu review vào database
 */
// === API: Gửi review/đánh giá ===
// Lưu review từ form modal vào bảng Reviews
session_start();
require_once __DIR__ . '/../php/mySQLconnect.php';

header('Content-Type: application/json; charset=utf-8');

// Chỉ chấp nhận POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if (empty($full_name) || empty($email) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
    exit;
}

$stmt = $connect->prepare("INSERT INTO Reviews (HoTen, Email, NoiDung) VALUES (?, ?, ?)");
if ($stmt) {
    $stmt->bind_param("sss", $full_name, $email, $message);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement.']);
}

$connect->close();
?>

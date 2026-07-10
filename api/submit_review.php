<?php
/**
 * api/submit_review.php
 * Lưu review vào database
 */
session_start();
require_once '../src/views/mySQLconnect.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if (empty($full_name) || empty($email) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin.']);
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

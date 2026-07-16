<?php
/**
 * Lưu review vào database
 */
// Lưu review từ pop-up reviews vào bảng Reviews
session_start();
//thiêt lập kết nối CSDL
require_once __DIR__ . '/../php/mySQLconnect.php';

header('Content-Type: application/json; charset=utf-8');

// Chỉ chấp nhận POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}
//Trim các trường tên, tin nhắn
$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if (empty($full_name) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'You have not signed in yet, please sign in to submit review']);
    exit;
}

if (empty($message)) {
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

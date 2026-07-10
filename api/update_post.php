<?php
session_start();
require_once '../src/views/mySQLconnect.php';
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$id       = intval($_POST['post_id'] ?? 0);
$title    = trim($_POST['title'] ?? '');
$summary  = trim($_POST['summary'] ?? '');
$category = trim($_POST['category'] ?? '');
$content  = trim($_POST['content'] ?? '');
$email    = $_SESSION['email'];

if (empty($title) || empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin.']);
    exit;
}

$wordCount = str_word_count(strip_tags($content));
$readTime = max(1, ceil($wordCount / 200));

$stmt = $connect->prepare("
    UPDATE BaiViet 
    SET TieuDe=?, NoiDung=?, TomTat=?, ThoiGianDoc=?, ID_The_Loai=? 
    WHERE ID_BaiViet=? AND ID_NguoiDung=?
");
$stmt->bind_param("sssssis", $title, $content, $summary, $readTime, $category, $id, $email);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi CSDL.']);
}
?>
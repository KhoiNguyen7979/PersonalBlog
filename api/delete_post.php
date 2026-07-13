<?php
// === API: Xóa bài viết ===
// Xóa bài viết kèm ảnh và lượt thích liên quan. Chỉ chủ bài viết hoặc admin mới được xóa.
session_start();
require_once __DIR__ . '/../php/mySQLconnect.php';
header('Content-Type: application/json; charset=utf-8');

// Only accept POST (fetch uses method: 'POST')
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}

if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid id']);
    exit;
}

$userEmail = $_SESSION['email'];
$isAdmin = isset($_SESSION['vaitro']) && $_SESSION['vaitro'] === 'admin';

// Verify ownership
$stmt = $connect->prepare('SELECT ID_NguoiDung FROM BaiViet WHERE ID_BaiViet = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$owner = $stmt->get_result()->fetch_assoc();
if (!$owner) {
    echo json_encode(['success' => false, 'message' => 'Post not found']);
    exit;
}
if ($owner['ID_NguoiDung'] !== $userEmail && !$isAdmin) {
    echo json_encode(['success' => false, 'message' => 'No permission']);
    exit;
}

// Delete related likes
$stmt = $connect->prepare('DELETE FROM ThichBaiViet WHERE ID_BaiViet = ?');
$stmt->bind_param('i', $id);
$stmt->execute();

// Delete related pics
$stmt = $connect->prepare('DELETE FROM Pics WHERE ID_BaiViet = ?');
$stmt->bind_param('i', $id);
$stmt->execute();

// Delete the post
if ($isAdmin) {
    $stmt = $connect->prepare('DELETE FROM BaiViet WHERE ID_BaiViet = ?');
    $stmt->bind_param('i', $id);
} else {
    $stmt = $connect->prepare('DELETE FROM BaiViet WHERE ID_BaiViet = ? AND ID_NguoiDung = ?');
    $stmt->bind_param('is', $id, $userEmail);
}
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Unable to delete']);
}
$connect->close();
?>
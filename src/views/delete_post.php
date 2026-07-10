<?php
session_start();
require_once '../src/views/mySQLconnect.php';
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$email = $_SESSION['email'];

$connect->begin_transaction();
try {
    // Chỉ cho phép xóa nếu bài viết đó thuộc về người dùng đang đăng nhập
    $stmt = $connect->prepare("DELETE FROM BaiViet WHERE ID_BaiViet = ? AND ID_NguoiDung = ?");
    $stmt->bind_param("is", $id, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $connect->commit();
        echo json_encode(['success' => true]);
    } else {
        $connect->rollback();
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy bài viết hoặc bạn không có quyền xóa.']);
    }
} catch (Exception $e) {
    $connect->rollback();
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
?>
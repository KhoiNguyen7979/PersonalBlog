<?php
// === API: Bật/tắt like bài viết ===
// Nếu đã like thì unlike, nếu chưa thì like. Trả về số like mới nhất.
session_start();
//thiết lập kết nối CSDL
require_once __DIR__ . '/../php/mySQLconnect.php';

header('Content-Type: application/json');

// Kiểm tra đăng nhập (đăng nhập mới like được)
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to like a post.']);
    exit;
}

$email = $_SESSION['email'];
$postId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Kiểm tra xem đã like chưa
$stmt = $connect->prepare("SELECT * FROM ThichBaiViet WHERE ID_BaiViet = ? AND Email = ?");
$stmt->bind_param("is", $postId, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Đã like -> Bỏ like (Unlike)
    $del = $connect->prepare("DELETE FROM ThichBaiViet WHERE ID_BaiViet = ? AND Email = ?");
    $del->bind_param("is", $postId, $email);
    $del->execute();
    $status = 'unliked';
} else {
    // Chưa like -> Thêm like
    $ins = $connect->prepare("INSERT INTO ThichBaiViet (ID_BaiViet, Email) VALUES (?, ?)");
    $ins->bind_param("is", $postId, $email);
    $ins->execute();
    $status = 'liked';
}

// Đếm tổng số like hiện tại trả về giao diện
$countStmt = $connect->prepare("SELECT COUNT(*) as total FROM ThichBaiViet WHERE ID_BaiViet = ?");
$countStmt->bind_param("i", $postId);
$countStmt->execute();
$likes = $countStmt->get_result()->fetch_assoc()['total'];

echo json_encode(['success' => true, 'status' => $status, 'likes' => $likes]);
?>
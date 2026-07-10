<?php
session_start();
require_once '../src/views/mySQLconnect.php';
header('Content-Type: application/json; charset=utf-8');

// Bật thông báo lỗi chi tiết của MySQLi để dễ debug
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập.']);
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$email = $_SESSION['email'];

try {
    // Bắt đầu Transaction
    $connect->begin_transaction();

    // 1. Kiểm tra xem bài viết có tồn tại và thuộc về người dùng này không
    $check = $connect->prepare("SELECT ID_BaiViet FROM BaiViet WHERE ID_BaiViet = ? AND ID_NguoiDung = ?");
    $check->bind_param("is", $id, $email);
    $check->execute();
    if ($check->get_result()->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Bài viết không tồn tại hoặc bạn không có quyền xóa!']);
        exit;
    }

    // 2. Xóa ảnh liên quan trong bảng Pics trước (tránh lỗi khóa ngoại)
    $delPics = $connect->prepare("DELETE FROM Pics WHERE ID_BaiViet = ?");
    $delPics->bind_param("i", $id);
    $delPics->execute();

    // 3. Xóa lượt thích trong bảng ThichBaiViet (tránh lỗi khóa ngoại)
    $delLikes = $connect->prepare("DELETE FROM ThichBaiViet WHERE ID_BaiViet = ?");
    $delLikes->bind_param("i", $id);
    $delLikes->execute();

    // 4. Cuối cùng mới xóa bài viết trong bảng BaiViet
    $stmt = $connect->prepare("DELETE FROM BaiViet WHERE ID_BaiViet = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Xác nhận lưu thay đổi
    $connect->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    // Nếu có lỗi ở bất kỳ bước nào, hoàn tác lại toàn bộ (Rollback)
    $connect->rollback();
    echo json_encode(['success' => false, 'message' => 'Lỗi CSDL: ' . $e->getMessage()]);
}
?>
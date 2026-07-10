<?php
/**
 * api/update_profile.php
 * Cập nhật thông tin hồ sơ người dùng (avatar, mô tả).
 */
session_start();
require_once '../src/views/mySQLconnect.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập.']);
    exit;
}

$email  = $_SESSION['email'];
$action = $_POST['action'] ?? '';

if ($action === 'update_bio') {
    // Cập nhật mô tả
    $mota = trim($_POST['mota'] ?? '');
    $stmt = $connect->prepare("UPDATE NguoiDung SET MoTa = ? WHERE Email = ?");
    $stmt->bind_param("ss", $mota, $email);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Đã cập nhật mô tả.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi cập nhật mô tả.']);
    }

} elseif ($action === 'update_avatar') {
    // Upload avatar mới
    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Lỗi upload file.']);
        exit;
    }

    $file = $_FILES['avatar'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Chỉ chấp nhận file ảnh (jpg, png, gif, webp).']);
        exit;
    }

    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxSize) {
        echo json_encode(['success' => false, 'message' => 'File ảnh quá lớn (tối đa 5MB).']);
        exit;
    }

    $extMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
    $ext    = $extMap[$mime];
    $data   = file_get_contents($file['tmp_name']);

   $stmt = $connect->prepare("UPDATE NguoiDung SET Avatar = ?, DuoiAnhAvatar = ? WHERE Email = ?");
    
    $null = NULL; // Bắt buộc phải có một biến trống đại diện cho cột BLOB
    
    // Đổi "sss" thành "bss" (b = blob, s = string)
    $stmt->bind_param("bss", $null, $ext, $email);
    
    // Gửi dữ liệu ảnh vào tham số thứ 0 (tức là dấu ? đầu tiên - Avatar)
    $stmt->send_long_data(0, $data); 
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Đã cập nhật ảnh đại diện.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi lưu ảnh vào database.']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Action không hợp lệ.']);
}

$connect->close();
?>

<?php
/**
 * api/update_profile.php
 * Cập nhật thông tin hồ sơ người dùng (avatar, mô tả).
 */
// === API: Cập nhật hồ sơ (avatar hoặc bio) ===
// Nhận action='update_avatar' hoặc action='update_bio'
session_start();
require_once __DIR__ . '/../php/mySQLconnect.php';

header('Content-Type: application/json; charset=utf-8');

// Kiểm tra đăng nhập
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit;
}

$email  = $_SESSION['email'];
$action = $_POST['action'] ?? '';
$isAdmin = isset($_SESSION['vaitro']) && $_SESSION['vaitro'] === 'admin';

if ($action === 'update_bio') {
    if ($isAdmin) {
        echo json_encode(['success' => false, 'message' => 'Admin account cannot edit this information.']);
        exit;
    }
    // Cập nhật mô tả
    $mota = trim($_POST['mota'] ?? '');
    $stmt = $connect->prepare("UPDATE NguoiDung SET MoTa = ? WHERE Email = ?");
    $stmt->bind_param("ss", $mota, $email);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Description updated.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update description.']);
    }

} elseif ($action === 'update_avatar') {
    // Admin không được đổi avatar
    $vaitro = $_SESSION['vaitro'] ?? 'user';
    if ($vaitro === 'admin') {
        echo json_encode(['success' => false, 'message' => 'Admin account cannot change avatar.']);
        exit;
    }

    // Upload avatar mới
    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'File upload error.']);
        exit;
    }

    $file = $_FILES['avatar'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Only image files are accepted (jpg, png, gif, webp).']);
        exit;
    }

    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxSize) {
        echo json_encode(['success' => false, 'message' => 'File too large (max 5MB).']);
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
        echo json_encode(['success' => true, 'message' => 'Avatar updated.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save image to database.']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}

$connect->close();
?>

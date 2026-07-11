<?php
session_start();
header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Bạn chưa đăng nhập.']);
    exit;
}

require_once __DIR__ . '/../php/mySQLconnect.php';

$email = $_SESSION['email'];
$username = $_POST['username'] ?? '';
$fullname = $_POST['fullname'] ?? '';
$password = $_POST['password'] ?? '';

// Validate cơ bản
if (empty($username) || empty($fullname)) {
    echo json_encode(['status' => 'error', 'message' => 'Tên đăng nhập và Họ tên không được để trống.']);
    exit;
}

try {
    if (!empty($password)) {
        // Cập nhật cả thông tin và mật khẩu
        // Lưu ý: Tùy vào hệ thống của bạn, có thể cần đổi md5() thành password_hash() nếu bạn dùng chuẩn mã hóa mới
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); 
        
        $stmt = $connect->prepare("UPDATE NguoiDung SET HoTenNguoiDung = ?, TenDangNhap = ?, MatKhau = ? WHERE Email = ?");
        $stmt->bind_param("ssss", $fullname, $username, $hashed_password, $email);
    } else {
        // Chỉ cập nhật thông tin, không đổi mật khẩu
        $stmt = $connect->prepare("UPDATE NguoiDung SET HoTenNguoiDung = ?, TenDangNhap = ? WHERE Email = ?");
        $stmt->bind_param("sss", $fullname, $username, $email);
    }

    if ($stmt->execute()) {
        $_SESSION['hoten'] = $username;
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Không thể cập nhật thông tin. Vui lòng thử lại.']);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi máy chủ: ' . $e->getMessage()]);
}

$connect->close();
?>
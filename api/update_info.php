<?php
session_start();
header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Bạn chưa đăng nhập.']);
    exit;
}

require_once '../src/views/mySQLconnect.php';

$email = $_SESSION['email'];
$username = $_POST['username'] ?? '';
$fullname = $_POST['fullname'] ?? '';
$password = $_POST['password'] ?? '';
$mota     = $_POST['mota'] ?? ''; // Nhận thêm dữ liệu mô tả

// Validate cơ bản
if (empty($username) || empty($fullname)) {
    echo json_encode(['status' => 'error', 'message' => 'Tên đăng nhập và Họ tên không được để trống.']);
    exit;
}

try {
    if (!empty($password)) {
        // Cập nhật thông tin, mô tả và mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); 
        
        // Thêm trường MoTa vào câu lệnh SQL
        $stmt = $connect->prepare("UPDATE NguoiDung SET HoTenNguoiDung = ?, TenDangNhap = ?, MoTa = ?, MatKhau = ? WHERE Email = ?");
        $stmt->bind_param("sssss", $fullname, $username, $mota, $hashed_password, $email);
    } else {
        // Chỉ cập nhật thông tin và mô tả, không đổi mật khẩu
        $stmt = $connect->prepare("UPDATE NguoiDung SET HoTenNguoiDung = ?, TenDangNhap = ?, MoTa = ? WHERE Email = ?");
        $stmt->bind_param("ssss", $fullname, $username, $mota, $email);
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
<?php
// API: Cập nhật thông tin cá nhân ===
// Sửa tên hiển thị, tên đăng nhập và mật khẩu. Admin không được sửa các thông tin của Admin trên trang web nhằm bảo mật (chỉ nên sửa thông qua phpMyAdmin).
session_start();
header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'You are not logged in.']);
    exit;
}
//thiết lập kết nối đến CSDL
require_once __DIR__ . '/../php/mySQLconnect.php';

$email = $_SESSION['email'];
$isAdmin = isset($_SESSION['vaitro']) && $_SESSION['vaitro'] === 'admin';

if ($isAdmin) {
    echo json_encode(['status' => 'error', 'message' => 'Admin account cannot edit this information.']);
    exit;
}

$username = $_POST['username'] ?? '';
$fullname = $_POST['fullname'] ?? '';
$password = $_POST['password'] ?? '';

//Xác thực nếu chưa có tên đăng nhập hoặc họ tên người dùng
if (empty($username) || empty($fullname)) {
    echo json_encode(['status' => 'error', 'message' => 'Username and Full Name cannot be empty.']);
    exit;
}

try {
    //Nếu có mật khẩu, cập nhật các trường khác+mật khẩu
    if (!empty($password)) {
        // Cập nhật cả thông tin và mật khẩu
        // password_hash() tự động tạo salt và mã hóa bằng bcrypt (PASSWORD_DEFAULT)
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
        echo json_encode(['status' => 'error', 'message' => 'Failed to update information. Please try again.']);
    }

    $stmt->close();
} catch (Exception $e) {
    // Nếu ko kết nối được server
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}

$connect->close();
?>
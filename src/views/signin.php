<?php
$show_modal = false;
$modal_title = "";
$modal_message = "";
// Nếu trạng thái của người dùng là đã đăng nhập thành công thì tự động chuyển hướng qua trang chủ
if (isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Thiết lập kết nối CSDL
require_once("mySQLconnect.php");

// Xử lý khi người dùng gửi thông tin đăng nhập
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signin'])) {
    $email = $_POST['email'];
    $matkhau_dabam = sha1($_POST['password']); 

    $sql = "SELECT Email, HoTenNguoiDung FROM nguoidung WHERE Email = ? AND MatKhau = ?";
    $stmt = $connect->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ss", $email, $matkhau_dabam);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Lưu trạng thái vào biến session
            $_SESSION['email'] = $user['Email'];
            $_SESSION['hoten'] = $user['HoTenNguoiDung'];
            
            header("Location: index.php");
            exit();
        } else {
            $show_modal = true;
            $modal_title = "Oops! Lỗi đăng nhập";
            $modal_message = "Bạn đã nhập email hoặc mật khẩu chưa chính xác, vui lòng nhập lại";
        }
        $stmt->close();
    } else {
         $show_modal = true;
            $modal_title = "Oops! Lỗi kết nối CSDL";
            $modal_message = "Cơ sở dữ liệu hệ thống hiện không phản hồi, vui lòng thử lại sau";
    }
}
$connect->close();
?>
<link rel="stylesheet" href="public/css/signin.css">

<div class="container">

    <!-- LEFT -->
    <div class="left-panel">
        <h1>Welcome to</h1>
        <a href="index.php"><img src="public/images/bloggerZ.png" class="logo" alt="BloggerZ Logo"></a>
    </div>

    <!-- RIGHT -->
    <div class="right-panel">

        <h2>Sign In</h2>

        <form action="" method="POST">

            <input type="text" name="email" placeholder="Email" required>

            <input type="password" name="password" placeholder="Password" required>

            <div class="remember">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>

            <div class="button">
                <button type="submit" class="signin-btn" name="signin">Sign In</button>
                <a href="?page=signup" class="signup">Sign Up →</a>
            </div>

            <div class="forgot-password">
                <a href="#">Forgot Password?</a>
            </div>

        </form>

    </div>

</div>
<?php if($show_modal): ?>
<div class="modal-overlay" id="resultModal">
    <div class="modal-box">
        <span class="close-btn" onclick="document.getElementById('resultModal').style.display='none'"> &times; </span>
        
        <h2><?= $modal_title ?></h2>
        <p><?= $modal_message ?></p>
        
        <button class="ok-btn" onclick="document.getElementById('resultModal').style.display='none'">OK</button><br><br>
        <a href="?page=signup" id=signup_direct>Bạn chưa có tài khoản?</a>
    </div>
</div>
<?php endif; ?>
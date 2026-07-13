<?php
// === TRANG ĐĂNG NHẬP ===
// Xử lý đăng nhập (POST) + Giao diện 2 panel (logo trái, form phải) + Modal lỗi
$show_modal = false;
$modal_title = "";
$modal_message = "";
// Nếu trạng thái của người dùng là đã đăng nhập thành công thì tự động chuyển hướng qua trang chủ
if (isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Thiết lập kết nối CSDL
require_once(__DIR__ . '/../mySQLconnect.php');

// Xử lý khi người dùng gửi thông tin đăng nhập
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signin'])) {
    $email = $_POST['email'];
    $matkhau_dabam = sha1($_POST['password']); 

    $sql = "SELECT Email, TenDangNhap, VaiTro FROM nguoidung WHERE Email = ? AND MatKhau = ?";
    $stmt = $connect->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ss", $email, $matkhau_dabam);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Lưu trạng thái vào biến session
            $_SESSION['email'] = $user['Email'];
            $_SESSION['hoten'] = $user['TenDangNhap'];
            $_SESSION['vaitro'] = $user['VaiTro'];
            
            header("Location: index.php?toast=" . urlencode("Login successful!"));
            exit();
        } else {
            $show_modal = true;
            $modal_title = "Oops! Login Error";
            $modal_message = "Incorrect email or password. Please try again.";
        }
        $stmt->close();
    } else {
         $show_modal = true;
            $modal_title = "Oops! Database Error";
            $modal_message = "The database is not responding. Please try again later.";
    }
}
$connect->close();
?>
<link rel="stylesheet" href="public/css/signin.css">

<div class="container">

    <!-- LEFT -->
    <div class="left-panel">
        <h1>Welcome back to</h1>
        <a href="index.php"><img src="public/images/bloggerZ.png" class="logo" alt="BloggerZ Logo"></a>
    </div>

    <!-- RIGHT -->
    <div class="right-panel">

        <h2>Sign In</h2>

        <form action="" method="POST">

            <input type="text" name="email" placeholder="Email" required>

            <div class="password-wrap">
                <input type="password" name="password" placeholder="Password" required>
                <button type="button" class="toggle-pw" aria-label="Show password">
                    <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>

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
        <span class="close-btn" onclick="document.getElementById('resultModal').style.display='none'">&times;</span>
        <h2><?= $modal_title ?></h2>
        <p><?= $modal_message ?></p>
        <div class="modal-actions">
            <button class="ok-btn" onclick="document.getElementById('resultModal').style.display='none'">OK</button>
            <a href="?page=signup" class="direct-link">Don't have an account?</a>
        </div>
    </div>
</div>
<?php endif; ?>
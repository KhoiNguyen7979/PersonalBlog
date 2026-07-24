<?php 
// === TRANG ĐĂNG KÝ ===
// Xử lý đăng ký (phương thức POST)
    // Thiết lập kết nối CSDL
    require_once(__DIR__ . '/../mySQLconnect.php');
    $show_modal = false;
    $modal_title = "";
    $modal_message = "";
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])){
        $ho_ten = $_POST['fullname'];
        $email = $_POST['email'];
        $user_name = $_POST['username'];
        $matkhau = $_POST['password'];
        $xac_nhan_mat_khau = $_POST['confirm_password'];
        
        //Băm mật khẩu để bảo mật
        $matkhau_dabam = password_hash($xac_nhan_mat_khau, PASSWORD_DEFAULT);
        $sql_command = "INSERT INTO nguoidung (Email, HoTenNguoiDung, TenDangNhap, MatKhau) VALUES (?, ?, ?, ?)";
        $stmt = $connect->prepare($sql_command);
        
        if ($stmt) {
            $stmt->bind_param("ssss", $email, $ho_ten, $user_name, $matkhau_dabam);
            
            // để trong vòng try catch để bắt lỗi ngoại lệ
            try {
                if ($stmt->execute()) {
                    $show_modal = true;
                        $modal_title = "Registration successful!";
                        $modal_message = "Account " . htmlspecialchars($user_name) . " has been created. You can sign in now.";
                }
            } catch (mysqli_sql_exception $e) {
                // Nếu đã có tài khoản với email đó trong hệ thống, thông báo đến người dùng là không đăng ký được
                if ($e->getCode() === 1062) {
                    $show_modal = true;
                        $modal_title = "Oops! Registration Error";
                        $modal_message = "This email or username already exists. Please use a different one.";

                }
            }
            $stmt->close();
        }
    }
    $connect->close();
?>

<link rel="stylesheet" href="public/css/signup.css">

<div class="container">

    <!-- Hình bên trái -->
    <div class="left-panel">
        <h1>Welcome to</h1>
        <a href="index.php"><img src="public/images/bloggerZ.png" class="logo" alt="BloggerZ Logo"></a>
    </div>

    <!-- Form đăng ký bên phải -->
    <div class="right-panel">

        <h3>Sign Up</h3>
        <form action="" method="POST">

            <input type="text" name="fullname" placeholder="Full name" required>

            <input type="email" name="email" placeholder="Email" required>

            <input type="text" name="username" placeholder="Username" required>

            <div class="password-wrap">
                <input type="password" name="password" placeholder="Password" required>
                <button type="button" class="toggle-pw" aria-label="Show password">
                    <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>

            <div class="password-wrap">
                <input type="password" name="confirm_password" placeholder="Repeat Password" required>
                <button type="button" class="toggle-pw" aria-label="Show password">
                    <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>

            <div class="agree">
                <input type="checkbox" name="agree" required>
                <span>
                    I agree to the
                    <a href="#">Terms of Use</a>
                </span>
            </div>
            <div class="buttons">
                <button type="submit" class="signup-btn" name="signup">Sign Up</button>
                <a href="?page=signin" class="signin">Sign In→</a>
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
            <!-- hiên thị các nội dung pop - up (đăng ký thành công/không thành công) -->
            <?php if($modal_title != "Đăng ký thành công!" && $modal_title != "Registration successful!"):?>
                <button class="ok-btn" onclick="document.getElementById('resultModal').style.display='none'">OK</button>
            <?php endif; ?>
            <a href="?page=signin" class="direct-link">Go to Sign In</a>
        </div>
    </div>
</div>
<?php endif; ?>
<script src="public/js/signup.js"></script>
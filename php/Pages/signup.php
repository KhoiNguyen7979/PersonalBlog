<?php 
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
        

        $matkhau_dabam = sha1($xac_nhan_mat_khau);
        $sql_command = "INSERT INTO nguoidung (Email, HoTenNguoiDung, TenDangNhap, MatKhau) VALUES (?, ?, ?, ?)";
        $stmt = $connect->prepare($sql_command);
        
        if ($stmt) {
            $stmt->bind_param("ssss", $email, $ho_ten, $user_name, $matkhau_dabam);
            
            // Wrap execution in try...catch to intercept the duplicate entry exception
            try {
                if ($stmt->execute()) {
                    $show_modal = true;
                        $modal_title = "Đăng ký thành công!";
                        $modal_message = "Tài khoản " . htmlspecialchars($user_name) . " đã được tạo. Bạn có thể đăng nhập ngay bây giờ.";
                }
            } catch (mysqli_sql_exception $e) {
                // Check if the error code is 1062 (MySQL's code for Duplicate Entry)
                if ($e->getCode() === 1062) {
                    $show_modal = true;
                        $modal_title = "Oops! Lỗi đăng ký";
                        $modal_message = "Email hoặc tên đăng nhập này đã tồn tại trong hệ thống. Vui lòng dùng email/ tên đăng nhập khác!";

                }
            }
            $stmt->close();
        }
    }
    $connect->close();
?>

<link rel="stylesheet" href="public/css/signup.css">

<div class="container">

    <!-- LEFT -->
    <div class="left-panel">
        <h1>Welcome to</h1>
        <a href="index.php"><img src="public/images/bloggerZ.png" class="logo" alt="BloggerZ Logo"></a>
    </div>

    <!-- RIGHT -->
    <div class="right-panel">

        <h3>Sign Up</h3>
        <form action="" method="POST">

            <input type="text" name="fullname" placeholder="Full name" required>

            <input type="email" name="email" placeholder="Email" required>

            <input type="text" name="username" placeholder="Username" required>

            <input type="password" name="password" placeholder="Password" required>

            <input type="password" name="confirm_password" placeholder="Repeat Password" required>

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
            <?php if($modal_title != "Đăng ký thành công!"):?>
                <button class="ok-btn" onclick="document.getElementById('resultModal').style.display='none'">OK</button>
            <?php endif; ?>
            <a href="?page=signin" class="direct-link">Chuyển đến trang đăng nhập</a>
        </div>
    </div>
</div>
<?php endif; ?>
<script src="public/js/signup.js"></script>
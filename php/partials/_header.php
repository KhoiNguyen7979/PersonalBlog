<?php
    // nếu người dùng đã đăng nhập, tạo 1 biến email để lấy email của người dùng đã đăng nhập
    // thiết lập kết nối CSDL
    require_once __DIR__ . '/../mySQLconnect.php';
    if(isset($_SESSION['email'])){
        $email = $_SESSION['email'];
    }
?>
<link rel="stylesheet" href="public/css/_header.css">
<header>
    <div class="nav_container">
        <div class="logo_container">
            <!-- Logo trang web ở ngoài cùng bên trái -->
            <a href="index.php"><img id="logo" src="public/images/logo.png" alt="logo"></a>
        </div>
        <!-- thanh điều hướng ở ngay giữa -->
        <div class="nav">
            <div class="nav_a"><a href="index.php">Home</a></div>
            <div class="nav_a"><a href="?page=blog">My Blog</a></div>
            <div class="nav_a"><a href="?page=about">About</a></div>
            <!-- Nếu vai trò của người dùng là admin, sẽ ẩn đi chức năng Review, vì admin không nên tự review trang web của chính mình :v -->
            <?php if (!isset($_SESSION['vaitro']) || $_SESSION['vaitro'] !== 'admin'): ?>
            <div class="nav_a"><a href="#" id="contact-nav-btn">Review</a></div>
            <?php endif; ?>
        </div>
        <!-- Ảnh avatar và tên người dùng ở ngoài cùng bên phải -->
        <div class="account_container" id="account_container_2">
                <!-- Giao diện khi ĐÃ ĐĂNG NHẬP: hiển thị tên người dùng và ảnh avatar đã thiết lập -->
            <?php if (isset($_SESSION['email']) && $_SESSION['hoten']): ?>
                <div style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <!-- Hiển thị tên người dùng -->
                    <span style="font-weight: 500; font-size: 20px;">
                        <?php echo htmlspecialchars($_SESSION['hoten']); ?>
                    </span>
                    <!--  Hiển thị ảnh tài khoản -->
                    <img
                    id="account"
                    src="get_image.php?email=<?= urlencode($email) ?>&v=<?= time() ?>"
                    alt="account"
                    onerror="this.src='public/images/account.jpg'">
                    <!-- nếu chưa đăng nhập sẽ để ảnh account.jpg mặc định, khi đăng nhập sẽ hiển thị ảnh avatar của người dùng đã thiết lập -->
                </div>
                 <!-- Giao diện khi ĐÃ ĐĂNG NHẬP: hiển thị lựa chọn coi hồ sơ hoặc đăng xuất -->
                <div id="logoption">
                    <a href="?page=profile">Profile</a>
                    <a href="?page=signout">Sign Out</a>
                </div>
            <!-- Giao diện khi CHƯA ĐĂNG NHẬP: chỉ hiển thị sign-in và sign-up -->
            <?php else: ?>
                <img id="account" src="public/images/account.jpg" alt="taikhoan">
                <div id="logoption">
                    <a href="?page=signup">Sign Up</a>
                    <a href="?page=signin">Sign In</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>
<script src="public/js/_header.js"></script>
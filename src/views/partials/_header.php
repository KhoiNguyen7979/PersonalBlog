<?php
    // nếu người dùng đã đăng nhập, tạo 1 biến email để lấy email của người dùng đã đăng nhập
    require_once 'src/views/mySQLconnect.php';
    if(isset($_SESSION['email'])){
        $email = $_SESSION['email'];
    }
?>

<link rel="stylesheet" href="public/css/_header.css">
<header>
    <div class="nav_container">
        <div class="logo_container">
            <a href="index.php"><img id="logo" src="public/images/logo.png" alt="logo"></a>
        </div>
        <div class="nav">
            <div class="nav_a"><a href="index.php">Home</a></div>
            <div class="nav_a"><a href="?page=blog">My Blog</a></div>
            <div class="nav_a"><a href="?page=about">About</a></div>
            <div class="nav_a"><a href="#" id="contact-nav-btn">Review</a></div>
        </div>
        
        <div class="account_container" id="account_container_2">
            <?php if (isset($_SESSION['email']) && $_SESSION['hoten']): ?>
                <!-- Giao diện khi ĐÃ ĐĂNG NHẬP -->
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
                    onerror="this.src='public/images/account.jpg'"
                >
                </div>
                <!-- Menu dropdown (Hồ sơ & Đăng xuất) -->
                <div id="logoption">
                    <a href="?page=profile">Hồ sơ</a>
                    <a href="?page=signout">Đăng xuất</a>
                </div>

            <?php else: ?>
                <!-- Giao diện khi CHƯA ĐĂNG NHẬP -->
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
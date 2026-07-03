<link rel="stylesheet" href="public/css/_header.css">
<header>
    <div class="nav_container">
        <div class="logo_container">
            <a href="index.php"><img id="logo" src="public/images/logo.png" alt="logo"></a>
        </div>
        <div class="nav">
            <div class="nav_a"><a href="index.php">Home</a></div>
            <div class="nav_a"><a href="#">My Blog</a></div>
            <div class="nav_a"><a href="?page=about">About</a></div>
            <div class="nav_a"><a href="#" id="contact-nav-btn">Contact</a></div>
        </div>
        
        <div class="account_container" id="account_container_2">
            <?php if (isset($_SESSION['email']) && $_SESSION['hoten']): ?>
                <!-- Giao diện khi ĐÃ ĐĂNG NHẬP -->
                <div style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <!-- Hiển thị tên người dùng -->
                    <span style="font-weight: 500; font-size: 20px;">
                        <?php echo htmlspecialchars($_SESSION['hoten']); ?>
                    </span>
                    <!-- Ảnh tài khoản -->
                    <img id="account" src="public/images/account.jpg" alt="taikhoan">
                </div>
                <!-- Menu dropdown (Hồ sơ & Đăng xuất) -->
                <div id="logoption">
                    <a href="#">Hồ sơ</a>
                    <a href="?page=signout">Đăng xuất</a>
                </div>

            <?php else: ?>
                <!-- Giao diện khi CHƯA ĐĂNG NHẬP (Giữ nguyên như cũ) -->
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
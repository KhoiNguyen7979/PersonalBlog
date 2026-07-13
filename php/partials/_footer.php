<!-- === FOOTER: Brand | Quick Links | Contact | Newsletter + Bottom bar === -->
<link rel="stylesheet" href="public/css/_footer.css">
<footer class="modern-footer">
    <div class="footer_container">
        
        <!-- Cột Logo và Giới thiệu -->
        <div class="footer_col brand_col">
            <a href="index.php" class="footer_brand">
                <img src="public/images/logo.png" alt="BloggerZ Logo" class="footer_logo">
                <span class="brand_name">bloggerZ</span>
            </a>
            <p class="brand_desc">Sharing ideas, stories, and the things that make us curious. Stay inspired and keep exploring the digital world with us.</p>
        </div>

        <!-- Cột Điều hướng -->
        <div class="footer_col nav_col">
            <h3 class="col_title">Quick Links</h3>
            <ul class="footer_nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="?page=blog">My Blog</a></li>
                <li><a href="?page=about">About</a></li>
                <?php if (!isset($_SESSION['vaitro']) || $_SESSION['vaitro'] !== 'admin'): ?>
                <li><a href="#" id="contact-footer-btn">Review</a></li>
                <?php endif;?>
            </ul>
        </div>

        <!-- Cột Liên hệ -->
        <div class="footer_col contact_col">
            <h3 class="col_title">Get in Touch</h3>
            <ul class="footer_contact">
                <li><span>📞</span> <a href="tel:03636363363">123 456 789</a></li>
                <li><span>✉️</span> <a href="mailto:bloggerZ@gmail.com">bloggerZ@gmail.com</a></li>
                <li><span>📍</span> 36, 30/4 Street, Thanh Hoa</li>
            </ul>
        </div>

        <!-- Cột Newsletter -->
        <?php if (!isset($_SESSION['vaitro']) || $_SESSION['vaitro'] !== 'admin'): ?>
        <div class="footer_col newsletter_col">
            <h3 class="col_title">Newsletter</h3>
            <p>Subscribe to get our latest content delivered to your inbox.</p>
            <div class="footer_subscribe">
                <button id="footer-subscribe-btn">Subscribe ↗</button>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <!-- Phần Dưới Cùng (Bottom Bar) -->
    <div class="footer_bottom">
        <div class="footer_bottom_left">
            <p>&copy; <?php echo date("Y"); ?> bloggerZ. All rights reserved.</p>
        </div>
        <div class="footer_bottom_right">
            <a href="#">Privacy Policy</a>
            <span class="sep">|</span>
            <a href="#">Terms of Service</a>
            <span class="sep">|</span>
            <span>Made with by bloggerZ</span>
        </div>
    </div>
</footer>

<!-- Include modals -->
<?php if (file_exists(__DIR__ . '/_modals.php')) { include __DIR__ . '/_modals.php'; } ?>
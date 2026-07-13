<?php
// === index.php - Trang chủ kiêm SPA Router ===
// Tất cả trang đều chạy qua file này, dùng ?page= để chuyển trang
session_start();
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

$titles = [
    'home'          => 'Home - BloggerZ',
    'blog'          => 'My Blog - BloggerZ',
    'about'         => 'About - BloggerZ',
    'signin'        => 'Sign In - BloggerZ',
    'signup'        => 'Sign Up - BloggerZ',
    'signout'       => 'Sign Out - BloggerZ',
    'profile'       => 'Profile - BloggerZ',
    'public_profile'=> 'Profile - BloggerZ',
    'create_blog'   => 'Write a Blog - BloggerZ',
    'read_blog'     => 'Read Blog - BloggerZ',
    'edit_post'     => 'Edit Post - BloggerZ',
];
$title = $titles[$page] ?? 'BloggerZ';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="public/css/_index.css">
</head>
<body>

<!-- Overlay hiệu ứng chuyển trang (fade) -->
<div id="page-transition"></div>

<!-- Trang đăng nhập/đăng ký/đăng xuất: hiển thị đứng-alone (không header/footer) -->
<?php if ($page === 'signin' || $page === 'signup' || $page === 'signout'): ?>

    <?php if ($page === 'signout'): include 'php/Pages/signout.php'; endif; ?>
    <?php if ($page === 'signin'): include 'php/Pages/signin.php'; endif; ?>
    <?php if ($page === 'signup'): include 'php/Pages/signup.php'; endif; ?>

<!-- Các trang còn lại: có header + sidebar + footer -->
<?php else: ?>
    <?php include 'php/partials/_header.php'; ?>
    <!-- Sidebar icons mạng xã hội (Instagram, X, Facebook, YouTube) -->
    <div class="sidenav">
        <a href="#"><svg viewBox="0 0 24 24" width="28" height="28" fill="white"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
        <a href="#"><svg viewBox="0 0 24 24" width="28" height="28" fill="white"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
        <a href="#"><svg viewBox="0 0 24 24" width="28" height="28" fill="white"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
        <a href="#"><svg viewBox="0 0 24 24" width="28" height="28" fill="white"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>
    </div>
    <main>
    <!-- ===== TRANG HOME: Hero + Blog ===== -->
    <?php if ($page === 'home'): ?>
        <div class="main_content">
            <!-- Hero Section: tiêu đề lớn + nút Read/Subscribe -->
            <div class="hero_content">
                <div id="mylife">
                    <h1>My Life<br>My Blog</h1>
                </div>
                <div class="hero_actions">
                    <hr class="hero_line">
                    <div class="hero_buttons">
                        <button id="read">Read the Blog</button>
                        <?php if (!isset($_SESSION['vaitro']) || $_SESSION['vaitro'] !== 'admin'): ?>
                        <button id="sub">Subscribe</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Anchor để cuộn xuống phần blog -->
        <div id="blog-anchor"></div>
        <?php include 'php/Pages/myBlog.php'; ?>

    <?php elseif ($page === 'blog'): ?>
        <?php include 'php/Pages/myBlog.php'; ?>

    <?php elseif ($page === 'about'): ?>
        <?php include 'php/Pages/about.php'; ?>

    <?php elseif ($page === 'profile'): ?>
        <?php include 'php/Pages/profile.php'; ?>

    <?php elseif ($page === 'public_profile'): ?>
        <?php include 'php/Pages/public_profile.php'; ?>

    <?php elseif ($page === 'create_blog'): ?>
        <?php include 'php/Pages/create_blog.php'; ?>

    <?php elseif ($page === 'read_blog'): ?>
        <?php include 'php/Pages/read_blog.php'; ?>

    <?php elseif ($page === 'edit_post'): ?>
        <?php include 'php/Pages/edit_post.php'; ?>

    <?php endif; ?>
    </main>
    <!-- Footer (bao gồm cả modal Review & Subscribe) -->
        <?php include 'php/partials/_footer.php'; ?>
<?php endif; ?>
<div id="global-toast" class="toast-notification"></div>
<script src="public/js/index.js"></script>
</body>
</html>

<?php
session_start();

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Tiêu đề trang theo từng page
$titles = [
    'home'   => 'BloggerZ - Trang Chủ',
    'blog'   => 'BloggerZ - My Blog',
    'about'  => 'About - BloggerZ',
    'signin' => 'Sign In - BloggerZ',
    'signup' => 'Sign Up - BloggerZ',
    'signout' => 'Sign Out - BloggerZ',
    'profile' => 'Hồ Sơ - BloggerZ',
    'create_blog' => 'Write a Blog - BloggerZ',
];
$title = $titles[$page] ?? 'BloggerZ';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="public/css/_index.css">
</head>
<body>

<?php if ($page === 'signin' || $page === 'signup' || $page === 'signout'): ?>

    <!-- Sign In / Sign Up không có header & footer -->
     <?php if ($page === 'signout'): include 'src/views/signout.php'; endif; ?>
    <?php if ($page === 'signin'): include 'src/views/signin.php'; endif; ?>
    <?php if ($page === 'signup'): include 'src/views/signup.php'; endif; ?>

<?php else: ?>
    <?php include 'src/views/partials/_header.php'; ?>

    <main>
    <?php if ($page === 'home'): ?>
        <div class="main_content">
            <div class="hero_content">
                <div id="mylife">
                    <h1>My Life<br>My Blog</h1>
                </div>
                <hr class="hero_line">
                <div class="hero_buttons">
                    <button id="read">Read the Blog ⚫</button>
                    <button id="sub">Subscribe ⚫</button>
                </div>
            </div>
        </div>
        <div class="sidenav">
            <a href="#"><img src="public/images/Instagram.png" alt="Instagram"></a>
            <a href="#"><img src="public/images/x.png" alt="X"></a>
            <a href="#"><img src="public/images/facebook.png" alt="Facebook"></a>
            <a href="#"><img src="public/images/youtube.png" alt="YouTube"></a>
        </div>
        <!-- Anchor để scroll xuống blog -->
        <div id="blog-anchor"></div>
        <?php include 'src/views/myBlog.php'; ?>

    <?php elseif ($page === 'blog'): ?>
        <?php include 'src/views/myBlog.php'; ?>

    <?php elseif ($page === 'about'): ?>
        <?php include 'src/views/about.php'; ?>

    <?php elseif ($page === 'profile'): ?>
        <?php include 'src/views/profile.php'; ?>

    <?php elseif ($page === 'create_blog'): ?>
        <?php include 'src/views/create_blog.php'; ?>

    <?php endif; ?>
    </main>

    <?php include 'src/views/partials/_footer.php'; ?>

<?php endif; ?>

</body>
</html>
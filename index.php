<?php
//Bắt đầu session của người dùng
session_start();
//Tạo 1 biến page để tạo giống như 1 bộ định tuyến (router) cơ bản
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Tiêu đề trang theo từng page (Đã thêm read_blog và edit_post)
$titles = [
    'home'        => 'Home - BloggerZ',
    'blog'        => 'My Blog - BloggerZ',
    'about'       => 'About - BloggerZ',
    'signin'      => 'Sign In - BloggerZ',
    'signup'      => 'Sign Up - BloggerZ',
    'signout'     => 'Sign Out - BloggerZ',
    'profile'     => 'Profile - BloggerZ',
    'create_blog' => 'Write a Blog - BloggerZ',
    'read_blog'   => 'Read Blog - BloggerZ', // Thêm title cho trang đọc bài
    'edit_post'   => 'Edit Post - BloggerZ', // Thêm title cho trang sửa bài
];
//Tạo biến title để xuất tên web ra trên tab (dựa vào biến page trước đó)
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

    <?php elseif ($page === 'read_blog'): ?>
        <?php include 'src/views/read_blog.php'; ?>

    <?php elseif ($page === 'edit_post'): ?>
        <?php include 'src/views/edit_post.php'; ?>

    <?php endif; ?>
    </main>
        <?php include 'src/views/partials/_footer.php'; ?>
<?php endif; ?>
</body>
</html>
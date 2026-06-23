<?php

if (file_exists('src/views/partials/_header.php')) {
    include 'src/views/partials/_header.php';
}


echo '<main style="min-height: 400px; padding: 20px;">';
    
   
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';

    if ($page == 'home') {
        echo "<h2>Chào mừng bạn đến với Blog Cá Nhân!</h2>";
        echo "<p>Đây là nội dung của trang chủ.</p>";
    } elseif ($page == 'blog') {
        echo "<h2>Danh sách bài viết</h2>";
    }

echo '</main>';

if (file_exists('src/views/partials/_footer.php')) {
    include 'src/views/partials/_footer.php';
}
?>
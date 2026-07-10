<?php
/**
 * src/views/read_blog.php
 * Giao diện đọc chi tiết bài viết - hiển thị nội dung, ảnh bìa và chức năng LIKE
 */
require_once 'mySQLconnect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$email = $_SESSION['email'] ?? null;

// Lấy thông tin bài viết, số lượt thích, trạng thái đã thích và kiểm tra xem có ảnh bìa không
$stmt = $connect->prepare("
    SELECT b.*, n.HoTenNguoiDung, 
           (SELECT COUNT(*) FROM ThichBaiViet WHERE ID_BaiViet = b.ID_BaiViet) as TotalLikes,
           (SELECT COUNT(*) FROM ThichBaiViet WHERE ID_BaiViet = b.ID_BaiViet AND Email = ?) as IsLiked,
           (SELECT COUNT(*) FROM Pics p WHERE p.ID_BaiViet = b.ID_BaiViet AND p.IsThumb = 1) AS HasThumb
    FROM BaiViet b
    JOIN NguoiDung n ON b.ID_NguoiDung = n.Email
    WHERE b.ID_BaiViet = ?
");
$stmt->bind_param("si", $email, $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
    echo "<h1 style='text-align:center; margin-top:50px;'>Bài viết không tồn tại.</h1>";
    exit;
}

$isLikedClass = $post['IsLiked'] > 0 ? 'liked' : '';

// Xác định đường dẫn ảnh bìa (Nếu có ảnh thì gọi get_image.php, ngược lại dùng ảnh mặc định)
$imgSrc = $post['HasThumb'] ? "get_image.php?id=$id" : "public/images/account.jpg";
?>
<link rel="stylesheet" href="public/css/read_blog.css">

<div class="read-blog-container">
    <h1 class="read-title"><?= htmlspecialchars($post['TieuDe']) ?></h1>
    
    <div class="read-meta">
        Bởi <strong><?= htmlspecialchars($post['HoTenNguoiDung']) ?></strong> | 
        Đăng ngày: <?= date('d/m/Y', strtotime($post['NgayDang'])) ?> |
        ⏱️ <?= $post['ThoiGianDoc'] ?> phút đọc
    </div>
    
    <div class="read-thumbnail-wrap">
        <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($post['TieuDe']) ?>" class="read-thumbnail">
    </div>
    
    <p class="read-summary"><i><?= htmlspecialchars($post['TomTat']) ?></i></p>

    <div class="read-content">
        <?= nl2br(htmlspecialchars($post['NoiDung'])) ?>
    </div>

    <hr class="read-divider">
    
    <div class="like-section">
        <button id="like-btn" class="like-btn <?= $isLikedClass ?>" data-id="<?= $id ?>">
            ❤️ <span id="like-count"><?= $post['TotalLikes'] ?></span> Thích
        </button>
    </div>
</div>

<script>
document.getElementById('like-btn').addEventListener('click', function() {
    const btn = this;
    const postId = btn.dataset.id;
    
    fetch(`api/like_post.php?id=${postId}`, { method: 'POST' })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('like-count').innerText = data.likes;
            if (data.status === 'liked') {
                btn.classList.add('liked');
            } else {
                btn.classList.remove('liked');
            }
        } else {
            alert(data.message);
        }
    });
});
</script>

<style>
/* CSS bổ sung để căn chỉnh hình ảnh và bố cục trang đọc bài viết */
.read-blog-container {
    max-width: 800px;
    margin: 40px auto;
    padding: 0 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
    line-height: 1.8;
}

.read-title {
    font-size: 32px;
    margin-bottom: 10px;
    font-weight: 700;
}

.read-meta {
    font-size: 14px;
    color: #666;
    margin-bottom: 30px;
}

/* Khung bọc ảnh bìa */
.read-thumbnail-wrap {
    width: 100%;
    max-height: 450px;
    overflow: hidden;
    border-radius: 8px;
    margin-bottom: 25px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Định dạng ảnh bìa căng đều, không méo hình */
.read-thumbnail {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.read-summary {
    font-size: 18px;
    color: #555;
    border-left: 4px solid #4CAF50;
    padding-left: 15px;
    margin-bottom: 30px;
}

.read-content {
    font-size: 16px;
    white-space: pre-line;
    margin-bottom: 40px;
}

.read-divider {
    border: 0;
    border-top: 1px solid #eee;
    margin-bottom: 20px;
}

/* Nút Like */
.like-btn { 
    padding: 10px 24px; 
    border: 1px solid #ddd; 
    background: #fff; 
    cursor: pointer; 
    border-radius: 20px; 
    font-size: 16px; 
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.like-btn:hover {
    background: #f9f9f9;
    transform: scale(1.03);
}

.like-btn.liked { 
    background: #ffebee; 
    border-color: #ffcdd2; 
    color: #d32f2f; 
}
</style>
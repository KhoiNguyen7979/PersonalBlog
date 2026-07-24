<?php
// === TRANG ĐỌC BÀI VIẾT ===
// Các block: Tiêu đề + Tác giả | Ảnh bìa| Tóm tắt | Nội dung | Nút Like | Menu Edit/Delete
//thiết lập kết nối CSDL
require_once __DIR__ . '/../mySQLconnect.php';
// lấy id của bài viết + email của người dùng đăng nhập
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$email = $_SESSION['email'] ?? null;

// Lấy thông tin bài viết, số lượt thích, trạng thái đã thích
$stmt = $connect->prepare("
    SELECT b.*, n.HoTenNguoiDung, 
           (SELECT COUNT(*) FROM ThichBaiViet WHERE ID_BaiViet = b.ID_BaiViet) as TotalLikes,
           (SELECT COUNT(*) FROM ThichBaiViet WHERE ID_BaiViet = b.ID_BaiViet AND Email = ?) as IsLiked
    FROM BaiViet b
    JOIN NguoiDung n ON b.ID_NguoiDung = n.Email
    WHERE b.ID_BaiViet = ?
");
$stmt->bind_param("si", $email, $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
//Nếu ko có dữ liệu của bài viết, báo lỗi
if (!$post) {
    echo "<h1 style='text-align:center; margin-top:50px;'>Post not found.</h1>";
    exit;
}

$isLikedClass = $post['IsLiked'] > 0 ? 'liked' : '';

// Lấy ảnh của bài viết
$imgStmt = $connect->prepare("SELECT ID_Anh FROM Pics WHERE ID_BaiViet = ? ORDER BY IsThumb DESC, ID_Anh ASC");
$imgStmt->bind_param("i", $id);
$imgStmt->execute();
$imgRows = $imgStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$imgStmt->close();

$images = [];
foreach ($imgRows as $i => $row) {
    $images[] = "get_image.php?id=$id&idx=$i&v=" . time();
}
$imgCount = count($images);
$hasMultiple = $imgCount > 1;

$connect->close();
?>
<link rel="stylesheet" href="public/css/blog.css">

<div class="read-blog-container">
    <h1 class="read-title"><?= htmlspecialchars($post['TieuDe']) ?></h1>
    
    <div class="read-meta">
        By <a href="?page=public_profile&email=<?= urlencode($post['ID_NguoiDung']) ?>" class="author-link"><strong><?= htmlspecialchars($post['HoTenNguoiDung']) ?></strong></a> | 
        Published: <?= date('d/m/Y', strtotime($post['NgayDang'])) ?>
    </div>
    
    <div class="read-thumbnail-area">
        <?php if ($hasMultiple): ?>
        <div class="carousel-wrap">
            <div class="carousel-track" id="carousel-track">
                <?php foreach ($images as $i => $src): ?>
                    <div class="carousel-slide <?= $i === 0 ? 'active' : '' ?>">
                        <img src="<?= $src ?>" alt="<?= htmlspecialchars($post['TieuDe']) ?> - Image <?= $i+1 ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-btn carousel-prev" id="carousel-prev">&#10094;</button>
            <button class="carousel-btn carousel-next" id="carousel-next">&#10095;</button>
            <div class="carousel-dots" id="carousel-dots">
                <?php foreach ($images as $i => $src): ?>
                    <span class="carousel-dot <?= $i === 0 ? 'active' : '' ?>" data-idx="<?= $i ?>"></span>
                <?php endforeach; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="read-thumbnail-wrap">
            <img src="<?= !empty($images) ? $images[0] : 'public/images/account.jpg' ?>" alt="<?= htmlspecialchars($post['TieuDe']) ?>" class="read-thumbnail">
        </div>
        <?php endif; ?>
            <!-- Nếu là email của người chính chủ đăng bài viết hoặc admin thì có thể chỉnh sửa được bài viết -->
        <?php if (isset($email) && ($email === $post['ID_NguoiDung'] || (isset($_SESSION['vaitro']) && $_SESSION['vaitro'] === 'admin'))): ?>
            <div class="post-menu">
                <button class="post-menu-btn" title="Options">⋮</button>
                <div class="post-menu-dropdown">
                    <a href="?page=edit_post&id=<?= $id ?>">Edit</a>
                    <a href="#" class="delete-post-btn" data-id="<?= $id ?>">Delete</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <p class="read-summary"><i><?= htmlspecialchars($post['TomTat']) ?></i></p>

    <div class="read-content">
        <?= nl2br(htmlspecialchars($post['NoiDung'])) ?>
    </div>

    <hr class="read-divider">
    
    <div class="like-section">
        <button id="like-btn" class="like-btn <?= $isLikedClass ?>" data-id="<?= $id ?>">
            ❤️ <span id="like-count"><?= $post['TotalLikes'] ?></span> Like
        </button>
    </div>
</div>
<script src="public/js/read_blog.js"></script>
<style>
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

.author-link {
    color: #4CAF50;
    text-decoration: none;
    transition: color 0.2s;
}

.author-link:hover {
    color: #388E3C;
    text-decoration: underline;
}

/* === CSS của trang đọc bài viết ===
/* Khung bọc ảnh bìa */
.read-thumbnail-area {
    position: relative;
    margin-bottom: 25px;
}

.read-thumbnail-wrap {
    width: 100%;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    background: #f0f0f0;
    overflow: hidden;
}

.read-thumbnail-area .post-menu {
    position: absolute;
    right: 8px;
    top: 8px;
    z-index: 10;
}

.read-thumbnail-area .post-menu .post-menu-dropdown {
    top: calc(100% + 6px);
    right: 0;
}

.read-thumbnail {
    width: 100%;
    height: auto;
    display: block;
}

.read-thumbnail-area .post-menu .post-menu-btn {
    background: rgba(255,255,255,0.92);
    border: 1px solid rgba(0,0,0,0.1);
    padding: 6px 10px;
    border-radius: 8px;
    font-size: 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.read-thumbnail-area .post-menu .post-menu-btn:hover {
    background: rgba(255,255,255,1);
}

/* ── Carousel ─────────────────────────────────────────────────────────────── */
.carousel-wrap {
    position: relative;
    width: 100%;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.carousel-track {
    position: relative;
    width: 100%;
    overflow: hidden;
    border-radius: 8px;
}

.carousel-track .carousel-slide {
    width: 100%;
    position: absolute;
    top: 0;
    left: 0;
    opacity: 0;
    transition: opacity 0.5s ease;
    pointer-events: none;
}

.carousel-track .carousel-slide.active {
    opacity: 1;
    pointer-events: auto;
}

.carousel-track .carousel-slide img {
    width: 100%;
    height: auto;
    display: block;
}

.read-thumbnail-area .carousel-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255,255,255,0.85);
    border: none;
    font-size: 20px;
    color: #333;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    transition: background 0.2s;
    z-index: 10;
    opacity: 1 !important;
    pointer-events: auto !important;
}

.read-thumbnail-area .carousel-btn:hover {
    background: rgba(255,255,255,1);
}

.carousel-prev { left: 12px; }
.carousel-next { right: 12px; }

.read-thumbnail-area .carousel-dots {
    position: absolute;
    bottom: 12px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    z-index: 10;
}

.read-thumbnail-area .carousel-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: rgba(255,255,255,0.5);
    cursor: pointer;
    transition: background 0.2s;
}

.read-thumbnail-area .carousel-dot.active {
    background: rgba(255,255,255,1);
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
<?php
// === TRANG HỒ SƠ CÔNG KHAI ===
//thiết lập kết nối CSDL
require_once __DIR__ . '/../mySQLconnect.php';

$email = isset($_GET['email']) ? trim($_GET['email']) : '';
//Nếu tài khoản ko có email thì báo lỗi
if (empty($email)) {
    echo '<p style="color:red; padding:40px; text-align:center;">Missing user information.</p>';
    $connect->close();
    exit;
}

// Lấy thông tin người dùng
$stmt = $connect->prepare("SELECT HoTenNguoiDung, TenDangNhap, MoTa FROM NguoiDung WHERE Email = ?");
if (!$stmt) {
    echo '<p style="color:red; padding:40px;">Database error: ' . htmlspecialchars($connect->error) . '</p>';
    $connect->close();
    exit;
}
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
//Nếu ko có dữ liệu người dùng thì báo lỗi
if (!$user) {
    echo '<p style="color:red; padding:40px; text-align:center;">User not found.</p>';
    $connect->close();
    exit;
}

// Thống kê tổng bài viết
$stmtStats = $connect->prepare("SELECT COUNT(*) as total_posts FROM BaiViet WHERE ID_NguoiDung = ?");
$stmtStats->bind_param("s", $email);
$stmtStats->execute();
$stats = $stmtStats->get_result()->fetch_assoc();

// Thống kê tổng lượt likes
$stmtLikes = $connect->prepare(
    "SELECT COUNT(*) AS total_likes
     FROM ThichBaiViet t
     JOIN BaiViet b ON t.ID_BaiViet = b.ID_BaiViet
     WHERE b.ID_NguoiDung = ?"
);
$stmtLikes->bind_param("s", $email);
$stmtLikes->execute();
$likesStat = $stmtLikes->get_result()->fetch_assoc();
$totalLikes = intval($likesStat['total_likes'] ?? 0);

// Lấy các bài viết đã đăng của user này
$stmtPosts = $connect->prepare("
    SELECT b.ID_BaiViet, b.TieuDe, b.TomTat, b.NgayDang,
           (SELECT COUNT(*) FROM ThichBaiViet t WHERE t.ID_BaiViet = b.ID_BaiViet) AS LuotThich,
           (SELECT COUNT(*) FROM Pics p WHERE p.ID_BaiViet = b.ID_BaiViet) AS PicCount
    FROM BaiViet b
    WHERE b.ID_NguoiDung = ?
    ORDER BY b.NgayDang DESC
");
$stmtPosts->bind_param("s", $email);
$stmtPosts->execute();
$posts = $stmtPosts->get_result()->fetch_all(MYSQLI_ASSOC);

// Lấy ảnh cho bài viết
$postIds = array_column($posts, 'ID_BaiViet');
$postImages = [];
if (!empty($postIds)) {
    $placeholders = implode(',', array_fill(0, count($postIds), '?'));
    $imgSql = "SELECT ID_BaiViet, ID_Anh FROM Pics WHERE ID_BaiViet IN ($placeholders) ORDER BY ID_Anh ASC";
    $imgStmt = $connect->prepare($imgSql);
    $imgTypes = str_repeat('i', count($postIds));
    $imgStmt->bind_param($imgTypes, ...$postIds);
    $imgStmt->execute();
    $allImgs = $imgStmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $imgStmt->close();
    foreach ($allImgs as $img) {
        $postImages[$img['ID_BaiViet']][] = $img['ID_Anh'];
    }
}

$connect->close();
//chức năng format lại ngày tháng đăng bài viết
function formatPostDate($dateStr) {
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
               'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $d = new DateTime($dateStr);
    return $months[(int)$d->format('n') - 1] . ' ' . $d->format('j') . ', ' . $d->format('Y');
}
?>
<link rel="stylesheet" href="public/css/public_profile.css">
<link rel="stylesheet" href="public/css/blog.css">

<div class="pub-profile-page">

    <!-- Header Hồ Sơ-->
    <div class="pub-profile-card">
        <div class="pub-avatar-wrap">
            <img
                src="get_image.php?email=<?= urlencode($email) ?>&v=<?= time() ?>"
                alt="Avatar"
                class="pub-avatar"
                onerror="this.src='public/images/account.jpg'"
            >
        </div>
        <div class="pub-info">
            <h1 class="pub-name"><?= htmlspecialchars($user['HoTenNguoiDung']) ?></h1>
            <p class="pub-username">@<?= htmlspecialchars($user['TenDangNhap']) ?></p>
            <div class="pub-stats-row">
                <div class="pub-stat">
                    <span class="pub-stat-num"><?= $stats['total_posts'] ?></span>
                    <span class="pub-stat-txt">Posts</span>
                </div>
                <div class="pub-stat">
                    <span class="pub-stat-num"><?= $totalLikes ?></span>
                    <span class="pub-stat-txt">Likes</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Phần mô tả -->
    <?php if (!empty($user['MoTa'])): ?>
    <div class="pub-section">
        <h3 class="pub-section-title">About</h3>
        <p class="pub-bio"><?= nl2br(htmlspecialchars($user['MoTa'])) ?></p>
    </div>
    <?php endif; ?>

    <!-- Bài đăng của người dùng -->
    <div class="pub-section">
        <h3 class="pub-section-title">Posts by <?= htmlspecialchars($user['HoTenNguoiDung']) ?></h3>

        <?php if (empty($posts)): ?>
            <div class="no-posts"><p>No posts yet.</p></div>
        <?php else: ?>
            <div class="pub-posts-list">
                <?php foreach ($posts as $post): ?>
                    <?php
                    $pid = $post['ID_BaiViet'];
                    $imgs = $postImages[$pid] ?? [];
                    $hasImg = !empty($imgs);
                    ?>
                    <a href="?page=read_blog&id=<?= $pid ?>" class="pub-post-card">
                        <div class="pub-post-img-wrap">
                            <?php if ($hasImg): ?>
                                <img src="get_image.php?id=<?= $pid ?>&idx=0&v=<?= time() ?>"
                                     alt="<?= htmlspecialchars($post['TieuDe']) ?>"
                                     class="pub-post-img" loading="lazy">
                            <?php else: ?>
                                <img src="public/images/account.jpg"
                                     alt="<?= htmlspecialchars($post['TieuDe']) ?>"
                                     class="pub-post-img" loading="lazy">
                            <?php endif; ?>
                        </div>
                        <div class="pub-post-content">
                            <h4 class="pub-post-title"><?= htmlspecialchars($post['TieuDe']) ?></h4>
                            <p class="pub-post-summary"><?= htmlspecialchars($post['TomTat']) ?></p>
                            <div class="pub-post-meta">
                                <span class="pub-post-date"><?= formatPostDate($post['NgayDang']) ?></span>
                                <span class="pub-post-likes">❤️ <?= $post['LuotThich'] ?></span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>

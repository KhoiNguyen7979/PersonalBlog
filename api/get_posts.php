<?php
/**
 * api/get_posts.php
 * Trả về HTML danh sách bài viết (AJAX).
 *
 * Params:
 *   type     = my | other
 *   category = all | technology | skill | story | music
 *   sort     = newest | oldest | likes
 *   page     = 1, 2, 3...
 */
session_start();
require_once __DIR__ . '/../php/mySQLconnect.php';

header('Content-Type: application/json; charset=utf-8');

$type     = $_GET['type']     ?? 'other';
$category = $_GET['category'] ?? 'all';
$sort     = $_GET['sort']     ?? 'newest';
$page     = max(1, intval($_GET['page'] ?? 1));
$perPage  = 6;
$offset   = ($page - 1) * $perPage;

$loggedIn = isset($_SESSION['email']) && !empty($_SESSION['email']);
$myEmail  = $loggedIn ? $_SESSION['email'] : '';
$isAdmin  = isset($_SESSION['vaitro']) && $_SESSION['vaitro'] === 'admin';

// ── Điều kiện WHERE ──────────────────────────────────────────────────────────
$conditions = [];
$params     = [];
$types      = '';

if ($type === 'my') {
    if (!$loggedIn) {
        echo json_encode(['html' => '', 'total' => 0, 'totalPages' => 0]);
        exit;
    }
    $conditions[] = "b.ID_NguoiDung = ?";
    $params[]     = $myEmail;
    $types       .= 's';
} else {
    if ($loggedIn) {
        $conditions[] = "b.ID_NguoiDung != ?";
        $params[]     = $myEmail;
        $types       .= 's';
    }
}

if ($category !== 'all') {
    $conditions[] = "b.ID_The_Loai = ?";
    $params[]     = $category;
    $types       .= 's';
}

$where = count($conditions) > 0 ? 'WHERE ' . implode(' AND ', $conditions) : '';

// ── ORDER BY ─────────────────────────────────────────────────────────────────
if ($sort === 'oldest') {
    $orderBy = 'b.NgayDang ASC';
} elseif ($sort === 'likes') {
    $orderBy = '(SELECT COUNT(*) FROM ThichBaiViet t WHERE t.ID_BaiViet = b.ID_BaiViet) DESC';
} else {
    $orderBy = 'b.NgayDang DESC';
}

// ── Đếm tổng ─────────────────────────────────────────────────────────────────
$countSql = "SELECT COUNT(*) as total FROM BaiViet b $where";
$stmtCount = $connect->prepare($countSql);
if (!empty($params)) {
    $stmtCount->bind_param($types, ...$params);
}
$stmtCount->execute();
$countResult = $stmtCount->get_result()->fetch_assoc();
$total = $countResult['total'];
$totalPages = ceil($total / $perPage);

// ── Lấy dữ liệu ──────────────────────────────────────────────────────────────
$sql = "
    SELECT
        b.ID_BaiViet, b.TieuDe, b.TomTat, b.NgayDang,
        b.ThoiGianDoc, b.ID_NguoiDung, b.ID_The_Loai,
        n.HoTenNguoiDung,
        (SELECT COUNT(*) FROM Pics p WHERE p.ID_BaiViet = b.ID_BaiViet) AS PicCount,
        (SELECT COUNT(*) FROM ThichBaiViet t WHERE t.ID_BaiViet = b.ID_BaiViet) AS LuotThich
    FROM BaiViet b
    JOIN NguoiDung n ON b.ID_NguoiDung = n.Email
    $where
    ORDER BY $orderBy
    LIMIT ? OFFSET ?
";

// Thêm LIMIT và OFFSET vào params
$params[]  = $perPage;
$types    .= 'i';
$params[]  = $offset;
$types    .= 'i';

$stmt = $connect->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Lấy danh sách ảnh cho tất cả posts
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

// ── Render HTML ───────────────────────────────────────────────────────────────
function formatDate($dateStr) {
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
               'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $d = new DateTime($dateStr);
    return $months[(int)$d->format('n') - 1] . ' ' . $d->format('j') . ', ' . $d->format('Y');
}

$isMyType  = ($type === 'my');
$canEdit   = ($isMyType && $loggedIn) || $isAdmin;

ob_start();
if (empty($posts)) {
    echo '<div class="no-posts"><p>No posts yet.</p></div>';
} else {
    // Chia thành 3 cột
    $cols = [[], [], []];
    foreach ($posts as $i => $post) {
        $cols[$i % 3][] = $post;
    }

    echo '<div class="posts-grid">';
    foreach ($cols as $col) {
        echo '<div class="posts-col">';
        foreach ($col as $post) {
            $pid = $post['ID_BaiViet'];
            $imgs = $postImages[$pid] ?? [];
            renderPostCard($post, $canEdit, $imgs);
        }
        echo '</div>';
    }
    echo '</div>';
}
$html = ob_get_clean();

echo json_encode([
    'html'       => $html,
    'total'      => $total,
    'totalPages' => $totalPages,
    'page'       => $page,
]);

// ── Helper: Render 1 card ─────────────────────────────────────────────────────
function renderPostCard($post, $canEdit, $images = []) {
    $id      = $post['ID_BaiViet'];
    $title   = htmlspecialchars($post['TieuDe']);
    $summary = htmlspecialchars($post['TomTat']);
    $date    = formatDate($post['NgayDang']);
    $likes   = $post['LuotThich'];
    $hasImages = !empty($images);
    ?>
    <div class="post-card" data-id="<?= $id ?>">
        <div class="post-img-wrap" data-total="<?= count($images) ?>">
            <a href="?page=read_blog&id=<?= $id ?>" class="post-carousel-link" style="text-decoration: none; color: inherit;">
                <?php if ($hasImages): ?>
                    <?php foreach ($images as $idx => $imgId): ?>
                        <img src="get_image.php?id=<?= $id ?>&idx=<?= $idx ?>&v=<?= time() ?>"
                             alt="<?= $title ?>"
                             class="post-carousel-img <?= $idx === 0 ? 'active' : '' ?>"
                             loading="lazy">
                    <?php endforeach; ?>
                <?php else: ?>
                    <img src="public/images/account.jpg" alt="<?= $title ?>" class="post-carousel-img active" loading="lazy">
                <?php endif; ?>
            </a>
            <?php if (count($images) > 1): ?>
                <button type="button" class="carousel-btn carousel-prev" aria-label="Previous">&#10094;</button>
                <button type="button" class="carousel-btn carousel-next" aria-label="Next">&#10095;</button>
                <div class="carousel-dots">
                    <?php foreach ($images as $idx => $imgId): ?>
                        <span class="carousel-dot <?= $idx === 0 ? 'active' : '' ?>" data-idx="<?= $idx ?>"></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if ($canEdit): ?>
                <div class="post-menu">
                    <button class="post-menu-btn" title="Options">⋮</button>
                    <div class="post-menu-dropdown">
                        <a href="?page=edit_post&id=<?= $id ?>">Edit</a>
                        <a href="#" class="delete-post-btn" data-id="<?= $id ?>">Delete</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="post-card-body">
            <div class="post-meta">
                <span class="post-date"><?= $date ?></span>
            </div>
            <a href="?page=read_blog&id=<?= $id ?>" style="text-decoration: none; color: inherit;">
                <h3 class="post-title"><?= $title ?></h3>
                <p class="post-summary"><?= $summary ?></p>
            </a>
            <hr class="post-divider">
            <span class="post-likes">❤️ <?= $likes ?> Likes</span>
        </div>
    </div>
    <?php
}

$connect->close();
?>

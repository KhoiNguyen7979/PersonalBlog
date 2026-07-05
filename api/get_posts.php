<?php
/**
 * api/get_posts.php
 * Trả về HTML danh sách bài viết (AJAX).
 *
 * Params:
 *   type     = my | other
 *   category = all | technology | skill | story | music
 *   sort     = newest | views
 *   page     = 1, 2, 3...
 */
session_start();
require_once '../src/views/mySQLconnect.php';

header('Content-Type: application/json; charset=utf-8');

$type     = $_GET['type']     ?? 'other';
$category = $_GET['category'] ?? 'all';
$sort     = $_GET['sort']     ?? 'newest';
$page     = max(1, intval($_GET['page'] ?? 1));
$perPage  = 6;
$offset   = ($page - 1) * $perPage;

$loggedIn = isset($_SESSION['email']) && !empty($_SESSION['email']);
$myEmail  = $loggedIn ? $_SESSION['email'] : '';

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
$orderBy = $sort === 'views' ? 'b.LuotXem DESC' : 'b.NgayDang DESC';

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
        b.ThoiGianDoc, b.LuotXem, b.ID_NguoiDung, b.ID_The_Loai,
        n.HoTenNguoiDung,
        (SELECT COUNT(*) FROM Pics p WHERE p.ID_BaiViet = b.ID_BaiViet AND p.IsThumb = 1) AS HasThumb
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

// ── Render HTML ───────────────────────────────────────────────────────────────
function formatDate($dateStr) {
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
               'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $d = new DateTime($dateStr);
    return $months[(int)$d->format('n') - 1] . ' ' . $d->format('j') . ', ' . $d->format('Y');
}

$isMyType  = ($type === 'my');
$canEdit   = $isMyType && $loggedIn;

ob_start();
if (empty($posts)) {
    echo '<div class="no-posts"><p>Chưa có bài viết nào.</p></div>';
} else {
    // Chia thành 2 cột
    $col1 = [];
    $col2 = [];
    foreach ($posts as $i => $post) {
        if ($i % 2 === 0) $col1[] = $post;
        else              $col2[] = $post;
    }

    echo '<div class="posts-grid">';
    echo '<div class="posts-col">';
    foreach ($col1 as $post) {
        renderPostCard($post, $canEdit);
    }
    echo '</div>';
    echo '<div class="posts-col">';
    foreach ($col2 as $post) {
        renderPostCard($post, $canEdit);
    }
    echo '</div>';
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
function renderPostCard($post, $canEdit) {
    $id      = $post['ID_BaiViet'];
    $title   = htmlspecialchars($post['TieuDe']);
    $summary = htmlspecialchars($post['TomTat']);
    $date    = formatDate($post['NgayDang']);
    $time    = $post['ThoiGianDoc'];
    $views   = $post['LuotXem'];
    $imgSrc  = $post['HasThumb'] ? "get_image.php?id=$id" : "public/images/account.jpg";
    ?>
    <div class="post-card" data-id="<?= $id ?>">
        <div class="post-img-wrap">
            <img src="<?= $imgSrc ?>" alt="<?= $title ?>" loading="lazy">
        </div>
        <div class="post-meta">
            <span class="post-date"><?= $date ?> · <?= $time ?> min read</span>
            <?php if ($canEdit): ?>
                <div class="post-menu">
                    <button class="post-menu-btn" title="Tuỳ chọn">⋮</button>
                    <div class="post-menu-dropdown">
                        <a href="?page=edit_post&id=<?= $id ?>">✏️ Chỉnh sửa</a>
                        <a href="#" class="delete-post-btn" data-id="<?= $id ?>">🗑️ Xoá</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <h3 class="post-title"><?= $title ?></h3>
        <p class="post-summary"><?= $summary ?></p>
        <hr class="post-divider">
        <span class="post-views"><?= $views ?> views</span>
    </div>
    <?php
}

$connect->close();
?>

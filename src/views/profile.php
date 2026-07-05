<?php
/**
 * src/views/profile.php
 * Trang hồ sơ cá nhân
 */
if (!isset($_SESSION['email'])) {
    header("Location: index.php?page=signin");
    exit;
}

require_once 'src/views/mySQLconnect.php';

$email = $_SESSION['email'];

// Lấy thông tin người dùng
$stmt = $connect->prepare("SELECT HoTenNguoiDung, Email, MoTa, Avatar FROM NguoiDung WHERE Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Thống kê
$stmtStats = $connect->prepare("
    SELECT COUNT(*) as total_posts, COALESCE(SUM(LuotXem), 0) as total_views
    FROM BaiViet WHERE ID_NguoiDung = ?
");
$stmtStats->bind_param("s", $email);
$stmtStats->execute();
$stats = $stmtStats->get_result()->fetch_assoc();
?>
<link rel="stylesheet" href="public/css/profile.css">

<div class="profile-page">

    <div class="profile-card">
        <!-- Avatar bên trái -->
        <div class="profile-left">
            <div class="avatar-wrapper">
                <img
                    id="profile-avatar"
                    src="get_image.php?email=<?= urlencode($email) ?>"
                    alt="Avatar"
                    class="profile-avatar"
                    onerror="this.src='public/images/account.jpg'"
                >
            </div>
            <h2 class="profile-name"><?= htmlspecialchars($user['HoTenNguoiDung']) ?></h2>
            <p class="profile-email"><?= htmlspecialchars($user['Email']) ?></p>

            <!-- Upload avatar -->
            <label for="avatar-upload" class="choose-avatar-btn">Choose Avatar</label>
            <input type="file" id="avatar-upload" accept="image/*" style="display:none">
        </div>

        <!-- Thông tin bên phải -->
        <div class="profile-right">
            <!-- Thống kê -->
            <div class="profile-stats">
                <div class="stat-item">
                    <span class="stat-number"><?= $stats['total_posts'] ?></span>
                    <span class="stat-label">Posts</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?= $stats['total_views'] ?></span>
                    <span class="stat-label">Views</span>
                </div>
            </div>

            <!-- Mô tả -->
            <div class="profile-bio" id="bio-display">
                <p id="bio-text">
                    <?= nl2br(htmlspecialchars($user['MoTa'] ?? 'Chưa có mô tả. Nhấn Edit describe để thêm.')) ?>
                </p>
            </div>

            <!-- Form sửa mô tả (ẩn mặc định) -->
            <div class="profile-bio-edit" id="bio-edit" style="display:none;">
                <textarea id="bio-textarea" rows="5"><?= htmlspecialchars($user['MoTa'] ?? '') ?></textarea>
                <div class="bio-edit-actions">
                    <button id="save-bio-btn" class="btn-save">Lưu</button>
                    <button id="cancel-bio-btn" class="btn-cancel">Hủy</button>
                </div>
            </div>

            <a href="#" class="edit-describe-link" id="edit-bio-btn">Edit describe</a>
        </div>
    </div>

</div>

<script src="public/js/profile.js"></script>

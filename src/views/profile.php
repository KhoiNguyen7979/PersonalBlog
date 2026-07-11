<?php
/**
 * src/views/profile.php
 * Trang hồ sơ cá nhân
 */
if (!isset($_SESSION['email'])) {
    header("Location: index.php?page=signin");
    exit;
}

// Header đã require_once mySQLconnect rồi, nên $connect đã có sẵn.
// Gọi lại phòng trường hợp vào trực tiếp.
if (!isset($connect) || $connect->connect_error) {
    require_once 'src/views/mySQLconnect.php';
}

$email = $_SESSION['email'];

// Tự động thêm các cột mới nếu chưa tồn tại (tránh lỗi DB)
$connect->query("ALTER TABLE NguoiDung ADD COLUMN IF NOT EXISTS MoTa TEXT DEFAULT NULL");
$connect->query("ALTER TABLE NguoiDung ADD COLUMN IF NOT EXISTS Avatar MEDIUMBLOB DEFAULT NULL");
$connect->query("ALTER TABLE NguoiDung ADD COLUMN IF NOT EXISTS DuoiAnhAvatar VARCHAR(10) DEFAULT 'jpg'");

// Lấy thông tin người dùng
$stmt = $connect->prepare("SELECT HoTenNguoiDung, TenDangNhap, Email, MoTa FROM NguoiDung WHERE Email = ?");
if (!$stmt) {
    echo '<p style="color:red; padding:40px;">Lỗi DB: ' . htmlspecialchars($connect->error) . '</p>';
    return;
}
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo '<p style="color:red; padding:40px;">Không tìm thấy thông tin người dùng.</p>';
    return;
}

// Thống kê
$stmtStats = $connect->prepare("SELECT COUNT(*) as total_posts FROM BaiViet WHERE ID_NguoiDung = ?");
$stmtStats->bind_param("s", $email);
$stmtStats->execute();
$stats = $stmtStats->get_result()->fetch_assoc();

// Tổng lượt likes cho tất cả bài viết của user
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
?>
<link rel="stylesheet" href="public/css/profile.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">

<div class="profile-page">

    <!-- Profile Header Card -->
    <div class="profile-header-card">
        <div class="profile-avatar-section">
            <div class="avatar-wrapper">
                <img
                    id="profile-avatar"
                    src="get_image.php?email=<?= urlencode($email) ?>&v=<?= time() ?>"
                    alt="Avatar"
                    class="profile-avatar"
                    onerror="this.src='public/images/account.jpg'"
                >
                <label for="avatar-upload" class="avatar-overlay" title="Đổi ảnh đại diện">
                    <span>📷</span>
                </label>
                <input type="file" id="avatar-upload" accept="image/*" style="display:none">
            </div>
        </div>
        
        <div class="profile-info-section">
            <h1 class="profile-display-name"><?= htmlspecialchars($user['HoTenNguoiDung']) ?></h1>
            <p class="profile-username">@<?= htmlspecialchars($user['TenDangNhap']) ?></p>
            <p class="profile-email-text"><?= htmlspecialchars($user['Email']) ?></p>
            
            <div class="profile-stats-row">
                <div class="stat-badge">
                    <span class="stat-num"><?= $stats['total_posts'] ?></span>
                    <span class="stat-txt">Bài viết</span>
                </div>
                <div class="stat-badge">
                    <span class="stat-num"><?= $totalLikes ?></span>
                    <span class="stat-txt">Lượt like</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bio Section -->
    <div class="profile-section">
        <div class="section-header">
            <h3>Giới thiệu</h3>
            <a href="#" class="edit-link" id="edit-bio-btn">Chỉnh sửa</a>
        </div>
        <div class="profile-bio" id="bio-display">
            <p id="bio-text">
                <?= nl2br(htmlspecialchars($user['MoTa'] ?? 'Chưa có mô tả. Nhấn Chỉnh sửa để thêm.')) ?>
            </p>
        </div>
        <div class="profile-bio-edit" id="bio-edit" style="display:none;">
            <textarea id="bio-textarea" rows="4" placeholder="Viết gì đó về bản thân bạn..."><?= htmlspecialchars($user['MoTa'] ?? '') ?></textarea>
            <div class="edit-actions">
                <button id="save-bio-btn" class="btn-save">Lưu</button>
                <button id="cancel-bio-btn" class="btn-cancel-sm">Hủy</button>
            </div>
        </div>
    </div>

    <!-- Info Edit Section -->
    <div class="profile-section">
        <div class="section-header">
            <h3>Thông tin cá nhân</h3>
            <button id="edit-info-btn" class="edit-link">Chỉnh sửa</button>
        </div>
        
        <div class="info-display" id="info-display">
            <div class="info-row">
                <span class="info-label">Tên đăng nhập</span>
                <span class="info-value"><?= htmlspecialchars($user['TenDangNhap']) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Họ và tên</span>
                <span class="info-value"><?= htmlspecialchars($user['HoTenNguoiDung']) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value"><?= htmlspecialchars($user['Email']) ?></span>
            </div>
        </div>

        <div class="profile-info-edit" id="info-edit-section" style="display:none;">
            <form id="update-info-form">
                <div class="form-field">
                    <label>Email (Không thể thay đổi)</label>
                    <input type="text" value="<?= htmlspecialchars($user['Email']) ?>" disabled>
                </div>
                <div class="form-field">
                    <label>Tên đăng nhập</label>
                    <input type="text" id="edit-username" value="<?= htmlspecialchars($user['TenDangNhap']) ?>" required>
                </div>
                <div class="form-field">
                    <label>Họ và tên</label>
                    <input type="text" id="edit-fullname" value="<?= htmlspecialchars($user['HoTenNguoiDung']) ?>" required>
                </div>
                
                <div class="password-section">
                    <h4>Đổi mật khẩu <span style="color:#999; font-weight:400">(Bỏ trống nếu không muốn đổi)</span></h4>
                    <div class="form-field">
                        <label>Mật khẩu mới</label>
                        <input type="password" id="edit-new-password" placeholder="Nhập mật khẩu mới">
                    </div>
                    <div class="form-field">
                        <label>Xác nhận mật khẩu</label>
                        <input type="password" id="edit-confirm-password" placeholder="Nhập lại mật khẩu mới">
                    </div>
                </div>

                <div id="update-msg" class="update-msg"></div>

                <div class="edit-actions">
                    <button type="submit" class="btn-save">Lưu thay đổi</button>
                    <button type="button" id="cancel-info-btn" class="btn-cancel-sm">Hủy</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script src="public/js/profile.js"></script>

<!-- ── Crop Modal Avatar ── -->
<div id="avatar-crop-modal" class="av-crop-overlay" style="display:none;">
    <div class="av-crop-box">
        <div class="av-crop-header">
            <h3>Cắt ảnh đại diện</h3>
            <button type="button" id="av-crop-close" class="av-crop-close">✕</button>
        </div>
        <div class="av-crop-body">
            <div class="av-crop-canvas-wrapper">
                <img id="av-crop-img" src="" alt="avatar crop">
            </div>
        </div>
        <div class="av-crop-footer">
            <span class="av-crop-hint">Kéo để di chuyển • Cuộn để phóng to/thu nhỏ</span>
            <div class="av-crop-actions">
                <button type="button" id="av-crop-cancel" class="btn-av-cancel">Hủy</button>
                <button type="button" id="av-crop-confirm" class="btn-av-confirm">Xác nhận</button>
            </div>
        </div>
    </div>
</div>

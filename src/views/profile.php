<?php
/**
 * src/views/profile.php
 * Trang hồ sơ cá nhân
 */
if (!isset($_SESSION['email'])) {
    header("Location: index.php?page=signin");
    exit;
}

require_once("mySQLconnect.php");

$email = $_SESSION['email'];

// Lấy thông tin người dùng
$stmt = $connect->prepare("SELECT HoTenNguoiDung, TenDangNhap, Email, MoTa FROM NguoiDung WHERE Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Thống kê
$stmtStats = $connect->prepare("
    SELECT COUNT(*) as total_posts
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
                    src="get_image.php?email=<?= urlencode($email) ?>&v=<?= time() ?>"
                    alt="Avatar"
                    class="profile-avatar"
                    onerror="this.src='public/images/account.jpg'"
                >
            </div>
            <h1 class="profile-name"><?= htmlspecialchars($user['TenDangNhap']) ?></h1>
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
            </div>

            <!-- Mô tả -->
            <div class="profile-bio" id="bio-display">
                <p id="bio-text">
                    <?= nl2br(htmlspecialchars($user['MoTa'] ?? 'Chưa có mô tả.')) ?>
                </p>
            </div>

            <!-- ĐÃ XÓA: profile-bio-edit và nút edit-bio-btn -->

            <button id="edit-info-btn" class="btn-edit-info">Cập nhật thông tin cá nhân</button>

            <div class="profile-info-edit" id="info-edit-section" style="display:none; margin-top: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
                <h3>Chỉnh sửa thông tin</h3>
                <form id="update-info-form">
                    <div class="form-group">
                        <label>Email (Không thể thay đổi):</label>
                        <input type="text" value="<?= htmlspecialchars($user['Email']) ?>" disabled style="width: 100%; padding: 8px; margin-bottom: 10px; background: #eee;">
                    </div>
                    
                    <div class="form-group">
                        <label>Tên đăng nhập:</label>
                        <input type="text" id="edit-username" value="<?= htmlspecialchars($user['TenDangNhap']) ?>" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
                    </div>
                    
                    <div class="form-group">
                        <label>Họ và tên:</label>
                        <input type="text" id="edit-fullname" value="<?= htmlspecialchars($user['HoTenNguoiDung']) ?>" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
                    </div>

                    <!-- THÊM MỚI: Form nhập mô tả -->
                    <div class="form-group">
                        <label>Mô tả bản thân:</label>
                        <textarea id="edit-mota" rows="4" style="width: 100%; padding: 8px; margin-bottom: 10px; resize: vertical;"><?= htmlspecialchars($user['MoTa'] ?? '') ?></textarea>
                    </div>
                    
                    <hr style="margin: 15px 0;">
                    
                    <h4>Đổi mật khẩu (Bỏ trống nếu không muốn đổi)</h4>
                    <div class="form-group">
                        <label>Mật khẩu mới:</label>
                        <input type="password" id="edit-new-password" placeholder="Nhập mật khẩu mới" style="width: 100%; padding: 8px; margin-bottom: 10px;">
                    </div>
                    
                    <div class="form-group">
                        <label>Xác nhận mật khẩu mới:</label>
                        <input type="password" id="edit-confirm-password" placeholder="Nhập lại mật khẩu mới" style="width: 100%; padding: 8px; margin-bottom: 10px;">
                    </div>

                    <div id="update-msg" style="color: red; margin-bottom: 10px;"></div>

                    <div class="info-edit-actions">
                        <button type="submit" class="btn-save">Lưu thay đổi</button>
                        <button type="button" id="cancel-info-btn" class="btn-cancel">Hủy</button>
                    </div>
                </form>
            </div>
        </div>

</div>

<script src="public/js/profile.js"></script>

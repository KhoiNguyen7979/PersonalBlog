<?php
// === TRANG HỒ SƠ CÁ NHÂN ===
//Nếu chưa đăng nhập thì tự động chuyển hướng qua trang đăng nhập
if (!isset($_SESSION['email'])) {
    header("Location: index.php?page=signin");
    exit;
}

//Thiết lập kết nối với CSDL
if (!isset($connect) || $connect->connect_error) {
    require_once __DIR__ . '/../mySQLconnect.php';
}

$email = $_SESSION['email'];
$isAdmin = isset($_SESSION['vaitro']) && $_SESSION['vaitro'] === 'admin';

// Lấy thông tin người dùng
$stmt = $connect->prepare("SELECT HoTenNguoiDung, TenDangNhap, Email, MoTa FROM NguoiDung WHERE Email = ?");
//Nếu lấy dữ liệu không được thông báo lỗi
if (!$stmt) {
    echo '<p style="color:red; padding:40px;">DB Error: ' . htmlspecialchars($connect->error) . '</p>';
    return;
}
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
//Nếu không có dữ liệu từ người dùng, thông báo lỗi
if (!$user) {
    echo '<p style="color:red; padding:40px;">User information not found.</p>';
    return;
}

// Thống kê tổng số bài viết đã đăng của người dùng
$stmtStats = $connect->prepare("SELECT COUNT(*) as total_posts FROM BaiViet WHERE ID_NguoiDung = ?");
$stmtStats->bind_param("s", $email);
$stmtStats->execute();
$stats = $stmtStats->get_result()->fetch_assoc();

// Thống kê tổng lượt likes cho tất cả bài viết của người dùng
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

    <!--Thẻ Header -->
    <div class="profile-header-card">
        <div class="profile-avatar-section">
            <div class="avatar-wrapper">
                <!-- Lấy ảnh từ CSDL, nếu ko có ảnh mặc định để ảnh account.jpg -->
                <img
                    id="profile-avatar"
                    src="get_image.php?email=<?= urlencode($email) ?>&v=<?= time() ?>"
                    alt="Avatar"
                    class="profile-avatar"
                    onerror="this.src='public/images/account.jpg'"
                >
                <!-- Nếu vai trò không phải là admin thì sẽ được đổi ảnh avatar -->
                <?php if (!$isAdmin): ?>
                <label for="avatar-upload" class="avatar-overlay" title="Change avatar">
                    <span>📷</span>
                </label>
                <input type="file" id="avatar-upload" accept="image/*" style="display:none">
                <?php endif; ?>
            </div>
        </div>
        <!-- Phần hiển thị thông tin người dùng: họ tên người dùng, tên đăng nhập, email tài khoản, tổng số bài viết và tổng số lượt thích -->
        <div class="profile-info-section">
            <h1 class="profile-display-name"><?= htmlspecialchars($user['HoTenNguoiDung']) ?></h1>
            <p class="profile-username">@<?= htmlspecialchars($user['TenDangNhap']) ?></p>
            <p class="profile-email-text"><?= htmlspecialchars($user['Email']) ?></p>
            
            <div class="profile-stats-row">
                <div class="stat-badge">
                    <span class="stat-num"><?= $stats['total_posts'] ?></span>
                    <span class="stat-txt">Posts</span>
                </div>
                <div class="stat-badge">
                    <span class="stat-num"><?= $totalLikes ?></span>
                    <span class="stat-txt">Likes</span>
                </div>
            </div>
        </div>
    </div>

    <!--Phần chỉnh sửa mô tả của người dùng-->
    <div class="profile-section">
        <div class="section-header">
            <h3>About</h3>
            <!-- Nếu vai trò không phải là admin thì sẽ được cập nhật mô tả của mình lên -->
            <?php if (!$isAdmin): ?>
            <a href="#" class="edit-link" id="edit-bio-btn">Edit</a>
            <?php endif; ?>
        </div>
        <div class="profile-bio" id="bio-display">
            <p id="bio-text">
                <!-- Nếu người dùng chưa có mô tả -->
                <?= nl2br(htmlspecialchars($user['MoTa'] ?? 'No description yet. Click Edit to add one.')) ?>
            </p>
        </div>
        <div class="profile-bio-edit" id="bio-edit" style="display:none;">
            <textarea id="bio-textarea" rows="4" placeholder="Write something about yourself..."><?= htmlspecialchars($user['MoTa'] ?? '') ?></textarea>
            <div class="edit-actions">
                <button id="save-bio-btn" class="btn-save">Save</button>
                <button id="cancel-bio-btn" class="btn-cancel-sm">Cancel</button>
            </div>
        </div>
    </div>

    <!--Phần chỉnh sửa thông tin chi tiết của người dùng-->
    <div class="profile-section">
        <div class="section-header">
            <h3>Personal Information</h3>
            <!-- Nếu vai trò không phải là admin thì sẽ được cập nhật thông tin chi tiết của mình lên -->
            <?php if (!$isAdmin): ?>
            <button id="edit-info-btn" class="edit-link">Edit</button>
            <?php endif; ?>
        </div>
        
        <div class="info-display" id="info-display">
            <div class="info-row">
                <span class="info-label">Username</span>
                <span class="info-value"><?= htmlspecialchars($user['TenDangNhap']) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Full Name</span>
                <span class="info-value"><?= htmlspecialchars($user['HoTenNguoiDung']) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value"><?= htmlspecialchars($user['Email']) ?></span>
            </div>
        </div>
        <!-- form chỉnh sửa thông tin chi tiết cá nhân -->
        <div class="profile-info-edit" id="info-edit-section" style="display:none;">
            <form id="update-info-form">
                <div class="form-field">
                    <label>Email (Cannot be changed)</label>
                    <input type="text" value="<?= htmlspecialchars($user['Email']) ?>" disabled>
                </div>
                <div class="form-field">
                    <label>Username</label>
                    <input type="text" id="edit-username" value="<?= htmlspecialchars($user['TenDangNhap']) ?>" required>
                </div>
                <div class="form-field">
                    <label>Full Name</label>
                    <input type="text" id="edit-fullname" value="<?= htmlspecialchars($user['HoTenNguoiDung']) ?>" required>
                </div>
                
                <div class="password-section">
                    <h4>Change Password <span style="color:#999; font-weight:400">(Leave empty to keep current)</span></h4>
                    <div class="form-field">
                        <label>New Password</label>
                        <div class="password-wrap">
                            <input type="password" id="edit-new-password" placeholder="Enter new password">
                            <button type="button" class="toggle-pw" aria-label="Show password">
                                <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="form-field">
                        <label>Confirm Password</label>
                        <div class="password-wrap">
                            <input type="password" id="edit-confirm-password" placeholder="Re-enter new password">
                            <button type="button" class="toggle-pw" aria-label="Show password">
                                <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div id="update-msg" class="update-msg"></div>

                <div class="edit-actions">
                    <button type="submit" class="btn-save">Save Changes</button>
                    <button type="button" id="cancel-info-btn" class="btn-cancel-sm">Cancel</button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- ── Chỉnh sửa ảnh Avatar khi up ảnh lên ── -->
<div id="avatar-crop-modal" class="av-crop-overlay" style="display:none;">
    <div class="av-crop-box">
        <div class="av-crop-header">
            <h3>Crop avatar</h3>
            <button type="button" id="av-crop-close" class="av-crop-close">✕</button>
        </div>
        <div class="av-crop-body">
            <div class="av-crop-canvas-wrapper">
                <img id="av-crop-img" src="" alt="avatar crop">
            </div>
        </div>
        <div class="av-crop-footer">
            <span class="av-crop-hint">Drag to move • Scroll to zoom</span>
            <div class="av-crop-actions">
                <button type="button" id="av-crop-cancel" class="btn-av-cancel">Cancel</button>
                <button type="button" id="av-crop-confirm" class="btn-av-confirm">Confirm</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script src="public/js/profile.js"></script>
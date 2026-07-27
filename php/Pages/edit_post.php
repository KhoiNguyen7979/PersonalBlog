<?php
// === TRANG CHỈNH SỬA BÀI VIẾT ===
// Các block: Form sửa bài (ảnh hiện tại + ảnh thay thế + tiêu đề + tóm tắt + thể loại + nội dung) | Crop Modal
// Script inline: CropperJS, submit form qua  API
if (!isset($_SESSION['email'])) {
    header("Location: index.php?page=signin");
    exit;
}
require_once __DIR__ . '/../mySQLconnect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$email = $_SESSION['email'];
$isAdmin = isset($_SESSION['vaitro']) && $_SESSION['vaitro'] === 'admin';

if ($isAdmin) {
    $stmt = $connect->prepare("SELECT * FROM BaiViet WHERE ID_BaiViet = ?");
    $stmt->bind_param("i", $id);
} else {
    $stmt = $connect->prepare("SELECT * FROM BaiViet WHERE ID_BaiViet = ? AND ID_NguoiDung = ?");
    $stmt->bind_param("is", $id, $email);
}
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
    echo "Post not found or you do not have permission to edit.";
    exit;
}

// Lấy danh sách ảnh hiện tại
$imgStmt = $connect->prepare("SELECT ID_Anh, Ten_File_Anh, IsThumb FROM Pics WHERE ID_BaiViet = ? ORDER BY IsThumb DESC, ID_Anh ASC");
$imgStmt->bind_param("i", $id);
$imgStmt->execute();
$images = $imgStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$checkimgempty=false;
if(empty($images)){
    $checkimgempty=true;
}
$imgStmt->close();
$connect->close();
?>
<link rel="stylesheet" href="public/css/create_blog.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">

<div class="create-blog-page">
    <div class="create-blog-header">
        <h1>Edit Blog</h1>
    </div>

    <form id="edit-blog-form" class="create-blog-form">
        <input type="hidden" name="post_id" value="<?= $id ?>">
        
        <!-- Ảnh hiện tại -->
        <div class="form-group">
            <label>Current images</label>
            <div class="edit-images-grid" id="edit-images-grid">
                <?php if($checkimgempty):?>
                     <div class="edit-img-item">
                        <img src="public/images/account.jpg">
                    </div>
                <?php endif; ?>
                <?php foreach ($images as $img): ?>
                    <div class="edit-img-item" data-id="<?= $img['ID_Anh'] ?>">
                        <img 
                        src="get_image.php?id=<?= $id ?>&idx=<?= array_search($img, $images) ?>&v=<?= time() ?>" 
                        alt="<?= htmlspecialchars($img['Ten_File_Anh']) ?>"
                        onerror="src='public/images/account.jpg'"
                        >
                    </div>
                <?php endforeach; ?>
            </div>
            <div id="deleted-ids-container"></div>
        </div>

        <!-- Đổi ảnh mới -->
        <div class="form-group">
            <label>Replace image</label>
            <div class="thumbnail-upload" id="edit-thumbnail-upload">
                <input type="file" id="edit-new-image" name="new_image" accept="image/*">
                <div class="upload-placeholder" id="edit-upload-placeholder">
                    <span class="icon">📷</span>
                    <span class="text">Click to choose a new image</span>
                </div>
                <img id="edit-new-preview" src="" alt="">
            </div>
        </div>

        <div class="form-group">
            <label for="title">Title*</label>
            <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($post['TieuDe']) ?>" required>
        </div>

        <div class="form-group">
            <label for="summary">Summary</label>
            <textarea id="summary" name="summary" class="form-control" rows="2" required><?= htmlspecialchars($post['TomTat']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category" class="form-control" required>
                <option value="technology" <?= $post['ID_The_Loai'] == 'technology' ? 'selected' : '' ?>>Technology</option>
                <option value="skill" <?= $post['ID_The_Loai'] == 'skill' ? 'selected' : '' ?>>Skill</option>
                <option value="story" <?= $post['ID_The_Loai'] == 'story' ? 'selected' : '' ?>>Story</option>
                <option value="music" <?= $post['ID_The_Loai'] == 'music' ? 'selected' : '' ?>>Music</option>
            </select>
        </div>

        <div class="form-group">
            <label for="content">Describe</label>
            <textarea id="content" name="content" class="form-control" rows="15" required><?= htmlspecialchars($post['NoiDung']) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="button" class="btn-cancel" onclick="window.history.back()">Cancel</button>
            <button type="submit" class="btn-publish" id="update-btn">Update</button>
        </div>
    </form>
</div>

<div id="create-toast" class="create-toast"></div>

<!-- Crop Hình Ảnh -->
<div class="crop-modal-overlay" id="crop-modal">
    <div class="crop-modal-box">
        <div class="crop-modal-header">
            <h3>Crop image</h3>
            <button type="button" class="crop-modal-close" id="crop-close">&times;</button>
        </div>
        <div class="crop-modal-body">
            <div class="crop-canvas-wrapper" id="crop-canvas-wrapper">
                <img id="crop-image" src="">
            </div>
        </div>
        <div class="crop-modal-footer">
            <span class="crop-hint">Drag to select image area</span>
            <div class="crop-modal-actions">
                <button type="button" class="btn-crop-cancel" id="crop-cancel">Skip</button>
                <button type="button" class="btn-crop-confirm" id="crop-confirm">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script src="public/js/edit_post.js"> </script>
<style>
.edit-images-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.edit-img-item {
    position: relative;
    width: 120px;
    height: 90px;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #ddd;
    transition: opacity 0.2s;
}

.edit-img-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.edit-img-delete {
    position: absolute;
    top: 4px;
    right: 4px;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: rgba(220,0,0,0.8);
    color: #fff;
    border: none;
    font-size: 14px;
    line-height: 1;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s;
}

.edit-img-delete:hover {
    background: rgba(180,0,0,1);
}
</style>
<?php
if (!isset($_SESSION['email'])) {
    header("Location: index.php?page=signin");
    exit;
}
require_once 'src/views/mySQLconnect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$email = $_SESSION['email'];

$stmt = $connect->prepare("SELECT * FROM BaiViet WHERE ID_BaiViet = ? AND ID_NguoiDung = ?");
$stmt->bind_param("is", $id, $email);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
    echo "Bài viết không tồn tại hoặc bạn không có quyền chỉnh sửa.";
    exit;
}

// Lấy danh sách ảnh hiện tại
$imgStmt = $connect->prepare("SELECT ID_Anh, Ten_File_Anh, IsThumb FROM Pics WHERE ID_BaiViet = ? ORDER BY IsThumb DESC, ID_Anh ASC");
$imgStmt->bind_param("i", $id);
$imgStmt->execute();
$images = $imgStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$imgStmt->close();
$connect->close();
?>
<link rel="stylesheet" href="public/css/create_blog.css">

<div class="create-blog-page">
    <div class="create-blog-header">
        <h1>Edit Blog</h1>
    </div>

    <form id="edit-blog-form" class="create-blog-form">
        <input type="hidden" name="post_id" value="<?= $id ?>">
        
        <!-- Ảnh hiện tại -->
        <div class="form-group">
            <label>Ảnh hiện tại</label>
            <div class="edit-images-grid" id="edit-images-grid">
                <?php foreach ($images as $img): ?>
                    <div class="edit-img-item" data-id="<?= $img['ID_Anh'] ?>">
                        <img src="get_image.php?id=<?= $id ?>&idx=<?= array_search($img, $images) ?>" alt="<?= htmlspecialchars($img['Ten_File_Anh']) ?>">
                        <?php if ($img['IsThumb']): ?>
                            <span class="edit-img-badge">Thumb</span>
                        <?php endif; ?>
                        <button type="button" class="edit-img-delete" title="Xoá ảnh này">&times;</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <div id="deleted-ids-container"></div>
        </div>

        <!-- Thêm ảnh mới -->
        <div class="form-group">
            <label>Ảnh bổ sung</label>
            <div class="extra-images-upload" id="extra-images-upload">
                <input type="file" id="new-images" name="new_images[]" accept="image/*" multiple>
                <div class="upload-placeholder" id="extra-placeholder">
                    <span class="icon">🖼️</span>
                    <span class="text">Chọn thêm ảnh (giữ Ctrl để chọn nhiều)</span>
                </div>
            </div>
            <div id="extra-preview-list" class="extra-preview-list"></div>
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
            <label for="category">Thể loại</label>
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
            <button type="button" class="btn-cancel" onclick="window.history.back()">Hủy</button>
            <button type="submit" class="btn-publish" id="update-btn">Cập nhật</button>
        </div>
    </form>
</div>

<div id="create-toast" class="create-toast"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const grid = document.getElementById('edit-images-grid');
    const container = document.getElementById('deleted-ids-container');
    const newImagesInput = document.getElementById('new-images');
    const extraPreview = document.getElementById('extra-preview-list');

    // Xoá ảnh hiện tại
    grid.addEventListener('click', function(e) {
        const btn = e.target.closest('.edit-img-delete');
        if (!btn) return;
        const item = btn.closest('.edit-img-item');
        const id = item.dataset.id;
        const inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = 'delete_ids[]';
        inp.value = id;
        container.appendChild(inp);
        item.remove();
    });

    // Preview ảnh mới (cộng dồn khi chọn nhiều lần)
    var accumulatedFiles = [];
    if (newImagesInput) {
        newImagesInput.addEventListener('change', function() {
            Array.from(this.files).forEach(function(f) { accumulatedFiles.push(f); });
            renderPreviews();
            // Reset input để có thể chọn lại cùng file
            this.value = '';
        });

        function renderPreviews() {
            extraPreview.innerHTML = '';
            accumulatedFiles.forEach(function(file, i) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var div = document.createElement('div');
                    div.className = 'extra-preview-item';
                    div.innerHTML = '<img src="' + e.target.result + '" alt="Ảnh ' + (i+1) + '"><button type="button" class="extra-remove-btn" data-idx="' + i + '">&times;</button>';
                    extraPreview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        extraPreview.addEventListener('click', function(e) {
            if (e.target.classList.contains('extra-remove-btn')) {
                var idx = parseInt(e.target.dataset.idx);
                accumulatedFiles.splice(idx, 1);
                renderPreviews();
            }
        });
    }

    // Submit form
    document.getElementById('edit-blog-form').addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var btn = document.getElementById('update-btn');
        btn.innerText = 'Đang cập nhật...';
        btn.disabled = true;

        // Thêm ảnh mới vào FormData (cộng dồn)
        accumulatedFiles.forEach(function(f) {
            formData.append('new_images[]', f);
        });

        fetch('api/update_post.php', { method: 'POST', body: formData })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                alert('Cập nhật thành công!');
                window.location.href = '?page=blog';
            } else {
                alert('Lỗi: ' + data.message);
                btn.innerText = 'Cập nhật';
                btn.disabled = false;
            }
        });
    });
});
</script>

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

.edit-img-badge {
    position: absolute;
    top: 4px;
    left: 4px;
    background: rgba(0,0,0,0.6);
    color: #fff;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 600;
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

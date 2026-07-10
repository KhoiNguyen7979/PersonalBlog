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
?>
<link rel="stylesheet" href="public/css/create_blog.css">

<div class="create-blog-page">
    <div class="create-blog-header">
        <h1>Edit Blog</h1>
    </div>

    <form id="edit-blog-form" class="create-blog-form">
        <input type="hidden" name="post_id" value="<?= $id ?>">
        
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

<script>
document.getElementById('edit-blog-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const btn = document.getElementById('update-btn');
    btn.innerText = 'Đang cập nhật...';
    
    fetch('api/update_post.php', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            alert('Cập nhật thành công!');
            window.location.href = '?page=blog';
        } else {
            alert('Lỗi: ' + data.message);
            btn.innerText = 'Cập nhật';
        }
    });
});
</script>
<?php
// === TRANG TẠO BLOG MỚI ===
// Các block: Header | Form (ảnh bìa + tiêu đề + tóm tắt + thể loại + nội dung) | Crop Modal
if (!isset($_SESSION['email'])) {
    header("Location: index.php?page=signin");
    exit;
}
?>
<link rel="stylesheet" href="public/css/create_blog.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">

<div class="create-blog-page">
    <div class="create-blog-header">
        <h1>Write a Blog</h1>
        <p>Share your stories and knowledge with the world.</p>
    </div>

    <form id="create-blog-form" class="create-blog-form">
        
        <!-- Ảnh bài viết -->
        <div class="form-group">
            <label>Featured Image*</label>
            <div class="thumbnail-upload" id="thumbnail-upload-box">
                <input type="file" id="images-input" name="images[]" accept="image/*" required>
                <div class="upload-placeholder" id="upload-placeholder">
                    <span class="icon">📷</span>
                    <span class="text">Click to choose an image</span>
                </div>
                <img id="thumbnail-preview" src="" alt="Preview">
            </div>
        </div>

        <!-- Tiêu đề -->
        <div class="form-group">
            <label for="title">Title*</label>
            <input type="text" id="title" name="title" class="form-control" placeholder="Enter post title..." required>
        </div>

        <!-- Tóm tắt -->
        <div class="form-group">
            <label for="summary">Summary</label>
            <textarea id="summary" name="summary" class="form-control" rows="2" placeholder="A short summary of your post..." required></textarea>
        </div>

        <!-- Thể loại -->
        <div class="form-group">
            <label for="category">Category</label>
            <div class="custom-select-wrapper">
                <select id="category" name="category" class="form-control custom-select" required>
                    <option value="" disabled selected>-- Select a category --</option>
                    <option value="technology">Technology</option>
                    <option value="skill">Skill</option>
                    <option value="story">Story</option>
                    <option value="music">Music</option>
                </select>
            </div>
        </div>

        <!-- Nội dung -->
        <div class="form-group">
            <label for="content">Desribe</label>
            <textarea id="content" name="content" class="form-control editor-content" rows="15" placeholder="What's on your mind? Write it here..." required></textarea>
        </div>

        <!-- Nút hủy và đăng bài viết -->
        <div class="form-actions">
            <button type="button" class="btn-cancel" onclick="window.history.back()">Cancel</button>
            <button type="submit" class="btn-publish" id="publish-btn">Publish</button>
        </div>
    </form>
</div>

<!-- Thông báo -->
<div id="create-toast" class="create-toast"></div>

<!-- Crop hình ảnh -->
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
<script src="public/js/create_blog.js"></script>

<?php
/**
 * php/Pages/create_blog.php
 * Giao diện tạo bài viết mới
 */
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
        <p>Chia sẻ câu chuyện, kiến thức của bạn với mọi người.</p>
    </div>

    <form id="create-blog-form" class="create-blog-form">
        
        <!-- Ảnh bài viết -->
        <div class="form-group">
            <label>Ảnh bài viết*</label>
            <div class="thumbnail-upload" id="thumbnail-upload-box">
                <input type="file" id="images-input" name="images[]" accept="image/*" required>
                <div class="upload-placeholder" id="upload-placeholder">
                    <span class="icon">📷</span>
                    <span class="text">Click để chọn ảnh</span>
                </div>
                <img id="thumbnail-preview" src="" alt="Preview">
            </div>
        </div>

        <!-- Tiêu đề -->
        <div class="form-group">
            <label for="title">Title*</label>
            <input type="text" id="title" name="title" class="form-control" placeholder="Nhập tiêu đề bài viết..." required>
        </div>

        <!-- Tóm tắt -->
        <div class="form-group">
            <label for="summary">Summary</label>
            <textarea id="summary" name="summary" class="form-control" rows="2" placeholder="Một đoạn ngắn mô tả nội dung bài viết..." required></textarea>
        </div>

        <!-- Thể loại -->
        <div class="form-group">
            <label for="category">Thể loại</label>
            <div class="custom-select-wrapper">
                <select id="category" name="category" class="form-control custom-select" required>
                    <option value="" disabled selected>-- Chọn thể loại --</option>
                    <option value="technology">Technology (Công nghệ)</option>
                    <option value="skill">Skill (Kỹ năng)</option>
                    <option value="story">Story (Câu chuyện)</option>
                    <option value="music">Music (Âm nhạc)</option>
                </select>
            </div>
        </div>

        <!-- Nội dung -->
        <div class="form-group">
            <label for="content">Desribe</label>
            <textarea id="content" name="content" class="form-control editor-content" rows="15" placeholder="Bạn đang nghĩ gì? Hãy viết ra đây..." required></textarea>
        </div>

        <!-- Nút hành động -->
        <div class="form-actions">
            <button type="button" class="btn-cancel" onclick="window.history.back()">Hủy</button>
            <button type="submit" class="btn-publish" id="publish-btn">Đăng bài</button>
        </div>
    </form>
</div>

<!-- Toast notification -->
<div id="create-toast" class="create-toast"></div>

<!-- Crop Modal -->
<div class="crop-modal-overlay" id="crop-modal">
    <div class="crop-modal-box">
        <div class="crop-modal-header">
            <h3>Xén ảnh</h3>
            <button type="button" class="crop-modal-close" id="crop-close">&times;</button>
        </div>
        <div class="crop-modal-body">
            <div class="crop-canvas-wrapper" id="crop-canvas-wrapper">
                <img id="crop-image" src="">
            </div>
        </div>
        <div class="crop-modal-footer">
            <span class="crop-hint">Kéo thả để chọn vùng ảnh</span>
            <div class="crop-modal-actions">
                <button type="button" class="btn-crop-cancel" id="crop-cancel">Bỏ qua</button>
                <button type="button" class="btn-crop-confirm" id="crop-confirm">Xác nhận</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script src="public/js/create_blog.js"></script>

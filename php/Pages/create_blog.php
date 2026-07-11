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

<div class="create-blog-page">
    <div class="create-blog-header">
        <h1>Write a Blog</h1>
        <p>Chia sẻ câu chuyện, kiến thức của bạn với mọi người.</p>
    </div>

    <form id="create-blog-form" class="create-blog-form">
        
        <!-- Ảnh bìa -->
        <div class="form-group">
            <label>Thumbnail*</label>
            <div class="thumbnail-upload" id="thumbnail-upload-box">
                <input type="file" id="thumbnail" name="thumbnail" accept="image/*" required>
                <div class="upload-placeholder" id="upload-placeholder">
                    <span class="icon">📷</span>
                    <span class="text">Click để tải ảnh lên*</span>
                </div>
                <img id="thumbnail-preview" src="" alt="Preview">
            </div>
        </div>

        <!-- Ảnh bổ sung -->
        <div class="form-group">
            <label>Ảnh bổ sung (tùy chọn)</label>
            <div class="extra-images-upload" id="extra-images-upload">
                <input type="file" id="extra-images" name="extra_images[]" accept="image/*" multiple>
                <div class="upload-placeholder" id="extra-placeholder">
                    <span class="icon">🖼️</span>
                    <span class="text">Chọn thêm ảnh (giữ Ctrl để chọn nhiều)</span>
                </div>
            </div>
            <div id="extra-preview-list" class="extra-preview-list"></div>
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

<script src="public/js/create_blog.js"></script>

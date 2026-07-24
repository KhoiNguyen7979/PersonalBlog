<?php
// === TRANG MYBLOG ===
// Các block: My Posts (có khi đăng nhập) | Sign-in CTA (khi chưa đăng nhập) | Posts from the other (chỉ ở trang Home)
?>
<link rel="stylesheet" href="public/css/blog.css">

<div class="blog-page">
    <!-- Nếu người dùng ĐÃ ĐĂNG NHẬP -->
    <?php if (isset($_SESSION['email'])): ?>
    <!-- ── SECTION 1: MY POSTS ── -->
    <section class="blog-section" id="my-posts-section">
        <div class="blog-section-header">
            <h2 class="section-title">My Posts</h2>
            <hr class="section-title-line">
            <a href="?page=create_blog" class="create-blog-btn">New Blog</a>
        </div>

        <div class="blog-controls">
            <div class="category-tabs" data-target="my">
                <button class="cat-btn active" data-cat="all">All</button>
                <span class="cat-sep">|</span>
                <button class="cat-btn" data-cat="technology">Technology</button>
                <span class="cat-sep">|</span>
                <button class="cat-btn" data-cat="skill">Skill</button>
                <span class="cat-sep">|</span>
                <button class="cat-btn" data-cat="story">Story</button>
                <span class="cat-sep">|</span>
                <button class="cat-btn" data-cat="music">Music</button>
            </div>
            <div class="filter-box">
                <label>Filter:</label>
                <select class="filter-select" data-target="my">
                    <option value="newest">Newest</option>
                    <option value="oldest">Oldest</option>
                    <option value="likes">Most Likes</option>
                </select>
            </div>
        </div>

        <div class="posts-container" id="my-posts-container">
            <div class="loading-spinner">Loading...</div>
        </div>

        <div class="read-more-wrap">
            <button class="read-more-btn" id="my-read-more" data-page="1" data-type="my" style="display:none;">Read more</button>
        </div>
    </section>
    <?php endif; ?>
<!-- Nếu người dùng CHƯA ĐĂNG NHẬP -->
    <?php if (!isset($_SESSION['email'])): ?>
    <!-- Hiển thị ra đề xuất đăng ký/đăng nhập -->
    <section class="blog-section">
        <div class="login-cta">
            <h3 class="login-cta-title">Start blogging today!</h3>
            <p class="login-cta-text">Create an account to write and share your own blog posts with the community.</p>
            <div class="login-cta-buttons">
                <a href="?page=signup" class="login-cta-btn primary">Sign Up</a>
                <a href="?page=signin" class="login-cta-btn secondary">Sign In</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php 
    // Chỉ hiển thị "Posts from the other" nếu KHÔNG PHẢI đang ở trang My Blog chuyên biệt
    // (tức là chỉ hiện ở trang Home)
    if ($page !== 'blog'): 
    ?>
    <!-- ── SECTION 2: POSTS FROM THE OTHER ── -->
    <section class="blog-section" id="other-posts-section">
        <div class="blog-section-header">
            <h2 class="section-title">Posts from the other</h2>
            <hr class="section-title-line green">
        </div>

        <div class="blog-controls">
            <div class="category-tabs" data-target="other">
                <button class="cat-btn active" data-cat="all">All</button>
                <span class="cat-sep">|</span>
                <button class="cat-btn" data-cat="technology">Technology</button>
                <span class="cat-sep">|</span>
                <button class="cat-btn" data-cat="skill">Skill</button>
                <span class="cat-sep">|</span>
                <button class="cat-btn" data-cat="story">Story</button>
                <span class="cat-sep">|</span>
                <button class="cat-btn" data-cat="music">Music</button>
            </div>
            <div class="filter-box">
                <label>Filter:</label>
                <select class="filter-select" data-target="other">
                    <option value="newest">Newest</option>
                    <option value="oldest">Oldest</option>
                    <option value="likes">Most Likes</option>
                </select>
            </div>
        </div>

        <div class="posts-container" id="other-posts-container">
            <div class="loading-spinner">Loading...</div>
        </div>

        <!-- Pagination -->
        <div class="pagination-wrap" id="other-pagination"></div>
    </section>
    <?php endif; ?>

</div>

<script src="public/js/blog.js"></script>

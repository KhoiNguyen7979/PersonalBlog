<?php
/**
 * php/Pages/myBlog.php
 * Giao diện trang blog - hiển thị My Posts và Posts from the other
 */
?>
<link rel="stylesheet" href="public/css/blog.css">

<div class="blog-page">

    <?php if (isset($_SESSION['email'])): ?>
    <!-- ── SECTION 1: MY POSTS ── -->
    <section class="blog-section" id="my-posts-section">
        <div class="blog-section-header">
            <h2 class="section-title">My Posts</h2>
            <hr class="section-title-line">
            <a href="?page=create_blog" class="create-blog-btn">✏️ New Blog </a>
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
            <div class="loading-spinner">Đang tải...</div>
        </div>

        <div class="read-more-wrap">
            <button class="read-more-btn" id="my-read-more" data-page="1" data-type="my" style="display:none;">Read more</button>
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
            <div class="loading-spinner">Đang tải...</div>
        </div>

        <!-- Pagination -->
        <div class="pagination-wrap" id="other-pagination"></div>
    </section>
    <?php endif; ?>

</div>

<script src="public/js/blog.js"></script>

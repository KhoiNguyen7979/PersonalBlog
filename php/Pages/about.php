<?php
// === TRANG ABOUT ===

$pageTitle = "About - BloggerZ";

require_once __DIR__ . '/../mySQLconnect.php';
$isAdmin = isset($_SESSION['vaitro']) && $_SESSION['vaitro'] === 'admin';

// Lấy các reviews từ CSDL
$reviews = [];
$stmt = $connect->query("SELECT HoTen, Email, NoiDung, NgayTao FROM Reviews ORDER BY NgayTao DESC LIMIT 10");
if ($stmt) {
    while ($row = $stmt->fetch_assoc()) {
        $reviews[] = $row;
    }
}
?>
<link rel="stylesheet" href="public/css/about.css">

<main class="about-main">
    <!-- Phần giới thiệu -->
    <section class="about-hero">
        <div class="about-hero-content">
            <h1 class="about-title">Welcome to <span>BloggerZ</span></h1>
            <p class="about-subtitle">A little corner of the internet for thoughts, ideas, and stories.</p>
        </div>
    </section>

    <!-- Phần nội dung -->
    <section class="about-story">
        <div class="story-container">
            <div class="story-text">
                <h2>The Story So Far</h2>
                <p>If you've found your way here, thank you for stopping by.</p>
                <p>This little corner of the internet is where I collect thoughts, ideas, stories, and things that make me curious. Some posts are about technology, some are about life, and some exist simply because I wanted to understand something a little better than I did yesterday.</p>
                <p>I don't claim to have all the answers. Most of the time, I'm just someone trying to make sense of the world — one question, one project, and one conversation at a time.</p>
                
                <div class="quote-box">
                    "The goal isn't to convince everyone. The goal is to stay curious."
                </div>

                <p>I believe the internet is still one of the most fascinating places ever created. A place where strangers can share knowledge, challenge perspectives, and leave behind ideas that travel much farther than they ever will.</p>
                <p>This website is my contribution to that space. You'll find reflections, experiments, things I'm learning, things I'm building, and occasionally things I'm struggling to understand. Some thoughts will age well. Others probably won't. That's part of the process.</p>
                <p>Thank you for reading, exploring, and spending a small piece of your time here. I hope you find something worth taking with you.</p>
                <p class="signature">— See you around.</p>
            </div>
            
            <div class="story-image">
                <img src="public/images/bloggerZ.png" alt="BloggerZ Logo">
            </div>
        </div>
    </section>

    <!-- Phần Reviews của người dùng -->
    <section class="about-reviews">
        <div class="reviews-header">
            <h2>What People Say</h2>
            <p>Thoughts and feedback from our amazing readers.</p>
        </div>
        
        <div class="reviews-carousel-wrapper">
            <!-- Nếu chưa có ai review -->
            <?php if (empty($reviews)): ?>
                <div class="no-reviews">
                    <p>No reviews yet. Be the first to leave one!</p>
                </div>
            <?php else: ?>
                <div class="reviews-track" id="reviews-track">
                    <?php foreach ($reviews as $rev): 
                        $name = htmlspecialchars($rev['HoTen']);
                        $email = htmlspecialchars($rev['Email']);
                        $content = htmlspecialchars($rev['NoiDung']);
                        $date = date('M d, Y', strtotime($rev['NgayTao']));
                    ?>
                    <div class="review-card">
                        <div class="review-quote-icon">❝</div>
                        <p class="review-content">"<?= $content ?>"</p>
                        <div class="review-meta">
                            <a href="?page=public_profile&email=<?= $email ?>" class="review-avatar">
                                <!-- lấy ảnh avatar của người dùng -->
                                <img src="get_image.php?email=<?= $email ?>&v=<?= time() ?>" alt="<?= $name ?>">
                            </a>
                            <div class="review-author">
                                <h4><a href="?page=public_profile&email=<?= $email ?>"><?= $name ?></a></h4>
                                <span><?= $date ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <!-- bảng điều khiển review -->
                <div class="carousel-controls">
                    <button id="prev-review" aria-label="Previous review">←</button>
                    <button id="next-review" aria-label="Next review">→</button>
                </div>
            <?php endif; ?>
        </div>
        <!-- Tương tự như file header nếu vai trò người dùng là admin, sẽ ẩn đi chức năng Review, vì admin không nên tự review trang web của chính mình :v  -->
        <?php if (!$isAdmin): ?>
        <div class="leave-review-cta">
            <button class="btn-primary" id="about-leave-review-btn">Leave a Review</button>
        </div>
        <?php endif; ?>
    </section>
</main>

<script src="public/js/about.js"></script>

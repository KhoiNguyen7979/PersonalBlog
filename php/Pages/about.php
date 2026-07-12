<?php
$pageTitle = "About - BloggerZ";

// Fetch reviews
require_once __DIR__ . '/../mySQLconnect.php';
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
    <!-- Hero Section -->
    <section class="about-hero">
        <div class="about-hero-content">
            <h1 class="about-title">Welcome to <span>BloggerZ</span></h1>
            <p class="about-subtitle">A little corner of the internet for thoughts, ideas, and stories.</p>
        </div>
    </section>

    <!-- My Journey / Content Section -->
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
                <img src="public/images/account.jpg" alt="About Me">
            </div>
        </div>
    </section>

    <!-- Reviews Section (Horizontal Carousel) -->
    <section class="about-reviews">
        <div class="reviews-header">
            <h2>What People Say</h2>
            <p>Thoughts and feedback from our amazing readers.</p>
        </div>
        
        <div class="reviews-carousel-wrapper">
            <?php if (empty($reviews)): ?>
                <div class="no-reviews">
                    <p>Chưa có đánh giá nào. Hãy là người đầu tiên để lại đánh giá nhé!</p>
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
                <!-- Controls -->
                <div class="carousel-controls">
                    <button id="prev-review" aria-label="Previous review">←</button>
                    <button id="next-review" aria-label="Next review">→</button>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="leave-review-cta">
            <button class="btn-primary" id="about-leave-review-btn">Leave a Review</button>
        </div>
    </section>
</main>

<script src="public/js/about.js"></script>

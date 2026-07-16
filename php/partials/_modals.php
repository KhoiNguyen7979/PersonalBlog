<!--Pop-up Review (viết đánh giá) + Pop-up Subscribe (đăng ký nhận tin) === -->
<link rel="stylesheet" href="public/css/_modals.css">
<!-- Pop-up review -->
<div id="review-modal" class="modal-overlay">
    <div class="modal-container review-container">
        <button class="modal-close-btn" id="close-review-modal" aria-label="Close modal">&times;</button>
        <div class="modal-card">
            <h2 class="modal-title">Leave a Review</h2>
            <p class="modal-subtitle" style="text-align: center; color: #666; margin-bottom: 20px;">We'd love to hear your thoughts!</p>
            <form id="review-form" class="modal-form">
                <!-- lấy sẵn dữ liệu email và họ tên người dùng trong biến SESSION -->
                <input type="hidden" name="full_name" value="<?= htmlspecialchars($_SESSION['hoten'] ?? '') ?>">
                <input type="hidden" name="email" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>">
                <div class="form-group full-width">
                    <label for="review-message">Your Review <span class="required">*</span></label>
                    <textarea id="review-message" name="message" rows="4" required></textarea>
                </div>
                <button type="submit" class="modal-submit-btn" id="submit-review-btn">Submit Review</button>
            </form>
        </div>
    </div>
</div>
<!-- Pop-up subscribe -->
<div id="subscribe-modal" class="modal-overlay">
    <div class="modal-container subscribe-container">
        <button class="modal-close-btn" id="close-subscribe-modal" aria-label="Close modal">&times;</button>
        <div class="modal-card">
            <h2 class="modal-title">Subscribe</h2>
            <p class="modal-subtitle">The best decision you'll make today.</p>
            <form id="subscribe-form" class="modal-form">
                <div class="form-group full-width">
                    <label for="subscribe-email">Email <span class="required">*</span></label>
                    <input type="email" id="subscribe-email" name="email" required>
                </div>
                <div class="form-checkbox-group">
                    <input type="checkbox" id="subscribe-confirm" name="subscribe_confirm" required>
                    <label for="subscribe-confirm">Yes, Subscribe me to your newsletter. <span class="required">*</span></label>
                </div>
                <button type="submit" class="modal-submit-btn">Submit</button>
            </form>
        </div>
    </div>
</div>


<script src="public/js/_modals.js"></script>

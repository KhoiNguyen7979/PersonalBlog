
<link rel="stylesheet" href="public/css/_modals.css">


<div id="contact-modal" class="modal-overlay">
    <div class="modal-container contact-container">
        <button class="modal-close-btn" id="close-contact-modal" aria-label="Close modal">&times;</button>
        <div class="modal-card">
            <h2 class="modal-title">Contact</h2>
            <form id="contact-form" class="modal-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="contact-first-name">First name <span class="required">*</span></label>
                        <input type="text" id="contact-first-name" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="contact-last-name">Last name <span class="required">*</span></label>
                        <input type="text" id="contact-last-name" name="last_name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="contact-email">Email <span class="required">*</span></label>
                        <input type="email" id="contact-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="contact-subject">Subject</label>
                        <input type="text" id="contact-subject" name="subject">
                    </div>
                </div>
                <div class="form-group full-width">
                    <label for="contact-message">Leave me a message...</label>
                    <textarea id="contact-message" name="message" rows="3"></textarea>
                </div>
                <button type="submit" class="modal-submit-btn">Submit</button>
            </form>
        </div>
    </div>
</div>


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

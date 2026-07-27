document.addEventListener('DOMContentLoaded', () => {
// === _modals.js - Quản lý modal Review và Subscribe ===
// Mở/đóng modal, gửi form review qua fetch, form subscribe
   
    const reviewModal = document.getElementById('review-modal');
    const subscribeModal = document.getElementById('subscribe-modal');
    
    const reviewNavBtn = document.getElementById('contact-nav-btn');
    const reviewFooterBtn = document.getElementById('contact-footer-btn');
    const subscribeBtn = document.getElementById('subscribe');
    const footerSubscribeBtn = document.getElementById('footer-subscribe-btn');
    
    const closeReviewBtn = document.getElementById('close-review-modal');
    const closeSubscribeBtn = document.getElementById('close-subscribe-modal');
    
    const reviewForm = document.getElementById('review-form');
    const subscribeForm = document.getElementById('subscribe-form');

  
    const openModal = (modal) => {
        if (!modal) return;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden'; 
    };

    const closeModal = (modal) => {
        if (!modal) return;
        modal.classList.remove('active');
     
        if (!document.querySelector('.modal-overlay.active')) {
            document.body.style.overflow = '';
        }
    };

    //------------Event Listeners------------
    
    // Mở pop-up review khi click vào ô review (header + footer)
    if (reviewNavBtn) {
        reviewNavBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openModal(reviewModal);
        });
    }
    
    if (reviewFooterBtn) {
        reviewFooterBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openModal(reviewModal);
        });
    }

    // Mở pop-up subscribe khi click vào ô subscribe (header + footer)
    if (subscribeBtn) {
        subscribeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openModal(subscribeModal);
        });
    }
    if (footerSubscribeBtn) {
        footerSubscribeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openModal(subscribeModal);
        });
    }

    // Đóng pop-up subscribe, review khi click vào dấu X
    if (closeReviewBtn) {
        closeReviewBtn.addEventListener('click', () => {
            closeModal(reviewModal);
        });
    }

    if (closeSubscribeBtn) {
        closeSubscribeBtn.addEventListener('click', () => {
            closeModal(subscribeModal);
        });
    }

    // Đóng pop-up subscribe, review khi click vào content ngoài pop-up
    window.addEventListener('click', (e) => {
        if (e.target === reviewModal) {
            closeModal(reviewModal);
        }
        if (e.target === subscribeModal) {
            closeModal(subscribeModal);
        }
    });

    // Đóng pop-up subscribe, review khi nhấn nút ESC trên bàn phím
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal(reviewModal);
            closeModal(subscribeModal);
        }
    });

    // --- Nộp dữ liệu form ---
    // Form review
    if (reviewForm) {
        reviewForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submit-review-btn');
            const originalText = submitBtn.innerText;
            submitBtn.innerText = 'Sending...';
            submitBtn.disabled = true;

            const formData = new FormData(reviewForm);

            // --- BẮT ĐẦU ĐOẠN XHR THAY THẾ FETCH ---
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'api/submit_review.php', true);

            // Xử lý khi nhận được phản hồi từ server (tương đương .then)
            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        
                        if (data.success) {
                            reviewForm.reset();
                            closeModal(reviewModal);
                            if (window.showToast) {
                                showToast('Your review has been posted. Thank you!');
                            }
                            if (window.location.search.includes('page=about')) {
                                setTimeout(() => window.location.reload(), 3000);
                            }
                        } else {
                            if (window.showToast) {
                                showToast(data.message, 'error');
                            }
                        }
                    } catch (err) {
                        console.error('Lỗi parse JSON:', err);
                        if (window.showToast) {
                            showToast('An error occurred. Please try again later.', 'error');
                        }
                    }
                } else {
                    if (window.showToast) {
                        showToast('An error occurred. Please try again later.', 'error');
                    }
                }
            };

            // Xử lý khi có lỗi mạng (tương đương .catch)
            xhr.onerror = function() {
                if (window.showToast) {
                    showToast('An error occurred. Please try again later.', 'error');
                }
            };

            // Luôn chạy sau khi request kết thúc dù thành công hay thất bại (tương đương .finally)
            xhr.onloadend = function() {
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
            };

            // Gửi dữ liệu form đi
            xhr.send(formData);
            // --- KẾT THÚC ĐOẠN XHR ---
        });
    }
    // Form subscribe (để đó không nộp dữ liệu form:/)
    if (subscribeForm) {
        subscribeForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const email = document.getElementById('subscribe-email').value;
            
            if (window.showToast) {
                showToast('Thank you! Your Email has been subscribed.');
            }
            
            subscribeForm.reset();
            closeModal(subscribeModal);
        });
    }
});

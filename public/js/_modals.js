document.addEventListener('DOMContentLoaded', () => {
   
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
    
    // Open Review Modal
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

    // Open Subscribe Modal
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

    // Close Review Modal
    if (closeReviewBtn) {
        closeReviewBtn.addEventListener('click', () => {
            closeModal(reviewModal);
        });
    }

    // Close Subscribe Modal
    if (closeSubscribeBtn) {
        closeSubscribeBtn.addEventListener('click', () => {
            closeModal(subscribeModal);
        });
    }

    // Close on clicking outside modal content
    window.addEventListener('click', (e) => {
        if (e.target === reviewModal) {
            closeModal(reviewModal);
        }
        if (e.target === subscribeModal) {
            closeModal(subscribeModal);
        }
    });

    // Close on pressing Escape key
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal(reviewModal);
            closeModal(subscribeModal);
        }
    });

    // --- Submit form ---
    
    if (reviewForm) {
        reviewForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submit-review-btn');
            const originalText = submitBtn.innerText;
            submitBtn.innerText = 'Đang gửi...';
            submitBtn.disabled = true;

            const formData = new FormData(reviewForm);

            fetch('api/submit_review.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Cảm ơn bạn đã để lại đánh giá! Đánh giá của bạn sẽ hiển thị ở trang About.');
                    reviewForm.reset();
                    closeModal(reviewModal);
                    // Reload trang nếu đang ở trang about
                    if (window.location.search.includes('page=about')) {
                        window.location.reload();
                    }
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(err => {
                alert('Đã có lỗi xảy ra. Vui lòng thử lại sau.');
            })
            .finally(() => {
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    if (subscribeForm) {
        subscribeForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const email = document.getElementById('subscribe-email').value;
            
            alert(`Cảm ơn bạn! Email "${email}" đã được đăng ký nhận bản tin thành công.`);
            
            subscribeForm.reset();
            closeModal(subscribeModal);
        });
    }
});

document.addEventListener('DOMContentLoaded', () => {
   
    const contactModal = document.getElementById('contact-modal');
    const subscribeModal = document.getElementById('subscribe-modal');
    
    const contactNavBtn = document.getElementById('contact-nav-btn');
    const contactFooterBtn = document.getElementById('contact-footer-btn');
    const subscribeBtn = document.getElementById('subscribe');
    
    const closeContactBtn = document.getElementById('close-contact-modal');
    const closeSubscribeBtn = document.getElementById('close-subscribe-modal');
    
    const contactForm = document.getElementById('contact-form');
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
    
    // Open Contact Modal
    if (contactNavBtn) {
        contactNavBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openModal(contactModal);
        });
    }
    
    if (contactFooterBtn) {
        contactFooterBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openModal(contactModal);
        });
    }

    // Open Subscribe Modal
    if (subscribeBtn) {
        subscribeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openModal(subscribeModal);
        });
    }

    // Close Contact Modal
    if (closeContactBtn) {
        closeContactBtn.addEventListener('click', () => {
            closeModal(contactModal);
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
        if (e.target === contactModal) {
            closeModal(contactModal);
        }
        if (e.target === subscribeModal) {
            closeModal(subscribeModal);
        }
    });

    // Close on pressing Escape key
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal(contactModal);
            closeModal(subscribeModal);
        }
    });

    // --- Submitt form ---
    
    if (contactForm) {
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Get form values
            const firstName = document.getElementById('contact-first-name').value;
            const lastName = document.getElementById('contact-last-name').value;
            
            // Show premium success feedback
            alert(`Cảm ơn ${firstName} ${lastName}! Tin nhắn của bạn đã được gửi thành công. Chúng tôi sẽ liên hệ lại sớm nhất có thể.`);
            
            // Reset and close
            contactForm.reset();
            closeModal(contactModal);
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

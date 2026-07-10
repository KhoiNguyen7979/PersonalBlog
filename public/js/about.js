/**
 * about.js - Xử lý hiệu ứng scroll ngang cho Review Carousel
 * và liên kết nút Leave a Review với Modal
 */
document.addEventListener('DOMContentLoaded', () => {
    // 1. Liên kết nút Leave Review trong trang About với modal
    const aboutReviewBtn = document.getElementById('about-leave-review-btn');
    const reviewModal = document.getElementById('review-modal');
    
    if (aboutReviewBtn && reviewModal) {
        aboutReviewBtn.addEventListener('click', (e) => {
            e.preventDefault();
            reviewModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

    // 2. Xử lý Carousel (thanh trượt review)
    const track = document.getElementById('reviews-track');
    const btnPrev = document.getElementById('prev-review');
    const btnNext = document.getElementById('next-review');

    if (track && btnPrev && btnNext) {
        // Khoảng cách cuộn mỗi lần bấm = chiều rộng của 1 card + khoảng cách (gap)
        // 350px width + 30px gap = 380px (theo thiết kế ở Desktop)
        // Dùng scrollBy để tương thích tốt với scroll-snap
        const scrollAmount = 380;

        btnNext.addEventListener('click', () => {
            track.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });

        btnPrev.addEventListener('click', () => {
            track.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        });

        // Tuỳ chọn: Kéo thả để cuộn (Mouse Drag)
        let isDown = false;
        let startX;
        let scrollLeft;

        track.addEventListener('mousedown', (e) => {
            isDown = true;
            track.style.cursor = 'grabbing';
            track.style.scrollSnapType = 'none'; // Tạm tắt snap để kéo mượt
            startX = e.pageX - track.offsetLeft;
            scrollLeft = track.scrollLeft;
        });

        track.addEventListener('mouseleave', () => {
            isDown = false;
            track.style.cursor = 'grab';
            track.style.scrollSnapType = 'x mandatory';
        });

        track.addEventListener('mouseup', () => {
            isDown = false;
            track.style.cursor = 'grab';
            track.style.scrollSnapType = 'x mandatory';
        });

        track.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - track.offsetLeft;
            const walk = (x - startX) * 2; // Tốc độ kéo
            track.scrollLeft = scrollLeft - walk;
        });
    }
});

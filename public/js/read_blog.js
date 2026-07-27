(function() {
    const track = document.getElementById('carousel-track');
    if (!track) return;
    const slides = track.querySelectorAll('.carousel-slide');
    const dots = document.querySelectorAll('#carousel-dots .carousel-dot');
    const prevBtn = document.getElementById('carousel-prev');
    const nextBtn = document.getElementById('carousel-next');
    let current = 0;
    let transitioning = false;

    function goTo(idx) {
        if (transitioning) return;
        if (idx < 0) idx = slides.length - 1;
        if (idx >= slides.length) idx = 0;
        if (idx === current) return;
        transitioning = true;

        var nextSlide = slides[idx];
        nextSlide.style.opacity = '0';
        nextSlide.classList.add('active');
        nextSlide.offsetHeight;
        nextSlide.style.opacity = '1';

        setTimeout(function() {
            slides[current].classList.remove('active');
            slides[current].style.opacity = '';
            current = idx;
            transitioning = false;
        }, 500);

        dots.forEach(function(d, i) {
            d.classList.toggle('active', i === idx);
        });
    }

    if (prevBtn) prevBtn.addEventListener('click', function(e) { e.preventDefault(); e.stopPropagation(); goTo(current - 1); });
    if (nextBtn) nextBtn.addEventListener('click', function(e) { e.preventDefault(); e.stopPropagation(); goTo(current + 1); });
    dots.forEach(function(d) { d.addEventListener('click', function(e) { e.preventDefault(); e.stopPropagation(); goTo(parseInt(d.dataset.idx)); }); });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') goTo(current - 1);
        if (e.key === 'ArrowRight') goTo(current + 1);
    });
})();

// Menu 3 chấm: Chỉnh sửa hoặc xóa bài viết
document.querySelectorAll('.read-blog-container .post-menu-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const menu = this.closest('.post-menu');
        document.querySelectorAll('.post-menu').forEach(m => {
            if (m !== menu) {
                m.classList.remove('open');
                const dd = m.querySelector('.post-menu-dropdown');
                if (dd) dd.style.display = 'none';
            }
        });
        const dd = menu.querySelector('.post-menu-dropdown');
        if (!dd) return;
        if (menu.classList.contains('open')) {
            menu.classList.remove('open');
            dd.style.display = 'none';
        } else {
            menu.classList.add('open');
            dd.style.display = 'block';
        }
    });
});
document.addEventListener('click', function(e) {
    if (!e.target.closest('.post-menu')) {
        document.querySelectorAll('.post-menu').forEach(m => {
            m.classList.remove('open');
            const dd = m.querySelector('.post-menu-dropdown');
            if (dd) dd.style.display = 'none';
        });
    }
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.post-menu').forEach(m => {
            m.classList.remove('open');
            const dd = m.querySelector('.post-menu-dropdown');
            if (dd) dd.style.display = 'none';
        });
    }
});

//Xóa bài viết: gọi API xóa, chuyển về trang blog
document.querySelectorAll('.delete-post-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (confirm('Are you sure you want to delete this post?')) {
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', `api/delete_post.php?id=${btn.dataset.id}`, true);

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            var overlay = document.getElementById('page-transition');
                            if (overlay) overlay.classList.add('active');
                            window.location.href = '?page=blog';
                        } else {
                            if (window.showToast) {
                                showToast('Failed to delete post.', 'error');
                            } else {
                                alert('Failed to delete post.');
                            }
                        }
                    } catch (err) {
                        console.error('Lỗi parse JSON:', err);
                    }
                } else {
                    if (window.showToast) {
                        showToast('Failed to delete post.', 'error');
                    } else {
                        alert('Failed to delete post.');
                    }
                }
            };

            xhr.onerror = function() {
                if (window.showToast) {
                    showToast('Failed to delete post.', 'error');
                } else {
                    alert('Failed to delete post.');
                }
            };

            xhr.send();
        }
    });
});

//Nút Like: gọi API like/unlike, cập nhật số like và giao diện ---
document.getElementById('like-btn').addEventListener('click', function() {
    const btn = this;
    const postId = btn.dataset.id;
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', `api/like_post.php?id=${postId}`, true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            try {
                const data = JSON.parse(xhr.responseText);
                if (data.success) {
                    document.getElementById('like-count').innerText = data.likes;
                    if (data.status === 'liked') {
                        btn.classList.add('liked');
                    } else {
                        btn.classList.remove('liked');
                    }
                } else {
                    if (window.showToast) {
                        showToast(data.message, 'error');
                    } else {
                        alert(data.message);
                    }
                }
            } catch (err) {
                console.error('Lỗi parse JSON:', err);
            }
        } else {
            if (window.showToast) {
                showToast('Server connection error.', 'error');
            } else {
                alert('Server connection error.');
            }
        }
    };

    xhr.onerror = function() {
        if (window.showToast) {
            showToast('Network error.', 'error');
        } else {
            alert('Network error.');
        }
    };

    xhr.send();
});
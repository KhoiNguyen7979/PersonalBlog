// === index.js - JS toàn cục (chạy trên mọi trang) ===
// --- Toast notification: đọc ?toast= từ URL và hiển thị thông báo ---
(function() {
    var toast = document.getElementById('global-toast');
    if (!toast) return;
    var params = new URLSearchParams(window.location.search);
    var msg = params.get('toast');
    var type = params.get('toast_type') || 'success';
    if (msg) {
        toast.textContent = msg;
        toast.className = 'toast-notification ' + type;
        setTimeout(function() { toast.classList.add('show'); }, 100);
        setTimeout(function() { toast.classList.remove('show'); }, 2500);
        params.delete('toast');
        params.delete('toast_type');
        var newUrl = params.toString() ? '?' + params.toString() : window.location.pathname;
        history.replaceState(null, '', newUrl);
    }
    window.showToast = function(message, type, duration) {
        toast.textContent = message;
        toast.className = 'toast-notification ' + (type || 'success');
        setTimeout(function() { toast.classList.add('show'); }, 100);
        setTimeout(function() { toast.classList.remove('show'); }, duration || 2500);
    };
})();

// --- Page transition: chặn click link nội bộ, hiển thị overlay fade rồi chuyển trang ---
(function() {
    var overlay = document.getElementById('page-transition');
    if (!overlay) return;
    overlay.classList.remove('active');

    document.addEventListener('click', function(e) {
        var link = e.target.closest('a[href]');
        if (!link) return;

        var href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('http') || link.target === '_blank') return;
        if (link.classList.contains('post-menu-btn') || link.classList.contains('carousel-btn') || link.classList.contains('carousel-dot')) return;
        if (link.closest('.modal-overlay') || link.closest('.pagination-wrap') || link.closest('.read-more-btn')) return;

        e.preventDefault();
        overlay.classList.add('active');

        setTimeout(function() {
            window.location.href = href;
        }, 300);
    });

    document.body.classList.remove('loading');
})();

// --- Password toggle: hiển thị/ẩn mật khẩu khi bấm nút con mắt ---
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.toggle-pw');
    if (!btn) return;
    var wrap = btn.closest('.password-wrap');
    if (!wrap) return;
    var input = wrap.querySelector('input');
    if (!input) return;

    var isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';

    var eyeOpen = btn.querySelector('.eye-open');
    var eyeClosed = btn.querySelector('.eye-closed');
    if (eyeOpen && eyeClosed) {
        eyeOpen.style.display = isPassword ? 'none' : '';
        eyeClosed.style.display = isPassword ? '' : 'none';
    }
});

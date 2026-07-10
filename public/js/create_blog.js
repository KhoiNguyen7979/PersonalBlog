/**
 * public/js/create_blog.js
 * Xử lý giao diện tạo bài viết: preview ảnh bìa và submit form.
 */
document.addEventListener('DOMContentLoaded', () => {

    const form        = document.getElementById('create-blog-form');
    const fileInput   = document.getElementById('thumbnail');
    const previewImg  = document.getElementById('thumbnail-preview');
    const placeholder = document.getElementById('upload-placeholder');
    const publishBtn  = document.getElementById('publish-btn');
    const toast       = document.getElementById('create-toast');

    // ── 1. Preview ảnh ────────────────────────────────────────────────────────
    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            previewImg.style.display = 'none';
            previewImg.src = '';
            placeholder.style.display = 'block';
        }
    });

    // ── 2. Submit form ────────────────────────────────────────────────────────
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        if (!fileInput.files[0]) {
            showToast('❌ Vui lòng chọn ảnh bìa (Thumbnail) cho bài viết.');
            return;
        }

        const formData = new FormData();
        formData.append('title',     document.getElementById('title').value);
        formData.append('summary',   document.getElementById('summary').value);
        formData.append('category',  document.getElementById('category').value);
        formData.append('content',   document.getElementById('content').value);
        formData.append('thumbnail', fileInput.files[0]);

        const originalText = publishBtn.innerText;
        publishBtn.innerText = 'Đang đăng...';
        publishBtn.classList.add('loading');
        publishBtn.disabled = true;

        fetch('api/create_post.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('✅ Đăng bài thành công! Đang chuyển hướng...');
                setTimeout(() => {
                    window.location.href = 'index.php?page=blog';
                }, 1500);
            } else {
                showToast('❌ Lỗi: ' + data.message);
                resetButton();
            }
        })
        .catch(err => {
            console.error(err);
            showToast('❌ Đã xảy ra lỗi kết nối với máy chủ.');
            resetButton();
        });

        function resetButton() {
            publishBtn.innerText = originalText;
            publishBtn.classList.remove('loading');
            publishBtn.disabled = false;
        }
    });

    // ── 3. Toast ──────────────────────────────────────────────────────────────
    function showToast(message) {
        toast.textContent = message;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3000);
    }

});

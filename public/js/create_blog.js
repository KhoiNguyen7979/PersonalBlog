/**
 * public/js/create_blog.js
 * Xử lý giao diện tạo bài viết: preview ảnh bìa, ảnh bổ sung, và submit form.
 */
document.addEventListener('DOMContentLoaded', () => {

    const form          = document.getElementById('create-blog-form');
    const fileInput     = document.getElementById('thumbnail');
    const previewImg    = document.getElementById('thumbnail-preview');
    const placeholder   = document.getElementById('upload-placeholder');
    const publishBtn    = document.getElementById('publish-btn');
    const toast         = document.getElementById('create-toast');
    const extraInput    = document.getElementById('extra-images');
    const extraPreview  = document.getElementById('extra-preview-list');

    // ── 1. Preview ảnh bìa ───────────────────────────────────────────────────
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

    // ── 2. Preview ảnh bổ sung ────────────────────────────────────────────────
    if (extraInput) {
        extraInput.addEventListener('change', () => {
            extraPreview.innerHTML = '';
            Array.from(extraInput.files).forEach((file, i) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'extra-preview-item';
                    div.innerHTML = `<img src="${e.target.result}" alt="Ảnh ${i+1}"><button type="button" class="extra-remove-btn" data-idx="${i}">&times;</button>`;
                    extraPreview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        });

        extraPreview.addEventListener('click', (e) => {
            if (e.target.classList.contains('extra-remove-btn')) {
                const idx = parseInt(e.target.dataset.idx);
                const dt = new DataTransfer();
                Array.from(extraInput.files).forEach((f, i) => {
                    if (i !== idx) dt.items.add(f);
                });
                extraInput.files = dt.files;
                extraInput.dispatchEvent(new Event('change'));
            }
        });
    }

    // ── 3. Submit form ────────────────────────────────────────────────────────
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        if (!fileInput.files[0]) {
            showToast('Vui lòng chọn ảnh bìa (Thumbnail) cho bài viết.');
            return;
        }

        const formData = new FormData();
        formData.append('title',     document.getElementById('title').value);
        formData.append('summary',   document.getElementById('summary').value);
        formData.append('category',  document.getElementById('category').value);
        formData.append('content',   document.getElementById('content').value);
        formData.append('images',    fileInput.files[0]);

        if (extraInput && extraInput.files.length > 0) {
            Array.from(extraInput.files).forEach(f => {
                formData.append('images', f);
            });
        }

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
                showToast('Đăng bài thành công! Đang chuyển hướng...');
                var overlay = document.getElementById('page-transition');
                if (overlay) overlay.classList.add('active');
                setTimeout(() => {
                    window.location.href = 'index.php?page=blog';
                }, 1500);
            } else {
                showToast('Lỗi: ' + data.message);
                resetButton();
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Đã xảy ra lỗi kết nối với máy chủ.');
            resetButton();
        });

        function resetButton() {
            publishBtn.innerText = originalText;
            publishBtn.classList.remove('loading');
            publishBtn.disabled = false;
        }
    });

    // ── 4. Toast ──────────────────────────────────────────────────────────────
    function showToast(message) {
        toast.textContent = message;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3000);
    }

});

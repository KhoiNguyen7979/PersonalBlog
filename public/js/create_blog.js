document.addEventListener('DOMContentLoaded', () => {

    const form          = document.getElementById('create-blog-form');
    const fileInput     = document.getElementById('images-input');
    const previewImg    = document.getElementById('thumbnail-preview');
    const placeholder   = document.getElementById('upload-placeholder');
    const publishBtn    = document.getElementById('publish-btn');
    const toast         = document.getElementById('create-toast');

    let cropper = null;
    let currentFile = null;

    const cropModal = document.getElementById('crop-modal');
    const cropImage = document.getElementById('crop-image');
    const cropClose = document.getElementById('crop-close');
    const cropCancel = document.getElementById('crop-cancel');
    const cropConfirm = document.getElementById('crop-confirm');

    function openCrop(file) {
        currentFile = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            cropImage.src = e.target.result;
            cropImage.onload = () => {
                cropModal.classList.add('active');
                document.body.style.overflow = 'hidden';
                if (cropper) cropper.destroy();
                const maxDim = Math.max(cropImage.naturalWidth, cropImage.naturalHeight);
                cropper = new Cropper(cropImage, {
                    aspectRatio: NaN,
                    viewMode: 1,
                    autoCropArea: 1,
                    responsive: true,
                    minCanvasWidth: maxDim,
                    minCanvasHeight: maxDim,
                });
            };
        };
        reader.readAsDataURL(file);
    }

    function closeCrop() {
        cropModal.classList.remove('active');
        document.body.style.overflow = '';
        if (cropper) { cropper.destroy(); cropper = null; }
        currentFile = null;
    }

    cropClose.addEventListener('click', closeCrop);
    cropCancel.addEventListener('click', closeCrop);
    cropModal.addEventListener('click', (e) => {
        if (e.target === cropModal) closeCrop();
    });

    cropConfirm.addEventListener('click', () => {
        if (!cropper || !currentFile) return;
        const canvas = cropper.getCroppedCanvas();
        if (!canvas) return;

        canvas.toBlob((blob) => {
            const newFile = new File([blob], currentFile.name, { type: currentFile.type });
            const dt = new DataTransfer();
            dt.items.add(newFile);
            fileInput.files = dt.files;
            showPreview(newFile);
            closeCrop();
        });
    });

    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        if (file) {
            openCrop(file);
        } else {
            previewImg.style.display = 'none';
            previewImg.src = '';
            placeholder.style.display = 'block';
        }
    });

    function showPreview(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        if (!fileInput.files[0]) {
            showToast('Vui lòng chọn ảnh cho bài viết.');
            return;
        }

        const formData = new FormData();
        formData.append('title',     document.getElementById('title').value);
        formData.append('summary',   document.getElementById('summary').value);
        formData.append('category',  document.getElementById('category').value);
        formData.append('content',   document.getElementById('content').value);
        formData.append('images',    fileInput.files[0]);

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
                }, 800);
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

    function showToast(message) {
        toast.textContent = message;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3000);
    }

});

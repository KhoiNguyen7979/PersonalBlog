document.addEventListener('DOMContentLoaded', () => {
// === create_blog.js - Trang tạo blog mới ===
// 1. Chọn ảnh → mở CropperJS (Cố định tỷ lệ chữ nhật, có scroll để zoom)
// 2. Submit form qua fetch API → tạo bài viết + lưu ảnh vào DB
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
                
                cropper = new Cropper(cropImage, {
                    aspectRatio: NaN, 
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 1,           // Đặt là 1 để mặc định khung cắt sẽ chọn toàn bộ ảnh
                    responsive: true,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
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
        
        const canvas = cropper.getCroppedCanvas({
            maxWidth: 1920,
            maxHeight: 1920,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        }); 
        
        if (!canvas) return; 

        canvas.toBlob((blob) => {
            const originalName = currentFile.name;
            const newFileName = originalName.substring(0, originalName.lastIndexOf('.')) + '.webp';
            
            const newFile = new File([blob], newFileName, { type: 'image/webp' });
            
            const dt = new DataTransfer(); 
            dt.items.add(newFile); 
            fileInput.files = dt.files; 
            
            showPreview(newFile); 
            closeCrop(); 
        }, 'image/webp', 0.8);
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

    const form = document.getElementById('create-blog-form');
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        if (!fileInput.files[0]) {
            showToast('Please select an image for your post.');
            return;
        }

        const formData = new FormData();
        formData.append('title',     document.getElementById('title').value);
        formData.append('summary',   document.getElementById('summary').value);
        formData.append('category',  document.getElementById('category').value);
        formData.append('content',   document.getElementById('content').value);
        formData.append('images',    fileInput.files[0]);

        const originalText = publishBtn.innerText;
        publishBtn.innerText = 'Publishing...';
        publishBtn.classList.add('loading');
        publishBtn.disabled = true;


        const xhr = new XMLHttpRequest();
        
        // Khởi tạo request POST tới API
        xhr.open('POST', 'api/create_post.php', true);

        // Xử lý khi nhận được phản hồi từ server
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                   
                    const data = JSON.parse(xhr.responseText); 
                    
                    if (data.success) {
                        showToast('Published successfully! Redirecting...');
                        setTimeout(() => {
                            var overlay = document.getElementById('page-transition');
                            if (overlay) overlay.classList.add('active');
                            setTimeout(() => {
                                window.location.href = 'index.php?page=blog';
                            }, 400);
                        }, 1000);
                    } else {
                        showToast('Lỗi: ' + data.message);
                        resetButton();
                    }
                } catch (e) {
                    console.error("Lỗi parse JSON:", e);
                    showToast('Lỗi dữ liệu phản hồi từ server.');
                    resetButton();
                }
            } else {
                console.error("Lỗi HTTP:", xhr.status);
                showToast('Lỗi kết nối máy chủ.');
                resetButton();
            }
        };

        // Xử lý khi có lỗi mạng
        xhr.onerror = function() {
            console.error("Lỗi mạng (Network Error)");
            showToast('Server connection error.');
            resetButton();
        };

        // Gửi dữ liệu đi
        xhr.send(formData);

        function resetButton() {
            publishBtn.innerText = originalText;
            publishBtn.classList.remove('loading');
            publishBtn.disabled = false;
        }
    });

    function showToast(message) {
        toast.textContent = message;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 5000);
    }

});
document.addEventListener('DOMContentLoaded', function() {
    const newImageInput = document.getElementById('edit-new-image');
    const newPreview = document.getElementById('edit-new-preview');
    const uploadPlaceholder = document.getElementById('edit-upload-placeholder');

    let cropper = null;
    let croppedFile = null;

    const cropModal = document.getElementById('crop-modal');
    const cropImage = document.getElementById('crop-image');
    const cropClose = document.getElementById('crop-close');
    const cropCancel = document.getElementById('crop-cancel');
    const cropConfirm = document.getElementById('crop-confirm');

    function openCrop(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            cropImage.src = e.target.result;
            cropImage.onload = function() {
                cropModal.classList.add('active');
                document.body.style.overflow = 'hidden';
                
                if (cropper) cropper.destroy();
                
                // Đã cập nhật cấu hình CropperJS giống create_blog
                cropper = new Cropper(cropImage, {
                    aspectRatio: NaN,
                    viewMode: 1,
                    dragMode: 'move', // Cho phép cuộn chuột để zoom và kéo ảnh
                    autoCropArea: 1,  // Tự động bao trọn toàn bộ ảnh khi vừa mở lên
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
    }

    cropClose.addEventListener('click', closeCrop);
    cropCancel.addEventListener('click', closeCrop);
    cropModal.addEventListener('click', function(e) {
        if (e.target === cropModal) closeCrop();
    });

    cropConfirm.addEventListener('click', function() {
        if (!cropper) return;
        
        // Cập nhật xuất ảnh độ phân giải cao
        var canvas = cropper.getCroppedCanvas({
            maxWidth: 1920,
            maxHeight: 1920,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });
        
        if (!canvas) return;

        canvas.toBlob(function(blob) {
            // Đổi tên đuôi file thành .webp cho khớp định dạng mới
            var originalName = newImageInput.files[0].name;
            var newFileName = originalName.substring(0, originalName.lastIndexOf('.')) + '.webp';
            
            // Ghi đè file với Blob đã nén
            croppedFile = new File([blob], newFileName, { type: 'image/webp' });
            
            var reader = new FileReader();
            reader.onload = function(e) {
                newPreview.src = e.target.result;
                newPreview.style.display = 'block';
                uploadPlaceholder.style.display = 'none';
            };
            reader.readAsDataURL(blob);
            closeCrop();
        }, 'image/webp', 0.8); // Mức chất lượng nén (0.8 để ảnh được nét)
    });

    // Chọn ảnh mới với crop
    newImageInput.addEventListener('change', function() {
        var file = this.files[0];
        if (file) openCrop(file);
    });

    // Nộp form
    document.getElementById('edit-blog-form').addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var btn = document.getElementById('update-btn');
        btn.innerText = 'Updating...';
        btn.disabled = true;

        if (croppedFile) {
            formData.set('new_image', croppedFile);
        }

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'api/update_post.php', true);

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    const data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        var overlay = document.getElementById('page-transition');
                        if (window.showToast) {
                            showToast('Updated successfully!');
                            if (overlay) overlay.classList.add('active');
                            setTimeout(function() { window.location.href = '?page=blog'; }, 800);
                        } else {
                            if (overlay) overlay.classList.add('active');
                            window.location.href = '?page=blog&toast=' + encodeURIComponent('Updated successfully!');
                        }
                    } else {
                        if (window.showToast) {
                            showToast('Error: ' + data.message, 'error');
                        } else {
                            alert('Error: ' + data.message);
                        }
                        btn.innerText = 'Update';
                        btn.disabled = false;
                    }
                } catch (err) {
                    console.error('Lỗi parse JSON:', err);
                    if (window.showToast) {
                        showToast('Data parsing error', 'error');
                    } else {
                        alert('Data parsing error');
                    }
                    btn.innerText = 'Update';
                    btn.disabled = false;
                }
            } else {
                if (window.showToast) {
                    showToast('Server connection error.', 'error');
                } else {
                    alert('Server connection error.');
                }
                btn.innerText = 'Update';
                btn.disabled = false;
            }
        };

        xhr.onerror = function() {
            if (window.showToast) {
                showToast('Network error.', 'error');
            } else {
                alert('Network error.');
            }
            btn.innerText = 'Update';
            btn.disabled = false;
        };

        xhr.send(formData);
    });
});
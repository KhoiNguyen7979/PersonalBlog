/**
 * profile.js - Trang hồ sơ cá nhân
 * 1. Đổi avatar (chụp ảnh → crop 1:1 → upload base64 lên server)
 * 2. Chỉnh sửa bio/mô tả (hiện/ẩn textarea, gửi qua fetch)
 * 3. Chỉnh sửa thông tin cá nhân (tên, username, mật khẩu)
 */
// Chỉnh sửa ảnh Avatar
document.addEventListener('DOMContentLoaded', () => {

    // ── Avatar Crop ───────────────────────────────────────────────────────────
    const avatarInput   = document.getElementById('avatar-upload');
    const avatarImg     = document.getElementById('profile-avatar');
    const cropModal     = document.getElementById('avatar-crop-modal');
    const cropImgEl     = document.getElementById('av-crop-img');
    const cropConfirm   = document.getElementById('av-crop-confirm');
    const cropCancel    = document.getElementById('av-crop-cancel');
    const cropClose     = document.getElementById('av-crop-close');

    let cropper = null;

    if (avatarInput) {
        avatarInput.addEventListener('change', () => {
            const file = avatarInput.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => openCropModal(e.target.result);
            reader.readAsDataURL(file);
            // Reset để có thể chọn lại cùng file
            avatarInput.value = '';
        });
    }

    function openCropModal(src) {
        cropImgEl.src = src;
        cropModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        if (cropper) { cropper.destroy(); cropper = null; }

        cropper = new Cropper(cropImgEl, {
            aspectRatio: 1,          // hình vuông → hiển thị tròn qua CSS
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 0.85,
            responsive: true,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
        });
    }

    function closeCropModal() {
        cropModal.style.display = 'none';
        document.body.style.overflow = '';
        if (cropper) { cropper.destroy(); cropper = null; }
    }

    if (cropClose)  cropClose.addEventListener('click', closeCropModal);
    if (cropCancel) cropCancel.addEventListener('click', closeCropModal);
    if (cropModal)  cropModal.addEventListener('click', (e) => { if (e.target === cropModal) closeCropModal(); });

    if (cropConfirm) {
        cropConfirm.addEventListener('click', () => {
            if (!cropper) return;

            cropConfirm.textContent = 'Processing...';
            cropConfirm.disabled = true;

            cropper.getCroppedCanvas({
                width: 400,
                height: 400,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            }).toBlob((blob) => {
                // Preview ngay
                avatarImg.src = URL.createObjectURL(blob);

                // Upload lên server
                const formData = new FormData();
                formData.append('action', 'update_avatar');
                formData.append('avatar', blob, 'avatar.jpg');

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'api/update_profile.php', true);

                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        try {
                            const data = JSON.parse(xhr.responseText);
                            showToast(data.success ? '✅ Avatar updated!' : '❌ ' + data.message);
                        } catch (e) {
                            console.error('Lỗi parse JSON:', e);
                            showToast('❌ Server data error.');
                        }
                    } else {
                        showToast('❌ Server connection error.');
                    }
                    
                    cropConfirm.textContent = 'Confirm';
                    cropConfirm.disabled = false;
                    closeCropModal();
                };

                xhr.onerror = function() {
                    showToast('❌ Server connection error.');
                    cropConfirm.textContent = 'Confirm';
                    cropConfirm.disabled = false;
                    closeCropModal();
                };

                xhr.send(formData);
                
            }, 'image/jpeg', 0.92);
        });
    }

    // Chỉnh sửa Mô tả
    const editBioBtn   = document.getElementById('edit-bio-btn');
    const bioDisplay   = document.getElementById('bio-display');
    const bioEdit      = document.getElementById('bio-edit');
    const bioText      = document.getElementById('bio-text');
    const bioTextarea  = document.getElementById('bio-textarea');
    const saveBioBtn   = document.getElementById('save-bio-btn');
    const cancelBioBtn = document.getElementById('cancel-bio-btn');
    
    //hiện textarea khi click vào nút edit, đồng thời ẩn display mô tả hiện tại 
    if (editBioBtn) {
        editBioBtn.addEventListener('click', (e) => {
            e.preventDefault();
            bioDisplay.style.display = 'none';
            bioEdit.style.display    = 'block';
            bioTextarea.focus();
        });
    }
    
    //đóng textarea khi click vào nút cancel, đồng thời hiện lại display mô tả hiện tại 
    if (cancelBioBtn) {
        cancelBioBtn.addEventListener('click', () => {
            bioDisplay.style.display = 'block';
            bioEdit.style.display    = 'none';
        });
    }
    
    //Nếu bấm nút Save thì sẽ nộp nội dung trong textarea lên CSDL
    if (saveBioBtn) {
        saveBioBtn.addEventListener('click', () => {
            const newBio = bioTextarea.value.trim();

            const formData = new FormData();
            formData.append('action', 'update_bio');
            formData.append('mota', newBio);
            
            // xuất dữ liệu qua file update_profile.php để cập nhật
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'api/update_profile.php', true);

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            bioText.innerHTML = newBio.replace(/\n/g, '<br>') || '<em>No description.</em>';
                            bioDisplay.style.display = 'block';
                            bioEdit.style.display    = 'none';
                            // hiển thị toast đăng ký thành công
                            showToast('✅ Description updated!');
                        } else {
                            showToast('❌ ' + data.message);
                        }
                    } catch (e) {
                        console.error('Lỗi parse JSON:', e);
                        showToast('❌ Server data error.');
                    }
                } else {
                    showToast('❌ Server connection error.');
                }
            };

            xhr.onerror = function() {
                showToast('❌ Server connection error.');
            };

            xhr.send(formData);
        });
    }

    // Chỉnh sửa thông tin cá nhân
    const editInfoBtn     = document.getElementById('edit-info-btn');
    const infoEditSection = document.getElementById('info-edit-section');
    const cancelInfoBtn   = document.getElementById('cancel-info-btn');
    const updateInfoForm  = document.getElementById('update-info-form');
    const updateMsg       = document.getElementById('update-msg');
    
    //hiện form khi click vào nút edit, đồng thời ẩn nút edit
    if (editInfoBtn) {
        editInfoBtn.addEventListener('click', (e) => {
            e.preventDefault();
            infoEditSection.style.display = 'block';
            editInfoBtn.style.display = 'none';
        });
    }
    
    //ẩn form khi click vào nút cancel, đồng thời hiện lại nút edit  
    if (cancelInfoBtn) {
        cancelInfoBtn.addEventListener('click', () => {
            infoEditSection.style.display = 'none';
            editInfoBtn.style.display = 'block';
            updateMsg.innerText = '';
        });
    }
    
    // cập nhật dữ liệu của form lên CSDL
    if (updateInfoForm) {
        updateInfoForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const username        = document.getElementById('edit-username').value.trim();
            const fullname        = document.getElementById('edit-fullname').value.trim();
            const newPassword     = document.getElementById('edit-new-password').value;
            const confirmPassword = document.getElementById('edit-confirm-password').value;

            if (newPassword !== '' && newPassword !== confirmPassword) {
                updateMsg.style.color = 'red';
                updateMsg.innerText = 'Passwords do not match!';
                return;
            }

            const formData = new FormData();
            formData.append('username', username);
            formData.append('fullname', fullname);
            if (newPassword !== '') formData.append('password', newPassword);
            
            // xuất dữ liệu qua file update_info.php
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'api/update_info.php', true);

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.status === 'success') {
                            if (window.showToast) {
                                showToast('Update successful!');
                                setTimeout(() => window.location.reload(), 2000);
                            } else {
                                updateMsg.style.color = 'green';
                                updateMsg.innerText = 'Update successful! Reloading...';
                                setTimeout(() => window.location.reload(), 1500);
                            }
                        } else {
                            updateMsg.style.color = 'red';
                            updateMsg.innerText = data.message || 'An error occurred!';
                        }
                    } catch (e) {
                        console.error('Lỗi parse JSON:', e);
                        updateMsg.style.color = 'red';
                        updateMsg.innerText = 'Server data error!';
                    }
                } else {
                    updateMsg.style.color = 'red';
                    updateMsg.innerText = 'Server connection error!';
                }
            };

            xhr.onerror = function() {
                updateMsg.style.color = 'red';
                updateMsg.innerText = 'Server connection error!';
            };

            xhr.send(formData);
        });
    }

    // hiển thị toast khi cập nhật thông tin, mô tả thành công
    function showToast(message) {
        let toast = document.querySelector('.profile-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.className = 'profile-toast';
            document.body.appendChild(toast);
        }
        toast.textContent = message;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3000);
    }
});
/**
 * public/js/profile.js
 * Xử lý đổi avatar (với crop), sửa mô tả và cập nhật thông tin cá nhân.
 */
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

            cropConfirm.textContent = 'Đang xử lý...';
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

                fetch('api/update_profile.php', { method: 'POST', body: formData })
                    .then(r => r.json())
                    .then(data => {
                        showToast(data.success ? '✅ Đã cập nhật ảnh đại diện!' : '❌ ' + data.message);
                    })
                    .catch(() => showToast('❌ Lỗi kết nối server.'));

                cropConfirm.textContent = 'Xác nhận';
                cropConfirm.disabled = false;
                closeCropModal();
            }, 'image/jpeg', 0.92);
        });
    }

    // ── Edit Bio ──────────────────────────────────────────────────────────────
    const editBioBtn   = document.getElementById('edit-bio-btn');
    const bioDisplay   = document.getElementById('bio-display');
    const bioEdit      = document.getElementById('bio-edit');
    const bioText      = document.getElementById('bio-text');
    const bioTextarea  = document.getElementById('bio-textarea');
    const saveBioBtn   = document.getElementById('save-bio-btn');
    const cancelBioBtn = document.getElementById('cancel-bio-btn');

    if (editBioBtn) {
        editBioBtn.addEventListener('click', (e) => {
            e.preventDefault();
            bioDisplay.style.display = 'none';
            bioEdit.style.display    = 'block';
            bioTextarea.focus();
        });
    }

    if (cancelBioBtn) {
        cancelBioBtn.addEventListener('click', () => {
            bioDisplay.style.display = 'block';
            bioEdit.style.display    = 'none';
        });
    }

    if (saveBioBtn) {
        saveBioBtn.addEventListener('click', () => {
            const newBio = bioTextarea.value.trim();

            const formData = new FormData();
            formData.append('action', 'update_bio');
            formData.append('mota', newBio);

            fetch('api/update_profile.php', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        bioText.innerHTML = newBio.replace(/\n/g, '<br>') || '<em>Chưa có mô tả.</em>';
                        bioDisplay.style.display = 'block';
                        bioEdit.style.display    = 'none';
                        showToast('✅ Đã cập nhật mô tả!');
                    } else {
                        showToast('❌ ' + data.message);
                    }
                })
                .catch(() => showToast('❌ Lỗi kết nối server.'));
        });
    }

    // ── Edit Info ─────────────────────────────────────────────────────────────
    const editInfoBtn     = document.getElementById('edit-info-btn');
    const infoEditSection = document.getElementById('info-edit-section');
    const cancelInfoBtn   = document.getElementById('cancel-info-btn');
    const updateInfoForm  = document.getElementById('update-info-form');
    const updateMsg       = document.getElementById('update-msg');

    if (editInfoBtn) {
        editInfoBtn.addEventListener('click', (e) => {
            e.preventDefault();
            infoEditSection.style.display = 'block';
            editInfoBtn.style.display = 'none';
        });
    }

    if (cancelInfoBtn) {
        cancelInfoBtn.addEventListener('click', () => {
            infoEditSection.style.display = 'none';
            editInfoBtn.style.display = 'block';
            updateMsg.innerText = '';
        });
    }

    if (updateInfoForm) {
        updateInfoForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const username        = document.getElementById('edit-username').value.trim();
            const fullname        = document.getElementById('edit-fullname').value.trim();
            const newPassword     = document.getElementById('edit-new-password').value;
            const confirmPassword = document.getElementById('edit-confirm-password').value;

            if (newPassword !== '' && newPassword !== confirmPassword) {
                updateMsg.style.color = 'red';
                updateMsg.innerText = 'Mật khẩu xác nhận không khớp!';
                return;
            }

            const formData = new FormData();
            formData.append('username', username);
            formData.append('fullname', fullname);
            if (newPassword !== '') formData.append('password', newPassword);

            fetch('api/update_info.php', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        if (window.showToast) {
                            showToast('Cập nhật thành công!');
                            setTimeout(() => window.location.reload(), 2000);
                        } else {
                            updateMsg.style.color = 'green';
                            updateMsg.innerText = 'Cập nhật thành công! Đang tải lại trang...';
                            setTimeout(() => window.location.reload(), 1500);
                        }
                    } else {
                        updateMsg.style.color = 'red';
                        updateMsg.innerText = data.message || 'Có lỗi xảy ra!';
                    }
                })
                .catch(() => {
                    updateMsg.style.color = 'red';
                    updateMsg.innerText = 'Lỗi kết nối máy chủ!';
                });
        });
    }

    // ── Toast ─────────────────────────────────────────────────────────────────
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

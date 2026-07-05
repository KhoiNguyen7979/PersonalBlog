/**
 * public/js/profile.js
 * Xử lý đổi avatar và sửa mô tả cá nhân.
 */
document.addEventListener('DOMContentLoaded', () => {

    // ── Avatar Upload ─────────────────────────────────────────────────────────
    const avatarInput = document.getElementById('avatar-upload');
    const avatarImg   = document.getElementById('profile-avatar');

    if (avatarInput) {
        avatarInput.addEventListener('change', () => {
            const file = avatarInput.files[0];
            if (!file) return;

            // Preview tức thì
            const reader = new FileReader();
            reader.onload = (e) => {
                avatarImg.src = e.target.result;
            };
            reader.readAsDataURL(file);

            // Upload lên server
            const formData = new FormData();
            formData.append('action', 'update_avatar');
            formData.append('avatar', file);

            fetch('api/update_profile.php', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    showToast(data.success ? '✅ Đã cập nhật ảnh đại diện!' : '❌ ' + data.message);
                })
                .catch(() => showToast('❌ Lỗi kết nối server.'));
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
                        // Cập nhật giao diện
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

    // ── Toast Notification ────────────────────────────────────────────────────
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

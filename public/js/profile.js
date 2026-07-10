/**
 * public/js/profile.js
 * Xử lý đổi avatar và cập nhật thông tin cá nhân (bao gồm mô tả).
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

document.addEventListener("DOMContentLoaded", function() {
    const editInfoBtn = document.getElementById("edit-info-btn");
    const infoEditSection = document.getElementById("info-edit-section");
    const cancelInfoBtn = document.getElementById("cancel-info-btn");
    const updateInfoForm = document.getElementById("update-info-form");
    const updateMsg = document.getElementById("update-msg");

    // Hiện form cập nhật
    if (editInfoBtn) {
        editInfoBtn.addEventListener("click", function(e) {
            e.preventDefault();
            infoEditSection.style.display = "block";
            editInfoBtn.style.display = "none";
        });
    }

    // Ẩn form cập nhật
    if (cancelInfoBtn) {
        cancelInfoBtn.addEventListener("click", function() {
            infoEditSection.style.display = "none";
            editInfoBtn.style.display = "block";
            updateMsg.innerText = ""; // Xóa thông báo lỗi cũ
        });
    }

    // Xử lý gửi form cập nhật thông tin
    if (updateInfoForm) {
        updateInfoForm.addEventListener("submit", function(e) {
            e.preventDefault();
            
            const username = document.getElementById("edit-username").value.trim();
            const fullname = document.getElementById("edit-fullname").value.trim();
            const mota = document.getElementById("edit-mota").value.trim(); // Lấy giá trị mô tả
            const newPassword = document.getElementById("edit-new-password").value;
            const confirmPassword = document.getElementById("edit-confirm-password").value;

            // Kiểm tra mật khẩu xác nhận
            if (newPassword !== "" && newPassword !== confirmPassword) {
                updateMsg.style.color = "red";
                updateMsg.innerText = "Mật khẩu xác nhận không khớp!";
                return;
            }

            // Gửi dữ liệu qua Fetch API
            const formData = new FormData();
            formData.append('username', username);
            formData.append('fullname', fullname);
            formData.append('mota', mota); // Gửi thêm mô tả vào form data
            
            if (newPassword !== "") {
                formData.append('password', newPassword);
            }

            fetch('api/update_info.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    updateMsg.style.color = "green";
                    updateMsg.innerText = "Cập nhật thành công! Đang tải lại trang...";
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    updateMsg.style.color = "red";
                    updateMsg.innerText = data.message || "Có lỗi xảy ra!";
                }
            })
            .catch(error => {
                console.error("Error:", error);
                updateMsg.style.color = "red";
                updateMsg.innerText = "Lỗi kết nối máy chủ!";
            });
        });
    }
});
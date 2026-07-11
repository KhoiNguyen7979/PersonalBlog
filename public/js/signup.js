document.querySelector("form").addEventListener("submit", function(event) {
    var password = document.querySelector('input[name="password"]').value;
    var confirmPassword = document.querySelector('input[name="confirm_password"]').value;
    if (password !== confirmPassword) {
        event.preventDefault();
        if (window.showToast) {
            showToast('Mật khẩu xác nhận không trùng khớp. Vui lòng nhập lại!', 'error');
        } else {
            alert("Mật khẩu xác nhận không trùng khớp. Vui lòng nhập lại!");
        }
    }
});
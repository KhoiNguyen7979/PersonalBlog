// Lắng nghe sự kiện khi người dùng bấm nút Sign Up
document.querySelector("form").addEventListener("submit", function(event) {
    // Lấy giá trị của 2 ô mật khẩu
    var password = document.querySelector('input[name="password"]').value;
    var confirmPassword = document.querySelector('input[name="confirm_password"]').value;
    // Nếu không khớp, chặn gửi form và hiện alert
    if (password !== confirmPassword) {
        event.preventDefault(); // Lệnh này giúp form KHÔNG bị gửi đi
        alert("Mật khẩu xác nhận không trùng khớp. Vui lòng nhập lại!");
    }
});
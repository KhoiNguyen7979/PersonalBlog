// === _header.js - Dropdown menu tài khoản (hover hiện/ẩn) ===
// Hiển thị dropdown khi hover vào avatar, ẩn sau 200ms khi rời chuột
const accountContainer = document.getElementById('account_container_2');
const dropbox_log = document.getElementById('logoption');
let closeTimer_too;

accountContainer.addEventListener('mouseenter', () => {
    clearTimeout(closeTimer_too);
    dropbox_log.style.display = 'block';
});

accountContainer.addEventListener('mouseleave', () => {
    closeTimer_too = setTimeout(() => {
        dropbox_log.style.display = 'none';
    }, 200); // 200ms delay
});

dropbox_log.addEventListener('mouseenter', () => {
    clearTimeout(closeTimer_too);
});
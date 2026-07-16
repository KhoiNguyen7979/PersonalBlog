// Hiển thị dropdown khi hover vào avatar, ẩn sau 200ms khi rời chuột
const accountContainer = document.getElementById('account_container_2');
const dropbox_log = document.getElementById('logoption');
let closeTimer_too;
// Khi chuột vào khu vực thì hiển thị dạng block với người dùng
accountContainer.addEventListener('mouseenter', () => {
    clearTimeout(closeTimer_too);
    dropbox_log.style.display = 'block';
});
// Khi chuột rời khu vực thì không hiển thị với người dùng
accountContainer.addEventListener('mouseleave', () => {
    closeTimer_too = setTimeout(() => {
        dropbox_log.style.display = 'none';
    }, 200); // delay 200ms
});
// Khi chuột vào khu vực thì reset lại thời gian delay
dropbox_log.addEventListener('mouseenter', () => {
    clearTimeout(closeTimer_too);
});
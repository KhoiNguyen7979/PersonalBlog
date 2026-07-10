//Cài đặt cho sign-up, sign-in xuất hiện khi hover qua logo tài khoản
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
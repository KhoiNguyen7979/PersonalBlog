<?php
// === TRANG ĐĂNG XUẤT ===
// Xoá session và chuyển về trang chủ với toast thông báo
session_start();
session_unset();
session_destroy();
header("Location: index.php?page=home&toast=" . urlencode("Signed out successfully!"));
exit();
?>
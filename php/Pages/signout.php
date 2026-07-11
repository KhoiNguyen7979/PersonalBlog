<?php
session_start();
session_unset();
session_destroy();
header("Location: index.php?page=home&toast=" . urlencode("Đăng xuất thành công!"));
exit();
?>
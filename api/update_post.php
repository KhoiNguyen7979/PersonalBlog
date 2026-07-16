<?php
// === API: Cập nhật bài viết ===
// Sửa tiêu đề, tóm tắt, nội dung, thể loại và (tuỳ chọn) thay ảnh mới
session_start();
//thiết lập kết nối CSDL
require_once __DIR__ . '/../php/mySQLconnect.php';
header('Content-Type: application/json; charset=utf-8');

// Kiểm tra đăng nhập
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$id       = intval($_POST['post_id'] ?? 0);
$title    = trim($_POST['title'] ?? '');
$summary  = trim($_POST['summary'] ?? '');
$category = trim($_POST['category'] ?? '');
$content  = trim($_POST['content'] ?? '');
$email    = $_SESSION['email'];
$isAdmin  = isset($_SESSION['vaitro']) && $_SESSION['vaitro'] === 'admin';

if (empty($title) || empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Missing information.']);
    exit;
}

// Nếu có ảnh mới → xóa hết ảnh cũ rồi thêm ảnh mới
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$extMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);

if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] === UPLOAD_ERR_OK) {
    $tmp = $_FILES['new_image']['tmp_name'];
    $mime = finfo_file($finfo, $tmp);
    if (in_array($mime, $allowedTypes) && $_FILES['new_image']['size'] <= 5 * 1024 * 1024) {
        // Xóa hết ảnh cũ
        $stmtDel = $connect->prepare("DELETE FROM Pics WHERE ID_BaiViet = ?");
        $stmtDel->bind_param("i", $id);
        $stmtDel->execute();
        $stmtDel->close();

        // Thêm ảnh mới
        $data = file_get_contents($tmp);
        $name = $_FILES['new_image']['name'];
        $ext  = $extMap[$mime];
        $size = $_FILES['new_image']['size'];
        $stmtPic = $connect->prepare("INSERT INTO Pics (Ten_File_Anh, Duoi_File_Anh, Kich_Co_Anh, Du_Lieu_Anh, ID_BaiViet, IsThumb) VALUES (?, ?, ?, ?, ?, 1)");
        $stmtPic->bind_param("ssiss", $name, $ext, $size, $data, $id);
        $stmtPic->execute();
        $stmtPic->close();
    }
}
finfo_close($finfo);

// Cập nhật các thông tin của bài viết
$wordCount = str_word_count(strip_tags($content));

if ($isAdmin) {
    $stmt = $connect->prepare("
        UPDATE BaiViet 
        SET TieuDe=?, NoiDung=?, TomTat=?, ID_The_Loai=? 
        WHERE ID_BaiViet=?
    ");
    $stmt->bind_param("ssssi", $title, $content, $summary, $category, $id);
} else {
    $stmt = $connect->prepare("
        UPDATE BaiViet 
        SET TieuDe=?, NoiDung=?, TomTat=?, ID_The_Loai=? 
        WHERE ID_BaiViet=? AND ID_NguoiDung=?
    ");
    $stmt->bind_param("ssssis", $title, $content, $summary, $category, $id, $email);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
?>

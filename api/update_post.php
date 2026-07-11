<?php
session_start();
require_once __DIR__ . '/../php/mySQLconnect.php';
header('Content-Type: application/json; charset=utf-8');

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

if (empty($title) || empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin.']);
    exit;
}

// Xóa ảnh được đánh dấu xóa (delete_ids[])
$deleteIds = [];
if (!empty($_POST['delete_ids'])) {
    $deleteIds = array_filter(array_map('intval', (array) $_POST['delete_ids']));
}

if (!empty($deleteIds)) {
    $placeholders = implode(',', array_fill(0, count($deleteIds), '?'));
    $types = str_repeat('i', count($deleteIds));
    $params = array_merge($deleteIds, [$id]);

    $stmt = $connect->prepare("DELETE FROM Pics WHERE ID_Anh IN ($placeholders) AND ID_BaiViet = ?");
    $stmt->bind_param($types . 'i', ...$params);
    $stmt->execute();
    $stmt->close();
}

// Thêm ảnh mới
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$extMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);

if (isset($_FILES['new_images']) && $_FILES['new_images']['error'] !== UPLOAD_ERR_NO_FILE) {
    if (is_array($_FILES['new_images']['name'])) {
        $count = count($_FILES['new_images']['name']);
        for ($i = 0; $i < $count; $i++) {
            if ($_FILES['new_images']['error'][$i] !== UPLOAD_ERR_OK) continue;
            $tmp = $_FILES['new_images']['tmp_name'][$i];
            $mime = finfo_file($finfo, $tmp);
            if (!in_array($mime, $allowedTypes)) continue;
            $size = $_FILES['new_images']['size'][$i];
            if ($size > 5 * 1024 * 1024) continue;
            $data = file_get_contents($tmp);
            $name = $_FILES['new_images']['name'][$i];
            $ext  = $extMap[$mime];
            $stmtPic = $connect->prepare("INSERT INTO Pics (Ten_File_Anh, Duoi_File_Anh, Kich_Co_Anh, Du_Lieu_Anh, ID_BaiViet, IsThumb) VALUES (?, ?, ?, ?, ?, 0)");
            $stmtPic->bind_param("ssiss", $name, $ext, $size, $data, $id);
            $stmtPic->execute();
            $stmtPic->close();
        }
    } elseif ($_FILES['new_images']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['new_images']['tmp_name'];
        $mime = finfo_file($finfo, $tmp);
        if (in_array($mime, $allowedTypes) && $_FILES['new_images']['size'] <= 5 * 1024 * 1024) {
            $data = file_get_contents($tmp);
            $name = $_FILES['new_images']['name'];
            $ext  = $extMap[$mime];
            $size = $_FILES['new_images']['size'];
            $stmtPic = $connect->prepare("INSERT INTO Pics (Ten_File_Anh, Duoi_File_Anh, Kich_Co_Anh, Du_Lieu_Anh, ID_BaiViet, IsThumb) VALUES (?, ?, ?, ?, ?, 0)");
            $stmtPic->bind_param("ssiss", $name, $ext, $size, $data, $id);
            $stmtPic->execute();
            $stmtPic->close();
        }
    }
}
finfo_close($finfo);

// Cập nhật bài viết
$wordCount = str_word_count(strip_tags($content));
$readTime = max(1, ceil($wordCount / 200));

$stmt = $connect->prepare("
    UPDATE BaiViet 
    SET TieuDe=?, NoiDung=?, TomTat=?, ThoiGianDoc=?, ID_The_Loai=? 
    WHERE ID_BaiViet=? AND ID_NguoiDung=?
");
$stmt->bind_param("sssssis", $title, $content, $summary, $readTime, $category, $id, $email);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi CSDL.']);
}
?>

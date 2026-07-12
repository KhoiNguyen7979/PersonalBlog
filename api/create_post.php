<?php
/**
 * api/create_post.php
 * API xử lý lưu bài viết mới và nhiều ảnh vào Database
 */
session_start();
require_once __DIR__ . '/../php/mySQLconnect.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn phải đăng nhập để viết bài.']);
    exit;
}

$email = $_SESSION['email'];

$title    = trim($_POST['title']    ?? '');
$summary  = trim($_POST['summary']  ?? '');
$category = trim($_POST['category'] ?? '');
$content  = trim($_POST['content']  ?? '');

if (empty($title) || empty($summary) || empty($category) || empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ các trường thông tin.']);
    exit;
}

// Xử lý 1 ảnh duy nhất
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$extMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];

$validFile = null;
if (isset($_FILES['images']) && $_FILES['images']['error'] === UPLOAD_ERR_OK) {
    $mime = finfo_file($finfo, $_FILES['images']['tmp_name']);
    if (in_array($mime, $allowedTypes) && $_FILES['images']['size'] <= 5 * 1024 * 1024) {
        $validFile = [
            'name' => $_FILES['images']['name'],
            'ext'  => $extMap[$mime],
            'size' => $_FILES['images']['size'],
            'data' => file_get_contents($_FILES['images']['tmp_name']),
        ];
    }
}
finfo_close($finfo);

if (!$validFile) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng chọn 1 ảnh hợp lệ.']);
    exit;
}

$wordCount = str_word_count(strip_tags($content));
$readTime  = max(1, ceil($wordCount / 200));

// Insert BaiViet
$stmt = $connect->prepare("
    INSERT INTO BaiViet (TieuDe, NoiDung, TomTat, ThoiGianDoc, ID_NguoiDung, ID_The_Loai)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("sssiss", $title, $content, $summary, $readTime, $email, $category);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Lỗi insert BaiViet: ' . $stmt->error]);
    exit;
}
$post_id = $connect->insert_id;
$stmt->close();

// Insert Pics
$stmtPic = $connect->prepare("
    INSERT INTO Pics (Ten_File_Anh, Duoi_File_Anh, Kich_Co_Anh, Du_Lieu_Anh, ID_BaiViet, IsThumb)
    VALUES (?, ?, ?, ?, ?, ?)
");
$isThumb = 1;
$stmtPic->bind_param("ssissi", $validFile['name'], $validFile['ext'], $validFile['size'], $validFile['data'], $post_id, $isThumb);
if (!$stmtPic->execute()) {
    echo json_encode(['success' => false, 'message' => 'Lỗi insert ảnh: ' . $stmtPic->error]);
    exit;
}
$stmtPic->close();
$connect->close();

echo json_encode(['success' => true, 'message' => 'Tạo bài viết thành công.']);
?>

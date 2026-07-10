<?php
/**
 * api/create_post.php
 * API xử lý lưu bài viết mới và ảnh bìa vào Database
 */
session_start();
require_once '../src/views/mySQLconnect.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn phải đăng nhập để viết bài.']);
    exit;
}

$email = $_SESSION['email'];

// Nhận dữ liệu text
$title    = trim($_POST['title']    ?? '');
$summary  = trim($_POST['summary']  ?? '');
$category = trim($_POST['category'] ?? '');
$content  = trim($_POST['content']  ?? '');

// Validate
if (empty($title) || empty($summary) || empty($category) || empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ các trường thông tin.']);
    exit;
}

// Xử lý ảnh Thumbnail
if (!isset($_FILES['thumbnail']) || $_FILES['thumbnail']['error'] !== UPLOAD_ERR_OK) {
    $errCode = $_FILES['thumbnail']['error'] ?? 'không có file';
    echo json_encode(['success' => false, 'message' => 'Lỗi tải ảnh bìa. Mã lỗi: ' . $errCode]);
    exit;
}

$file         = $_FILES['thumbnail'];
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime  = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mime, $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Chỉ hỗ trợ file ảnh (jpg, png, gif, webp). Mime nhận được: ' . $mime]);
    exit;
}

if ($file['size'] > 5 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'Kích thước ảnh vượt quá 5MB.']);
    exit;
}

$extMap  = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
$ext     = $extMap[$mime];
$imgSize = $file['size'];
$imgName = $file['name'];
$imgData = file_get_contents($file['tmp_name']);

// Tính thời gian đọc (200 từ/phút)
$wordCount = str_word_count(strip_tags($content));
$readTime  = max(1, ceil($wordCount / 200));

// Insert BaiViet
$stmt = $connect->prepare("
    INSERT INTO BaiViet (TieuDe, NoiDung, TomTat, ThoiGianDoc, ID_NguoiDung, ID_The_Loai)
    VALUES (?, ?, ?, ?, ?, ?)
");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Lỗi prepare BaiViet: ' . $connect->error]);
    exit;
}
$stmt->bind_param("sssiss", $title, $content, $summary, $readTime, $email, $category);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Lỗi insert BaiViet: ' . $stmt->error]);
    exit;
}
$post_id = $connect->insert_id;
$stmt->close();

// Insert Pics (thumbnail)
$stmtPic = $connect->prepare("
    INSERT INTO Pics (Ten_File_Anh, Duoi_File_Anh, Kich_Co_Anh, Du_Lieu_Anh, ID_BaiViet, IsThumb)
    VALUES (?, ?, ?, ?, ?, 1)
");
if (!$stmtPic) {
    echo json_encode(['success' => false, 'message' => 'Lỗi prepare Pics: ' . $connect->error]);
    exit;
}
$stmtPic->bind_param("ssiss", $imgName, $ext, $imgSize, $imgData, $post_id);
if (!$stmtPic->execute()) {
    echo json_encode(['success' => false, 'message' => 'Lỗi insert Pics: ' . $stmtPic->error]);
    exit;
}
$stmtPic->close();

$connect->close();

echo json_encode(['success' => true, 'message' => 'Tạo bài viết thành công.']);
?>

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
$title    = trim($_POST['title'] ?? '');
$summary  = trim($_POST['summary'] ?? '');
$category = trim($_POST['category'] ?? '');
$content  = trim($_POST['content'] ?? '');

// Validate
if (empty($title) || empty($summary) || empty($category) || empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ các trường thông tin.']);
    exit;
}

// Xử lý ảnh Thumbnail
if (!isset($_FILES['thumbnail']) || $_FILES['thumbnail']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Lỗi tải ảnh bìa.']);
    exit;
}

$file = $_FILES['thumbnail'];
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime  = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mime, $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Chỉ hỗ trợ file ảnh (jpg, png, gif, webp).']);
    exit;
}

if ($file['size'] > 5 * 1024 * 1024) { // 5MB
    echo json_encode(['success' => false, 'message' => 'Kích thước ảnh vượt quá 5MB.']);
    exit;
}

$extMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
$ext    = $extMap[$mime];
$imgData = file_get_contents($file['tmp_name']);
$imgSize = $file['size'];
$imgName = $file['name'];

// Bắt đầu Transaction
$connect->begin_transaction();

try {
    // 1. Insert vào bảng BaiViet
    // Tính thời gian đọc cơ bản (khoảng 200 từ / phút)
    $wordCount = str_word_count(strip_tags($content));
    $readTime = max(1, ceil($wordCount / 200));

    $stmt = $connect->prepare("
        INSERT INTO BaiViet (TieuDe, NoiDung, TomTat, ThoiGianDoc, ID_NguoiDung, ID_The_Loai)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssiss", $title, $content, $summary, $readTime, $email, $category);
    $stmt->execute();
    
    $post_id = $connect->insert_id;

    // 2. Insert vào bảng Pics (đánh dấu IsThumb = 1)
    $stmtPic = $connect->prepare("
        INSERT INTO Pics (Ten_File_Anh, Duoi_File_Anh, Kich_Co_Anh, Du_Lieu_Anh, ID_BaiViet, IsThumb)
        VALUES (?, ?, ?, ?, ?, 1)
    ");
    $stmtPic->bind_param("ssisi", $imgName, $ext, $imgSize, $imgData, $post_id);
    $stmtPic->execute();

    // Hoàn thành transaction
    $connect->commit();

    echo json_encode(['success' => true, 'message' => 'Tạo bài viết thành công.']);

} catch (Exception $e) {
    $connect->rollback();
    echo json_encode(['success' => false, 'message' => 'Lỗi lưu database: ' . $e->getMessage()]);
}

$connect->close();
?>

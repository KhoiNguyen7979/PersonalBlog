<?php
/**
 * api/create_post.php
 * API xử lý lưu bài viết mới và nhiều ảnh vào Database
 */
// === API: Tạo bài viết mới (bài viết + 1 ảnh) ===
// Nhận POST từ form tạo blog, lưu vào bảng BaiViet + Pics
session_start();
//thiết lập kết nối CSDL
require_once __DIR__ . '/../php/mySQLconnect.php';

header('Content-Type: application/json; charset=utf-8');

// Kiểm tra đăng nhập (đăng nhập mới tạo bài viết được)
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to create a post.']);
    exit;
}

$email = $_SESSION['email'];
$title    = trim($_POST['title']    ?? '');
$summary  = trim($_POST['summary']  ?? '');
$category = trim($_POST['category'] ?? '');
$content  = trim($_POST['content']  ?? '');

if (empty($title) || empty($summary) || empty($category) || empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
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
    echo json_encode(['success' => false, 'message' => 'Please select a valid image.']);
    exit;
}

$wordCount = str_word_count(strip_tags($content));

// Insert Bài viết mới vào bảng bài viết 
$stmt = $connect->prepare("
    INSERT INTO BaiViet (TieuDe, NoiDung, TomTat, ID_NguoiDung, ID_The_Loai)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("sssss", $title, $content, $summary, $email, $category);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to create post: ' . $stmt->error]);
    exit;
}
$post_id = $connect->insert_id;
$stmt->close();

// Insert pic vào bảng Pics
$stmtPic = $connect->prepare("
    INSERT INTO Pics (Ten_File_Anh, Duoi_File_Anh, Kich_Co_Anh, Du_Lieu_Anh, ID_BaiViet, IsThumb)
    VALUES (?, ?, ?, ?, ?, ?)
");
$isThumb = 1;
$stmtPic->bind_param("ssissi", $validFile['name'], $validFile['ext'], $validFile['size'], $validFile['data'], $post_id, $isThumb);
if (!$stmtPic->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to insert image: ' . $stmtPic->error]);
    exit;
}
$stmtPic->close();
$connect->close();

echo json_encode(['success' => true, 'message' => 'Post created successfully.']);
?>

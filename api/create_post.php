<?php
/**
 * api/create_post.php
 * API xử lý lưu bài viết mới và nhiều ảnh vào Database
 */
session_start();
require_once '../src/views/mySQLconnect.php';

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

// Xử lý nhiều ảnh
$files = [];
if (isset($_FILES['images']) && $_FILES['images']['error'] !== UPLOAD_ERR_NO_FILE) {
    // Có thể là 1 file hoặc nhiều files
    if (is_array($_FILES['images']['name'])) {
        $count = count($_FILES['images']['name']);
        for ($i = 0; $i < $count; $i++) {
            if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                $files[] = [
                    'name' => $_FILES['images']['name'][$i],
                    'tmp'  => $_FILES['images']['tmp_name'][$i],
                    'size' => $_FILES['images']['size'][$i],
                ];
            }
        }
    } elseif ($_FILES['images']['error'] === UPLOAD_ERR_OK) {
        $files[] = [
            'name' => $_FILES['images']['name'],
            'tmp'  => $_FILES['images']['tmp_name'],
            'size' => $_FILES['images']['size'],
        ];
    }
}

if (empty($files)) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng chọn ít nhất 1 ảnh.']);
    exit;
}

$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$extMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];

$validFiles = [];
foreach ($files as $f) {
    $mime = finfo_file($finfo, $f['tmp']);
    if (!in_array($mime, $allowedTypes)) continue;
    if ($f['size'] > 5 * 1024 * 1024) continue;
    $validFiles[] = [
        'name' => $f['name'],
        'ext'  => $extMap[$mime],
        'size' => $f['size'],
        'data' => file_get_contents($f['tmp']),
    ];
}
finfo_close($finfo);

if (empty($validFiles)) {
    echo json_encode(['success' => false, 'message' => 'Không có ảnh hợp lệ.']);
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

foreach ($validFiles as $i => $img) {
    $isThumb = ($i === 0) ? 1 : 0;
    $stmtPic->bind_param("ssissi", $img['name'], $img['ext'], $img['size'], $img['data'], $post_id, $isThumb);
    if (!$stmtPic->execute()) {
        echo json_encode(['success' => false, 'message' => 'Lỗi insert ảnh: ' . $stmtPic->error]);
        exit;
    }
}
$stmtPic->close();
$connect->close();

echo json_encode(['success' => true, 'message' => 'Tạo bài viết thành công.']);
?>

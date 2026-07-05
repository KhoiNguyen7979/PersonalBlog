<?php
/**
 * get_image.php
 * Serve ảnh bài viết hoặc avatar người dùng trực tiếp từ database.
 * 
 * Cách dùng:
 *   Ảnh bài viết : get_image.php?id=1
 *   Avatar user  : get_image.php?email=domixi@gmail.com
 */
require_once 'src/views/mySQLconnect.php';

if (isset($_GET['id'])) {
    // Lấy ảnh thumbnail của bài viết
    $id = intval($_GET['id']);
    $stmt = $connect->prepare("SELECT Du_Lieu_Anh, Duoi_File_Anh FROM Pics WHERE ID_BaiViet = ? AND IsThumb = 1 LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        // Placeholder xám nếu không có ảnh
        header("Content-Type: image/svg+xml");
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="250">
                <rect width="400" height="250" fill="#ddd"/>
                <text x="50%" y="50%" text-anchor="middle" fill="#999" font-size="18" dy=".3em">No Image</text>
              </svg>';
        exit;
    }

    $stmt->bind_result($data, $ext);
    $stmt->fetch();

    $mimeMap = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'webp' => 'image/webp'];
    $mime = $mimeMap[strtolower($ext)] ?? 'image/jpeg';

    header("Content-Type: $mime");
    header("Cache-Control: public, max-age=86400");
    echo $data;

} elseif (isset($_GET['email'])) {
    // Lấy avatar của người dùng
    $email = $_GET['email'];
    $stmt = $connect->prepare("SELECT Avatar, DuoiAnhAvatar FROM NguoiDung WHERE Email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0 || !$stmt->fetch()) {
        // Placeholder avatar
        header("Content-Type: image/svg+xml");
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="150" height="150">
                <circle cx="75" cy="75" r="75" fill="#ccc"/>
                <circle cx="75" cy="60" r="30" fill="#fff"/>
                <ellipse cx="75" cy="130" rx="50" ry="40" fill="#fff"/>
              </svg>';
        exit;
    }

    $stmt->bind_result($data, $ext);
    $stmt->fetch();

    if (empty($data)) {
        header("Location: public/images/account.jpg");
        exit;
    }

    $mimeMap = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png'];
    $mime = $mimeMap[strtolower($ext ?? 'jpg')] ?? 'image/jpeg';

    header("Content-Type: $mime");
    header("Cache-Control: public, max-age=86400");
    echo $data;

} else {
    http_response_code(400);
    echo "Bad request: Cần tham số ?id=... hoặc ?email=...";
}

$connect->close();
?>

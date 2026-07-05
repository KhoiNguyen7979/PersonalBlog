<?php
/**
 * setup_db.php
 * Chạy file này 1 lần để tạo database, bảng và dữ liệu mẫu.
 * Truy cập: http://localhost/PersonalBlog/setup_db.php
 */

$server   = "localhost";
$username = "root";
$password = "";
$dbname   = "blog_riel";

// Kết nối không chỉ định database để tạo mới
$conn = new mysqli($server, $username, $password);
if ($conn->connect_error) {
    die("<b>Lỗi kết nối:</b> " . $conn->connect_error);
}

// Cấu hình charset
$conn->set_charset("utf8mb4");

echo "<h2>🔧 BloggerZ - Setup Database</h2><pre>";

// ─── 1. Tạo database ────────────────────────────────────────────────────────
$conn->query("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
echo "✅ Database '$dbname' đã sẵn sàng.\n";

$conn->select_db($dbname);

// ─── 2. Tạo bảng NguoiDung ──────────────────────────────────────────────────
$conn->query("DROP TABLE IF EXISTS Pics, Videos, BaiViet, NguoiDung, TheLoai");

$conn->query("
    CREATE TABLE NguoiDung (
        Email           VARCHAR(100)    PRIMARY KEY,
        HoTenNguoiDung  VARCHAR(100)    NOT NULL,
        TenDangNhap     VARCHAR(100)    NOT NULL UNIQUE,
        MatKhau         VARCHAR(255)    NOT NULL,
        MoTa            TEXT,
        Avatar          MEDIUMBLOB,
        DuoiAnhAvatar   VARCHAR(10)     DEFAULT 'jpg'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");
echo "✅ Bảng NguoiDung đã tạo.\n";

// ─── 3. Tạo bảng TheLoai ────────────────────────────────────────────────────
$conn->query("
    CREATE TABLE TheLoai (
        ID_TheLoai  VARCHAR(50)  PRIMARY KEY,
        Ten_TheLoai VARCHAR(100) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");
echo "✅ Bảng TheLoai đã tạo.\n";

// ─── 4. Tạo bảng BaiViet ────────────────────────────────────────────────────
$conn->query("
    CREATE TABLE BaiViet (
        ID_BaiViet      INT             AUTO_INCREMENT PRIMARY KEY,
        TieuDe          VARCHAR(255)    NOT NULL,
        NoiDung         LONGBLOB,
        TomTat          VARCHAR(255)    DEFAULT '',
        NgayDang        DATETIME        DEFAULT CURRENT_TIMESTAMP,
        ThoiGianDoc     INT             DEFAULT 1,
        LuotXem         INT             DEFAULT 0,
        ID_NguoiDung    VARCHAR(100)    NOT NULL,
        ID_The_Loai     VARCHAR(50)     NOT NULL,
        FOREIGN KEY (ID_NguoiDung) REFERENCES NguoiDung(Email) ON DELETE CASCADE,
        FOREIGN KEY (ID_The_Loai)  REFERENCES TheLoai(ID_TheLoai) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");
echo "✅ Bảng BaiViet đã tạo.\n";

// ─── 5. Tạo bảng Pics ───────────────────────────────────────────────────────
$conn->query("
    CREATE TABLE Pics (
        ID_Anh          INT             AUTO_INCREMENT PRIMARY KEY,
        Ten_File_Anh    VARCHAR(200)    NOT NULL,
        Duoi_File_Anh   VARCHAR(10)     NOT NULL,
        Kich_Co_Anh     INT             NOT NULL,
        Du_Lieu_Anh     MEDIUMBLOB      NOT NULL,
        ID_BaiViet      INT             NOT NULL,
        IsThumb         TINYINT(1)      DEFAULT 1,
        FOREIGN KEY (ID_BaiViet) REFERENCES BaiViet(ID_BaiViet) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");
echo "✅ Bảng Pics đã tạo.\n";

// ─── 6. Tạo bảng Videos ─────────────────────────────────────────────────────
$conn->query("
    CREATE TABLE Videos (
        ID_Video        INT             AUTO_INCREMENT PRIMARY KEY,
        Ten_File_Video  VARCHAR(200)    NOT NULL,
        Duoi_file       VARCHAR(10)     NOT NULL,
        Kich_Co_Video   INT             NOT NULL,
        Du_Lieu_Video   MEDIUMBLOB      NOT NULL,
        ID_BaiViet      INT             NOT NULL,
        FOREIGN KEY (ID_BaiViet) REFERENCES BaiViet(ID_BaiViet) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");
echo "✅ Bảng Videos đã tạo.\n";

// ─── 7. Seed dữ liệu ────────────────────────────────────────────────────────

// --- Thể loại ---
$categories = [
    ['technology', 'Technology'],
    ['skill',      'Skill'],
    ['story',      'Story'],
    ['music',      'Music'],
];
$stmtCat = $conn->prepare("INSERT IGNORE INTO TheLoai (ID_TheLoai, Ten_TheLoai) VALUES (?, ?)");
foreach ($categories as $cat) {
    $stmtCat->bind_param("ss", $cat[0], $cat[1]);
    $stmtCat->execute();
}
echo "✅ Đã thêm 4 thể loại.\n";

// --- Người dùng ---
$users = [
    [
        'email'    => 'domixi@gmail.com',
        'hoten'    => 'Độ Mixi',
        'tendangnhap' => 'domixi',
        'matkhau'  => password_hash('123456', PASSWORD_DEFAULT),
        'mota'     => 'Được biết đến với danh hiệu "Chủ tịch Bộ tộc MixiGaming", Độ Mixi là một trong những streamer có sức ảnh hưởng lớn nhất Việt Nam. Từ những ngày đầu livestream game cho đến khi trở thành cái tên quen thuộc trên khắp các nền tảng mạng xã hội, anh luôn giữ được phong cách gần gũi, chân thật và cực kỳ hài hước.',
    ],
    [
        'email'    => 'nguyen.abc@gmail.com',
        'hoten'    => 'Nguyễn Văn A',
        'tendangnhap' => 'nguyenvana',
        'matkhau'  => password_hash('123456', PASSWORD_DEFAULT),
        'mota'     => 'Blogger công nghệ từ Hà Nội.',
    ],
    [
        'email'    => 'tran.xyz@gmail.com',
        'hoten'    => 'Trần Thị B',
        'tendangnhap' => 'tranthib',
        'matkhau'  => password_hash('123456', PASSWORD_DEFAULT),
        'mota'     => 'Yêu thích âm nhạc và chia sẻ câu chuyện cuộc sống.',
    ],
];
$stmtUser = $conn->prepare("INSERT IGNORE INTO NguoiDung (Email, HoTenNguoiDung, TenDangNhap, MatKhau, MoTa) VALUES (?, ?, ?, ?, ?)");
foreach ($users as $u) {
    $stmtUser->bind_param("sssss", $u['email'], $u['hoten'], $u['tendangnhap'], $u['matkhau'], $u['mota']);
    $stmtUser->execute();
}
echo "✅ Đã thêm " . count($users) . " người dùng.\n";

// --- Bài viết mẫu ---
$posts = [
    // Của Độ Mixi
    ['Na ná na nà anh Phùng Thanh Độ', 'Bài hát này rất tây ....................................', 'story',      'domixi@gmail.com',       36, 1],
    ['Hành trình trở thành streamer',  'Từ những ngày đầu khó khăn cho đến khi nổi tiếng.', 'story',      'domixi@gmail.com',       50, 2],
    ['Review gaming chair mới nhất',   'Ghế gaming nào tốt nhất cho streamer năm 2025?',      'technology', 'domixi@gmail.com',       28, 3],
    ['Kỹ năng quản lý cộng đồng',      'Làm thế nào để giữ cộng đồng fan luôn vui vẻ.',      'skill',      'domixi@gmail.com',       12, 1],
    ['Playlist nhạc yêu thích tháng 7','Những bài nhạc Độ Mixi nghe xuyên suốt tháng này.',  'music',      'domixi@gmail.com',       44, 2],
    ['Setup stream chuyên nghiệp',     'Hướng dẫn setup máy tính để stream không giật lag.', 'technology', 'domixi@gmail.com',       60, 3],
    // Của người khác
    ['Top 5 framework JS năm 2025',    'Những framework JavaScript hot nhất hiện nay.',       'technology', 'nguyen.abc@gmail.com',   30, 1],
    ['Cách học ngoại ngữ hiệu quả',   'Bí quyết học tiếng Anh trong 6 tháng.',               'skill',      'nguyen.abc@gmail.com',   18, 2],
    ['Chuyến du lịch Đà Lạt 2025',    'Trải nghiệm không thể quên ở thành phố ngàn hoa.',   'story',      'tran.xyz@gmail.com',     22, 1],
    ['Những bản nhạc acoustic hay',   'Tổng hợp nhạc acoustic nhẹ nhàng cho buổi tối.',      'music',      'tran.xyz@gmail.com',     15, 2],
    ['AI và tương lai lập trình',      'Liệu AI có thay thế lập trình viên trong 10 năm tới?','technology', 'nguyen.abc@gmail.com',   88, 3],
    ['Chia sẻ về cuộc sống tự do',    'Câu chuyện về hành trình sống tự do của tôi.',         'story',      'tran.xyz@gmail.com',     33, 4],
];

$stmtPost = $conn->prepare("
    INSERT INTO BaiViet (TieuDe, TomTat, NgayDang, ThoiGianDoc, LuotXem, ID_NguoiDung, ID_The_Loai)
    VALUES (?, ?, DATE_SUB(NOW(), INTERVAL ? DAY), ?, ?, ?, ?)
");
foreach ($posts as $p) {
    [$title, $summary, $cat, $email, $views, $daysAgo] = $p;
    $readTime = rand(1, 5);
    $stmtPost->bind_param("ssiisss", $title, $summary, $daysAgo, $readTime, $views, $email, $cat);
    $stmtPost->execute();
}
echo "✅ Đã thêm " . count($posts) . " bài viết mẫu.\n";

echo "\n<b>🎉 Setup hoàn tất! Database '$dbname' đã sẵn sàng.</b>\n";
echo "\n<b>Tài khoản demo:</b>\n";
echo "  - Email: domixi@gmail.com    / Mật khẩu: 123456\n";
echo "  - Email: nguyen.abc@gmail.com / Mật khẩu: 123456\n";
echo "  - Email: tran.xyz@gmail.com  / Mật khẩu: 123456\n";
echo "</pre>";

$conn->close();
?>

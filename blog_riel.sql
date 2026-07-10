-- ==========================================================
-- BỘ KHỞI TẠO DATABASE: blog_riel
-- Chuyển đổi từ file setup_db.php
-- ==========================================================
-- 1. Tạo và chọn database
CREATE DATABASE IF NOT EXISTS `blog_riel` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `blog_riel`;

-- 2. Xóa các bảng cũ nếu đã tồn tại (Xóa theo thứ tự để không bị lỗi Khóa ngoại)
DROP TABLE IF EXISTS Pics, Videos, BaiViet, NguoiDung, TheLoai;

-- 3. Tạo bảng NguoiDung
CREATE TABLE NguoiDung (
    Email           VARCHAR(100)    PRIMARY KEY,
    HoTenNguoiDung  VARCHAR(100)    NOT NULL,
    TenDangNhap     VARCHAR(100)    NOT NULL UNIQUE,
    MatKhau         VARCHAR(255)    NOT NULL,
    MoTa            TEXT,
    Avatar          MEDIUMBLOB,
    DuoiAnhAvatar   VARCHAR(10)     DEFAULT 'jpg'
)
-- 4. Tạo bảng TheLoai
CREATE TABLE TheLoai (
    ID_TheLoai  VARCHAR(50)  PRIMARY KEY,
    Ten_TheLoai VARCHAR(100) NOT NULL
)

-- 5. Tạo bảng BaiViet
CREATE TABLE BaiViet (
    ID_BaiViet      INT             AUTO_INCREMENT PRIMARY KEY,
    TieuDe          VARCHAR(255)    NOT NULL,
    NoiDung         LONGBLOB,
    TomTat          VARCHAR(255)    DEFAULT '',
    NgayDang        DATETIME        DEFAULT CURRENT_TIMESTAMP,
    LuotXem         INT             DEFAULT 0,
    ID_NguoiDung    VARCHAR(100)    NOT NULL,
    ID_The_Loai     VARCHAR(50)     NOT NULL,
    FOREIGN KEY (ID_NguoiDung) REFERENCES NguoiDung(Email) ON DELETE CASCADE,
    FOREIGN KEY (ID_The_Loai)  REFERENCES TheLoai(ID_TheLoai) ON DELETE CASCADE
)

-- 6. Tạo bảng Pics
CREATE TABLE Pics (
    ID_Anh          INT             AUTO_INCREMENT PRIMARY KEY,
    Ten_File_Anh    VARCHAR(200)    NOT NULL,
    Duoi_File_Anh   VARCHAR(10)     NOT NULL,
    Kich_Co_Anh     INT             NOT NULL,
    Du_Lieu_Anh     MEDIUMBLOB      NOT NULL,
    ID_BaiViet      INT             NOT NULL,
    FOREIGN KEY (ID_BaiViet) REFERENCES BaiViet(ID_BaiViet) ON DELETE CASCADE
)

-- 7. Tạo bảng Videos
CREATE TABLE Videos (
    ID_Video        INT             AUTO_INCREMENT PRIMARY KEY,
    Ten_File_Video  VARCHAR(200)    NOT NULL,
    Duoi_file       VARCHAR(10)     NOT NULL,
    Kich_Co_Video   INT             NOT NULL,
    Du_Lieu_Video   MEDIUMBLOB      NOT NULL,
    ID_BaiViet      INT             NOT NULL,
    FOREIGN KEY (ID_BaiViet) REFERENCES BaiViet(ID_BaiViet) ON DELETE CASCADE
)
ALTER TABLE BaiViet DROP COLUMN LuotXem;
CREATE TABLE ThichBaiViet (
    ID_BaiViet INT NOT NULL,
    Email VARCHAR(255) NOT NULL,
    NgayThich DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (ID_BaiViet, Email),
    FOREIGN KEY (ID_BaiViet) REFERENCES BaiViet(ID_BaiViet) ON DELETE CASCADE,
    FOREIGN KEY (Email) REFERENCES NguoiDung(Email) ON DELETE CASCADE
);
-- -- ==========================================================
-- -- CHÈN DỮ LIỆU MẪU (SEED DATA)
-- -- ==========================================================

-- -- Chèn dữ liệu Thể loại
-- INSERT IGNORE INTO TheLoai (ID_TheLoai, Ten_TheLoai) VALUES 
-- ('technology', 'Technology'),
-- ('skill', 'Skill'),
-- ('story', 'Story'),
-- ('music', 'Music');

-- -- Chèn dữ liệu Người dùng
-- -- Chuỗi '$2y$10$8k1/...' chính là mã hash bcrypt của chuỗi '123456'
-- INSERT IGNORE INTO NguoiDung (Email, HoTenNguoiDung, TenDangNhap, MatKhau, MoTa) VALUES 
-- ('domixi@gmail.com', 'Độ Mixi', 'domixi', '$2y$10$8k1/X.Y8Z.P.3qQ1Z2O/0e5o.I.Y3qQ1Z2O/0e5o.I.Y3qQ1Z2O/0', 'Được biết đến với danh hiệu "Chủ tịch Bộ tộc MixiGaming", Độ Mixi là một trong những streamer có sức ảnh hưởng lớn nhất Việt Nam. Từ những ngày đầu livestream game cho đến khi trở thành cái tên quen thuộc trên khắp các nền tảng mạng xã hội, anh luôn giữ được phong cách gần gũi, chân thật và cực kỳ hài hước.'),
-- ('nguyen.abc@gmail.com', 'Nguyễn Văn A', 'nguyenvana', '$2y$10$8k1/X.Y8Z.P.3qQ1Z2O/0e5o.I.Y3qQ1Z2O/0e5o.I.Y3qQ1Z2O/0', 'Blogger công nghệ từ Hà Nội.'),
-- ('tran.xyz@gmail.com', 'Trần Thị B', 'tranthib', '$2y$10$8k1/X.Y8Z.P.3qQ1Z2O/0e5o.I.Y3qQ1Z2O/0e5o.I.Y3qQ1Z2O/0', 'Yêu thích âm nhạc và chia sẻ câu chuyện cuộc sống.');

-- -- Chèn dữ liệu Bài viết
-- -- Hàm RAND() và thời gian đọc giả lập theo code PHP (1-5 phút)
-- INSERT INTO BaiViet (TieuDe, TomTat, NgayDang, ThoiGianDoc, LuotXem, ID_NguoiDung, ID_The_Loai) VALUES 
-- ('Na ná na nà anh Phùng Thanh Độ', 'Bài hát này rất tây ....................................', DATE_SUB(NOW(), INTERVAL 1 DAY), 3, 36, 'domixi@gmail.com', 'story'),
-- ('Hành trình trở thành streamer', 'Từ những ngày đầu khó khăn cho đến khi nổi tiếng.', DATE_SUB(NOW(), INTERVAL 2 DAY), 5, 50, 'domixi@gmail.com', 'story'),
-- ('Review gaming chair mới nhất', 'Ghế gaming nào tốt nhất cho streamer năm 2025?', DATE_SUB(NOW(), INTERVAL 3 DAY), 2, 28, 'domixi@gmail.com', 'technology'),
-- ('Kỹ năng quản lý cộng đồng', 'Làm thế nào để giữ cộng đồng fan luôn vui vẻ.', DATE_SUB(NOW(), INTERVAL 1 DAY), 4, 12, 'domixi@gmail.com', 'skill'),
-- ('Playlist nhạc yêu thích tháng 7', 'Những bài nhạc Độ Mixi nghe xuyên suốt tháng này.', DATE_SUB(NOW(), INTERVAL 2 DAY), 1, 44, 'domixi@gmail.com', 'music'),
-- ('Setup stream chuyên nghiệp', 'Hướng dẫn setup máy tính để stream không giật lag.', DATE_SUB(NOW(), INTERVAL 3 DAY), 4, 60, 'domixi@gmail.com', 'technology'),
-- ('Top 5 framework JS năm 2025', 'Những framework JavaScript hot nhất hiện nay.', DATE_SUB(NOW(), INTERVAL 1 DAY), 3, 30, 'nguyen.abc@gmail.com', 'technology'),
-- ('Cách học ngoại ngữ hiệu quả', 'Bí quyết học tiếng Anh trong 6 tháng.', DATE_SUB(NOW(), INTERVAL 2 DAY), 5, 18, 'nguyen.abc@gmail.com', 'skill'),
-- ('Chuyến du lịch Đà Lạt 2025', 'Trải nghiệm không thể quên ở thành phố ngàn hoa.', DATE_SUB(NOW(), INTERVAL 1 DAY), 2, 22, 'tran.xyz@gmail.com', 'story'),
-- ('Những bản nhạc acoustic hay', 'Tổng hợp nhạc acoustic nhẹ nhàng cho buổi tối.', DATE_SUB(NOW(), INTERVAL 2 DAY), 4, 15, 'tran.xyz@gmail.com', 'music'),
-- ('AI và tương lai lập trình', 'Liệu AI có thay thế lập trình viên trong 10 năm tới?', DATE_SUB(NOW(), INTERVAL 3 DAY), 3, 88, 'nguyen.abc@gmail.com', 'technology'),
-- ('Chia sẻ về cuộc sống tự do', 'Câu chuyện về hành trình sống tự do của tôi.', DATE_SUB(NOW(), INTERVAL 4 DAY), 5, 33, 'tran.xyz@gmail.com', 'story');
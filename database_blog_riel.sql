-- phpMyAdmin SQL Dump
-- Host: localhost
-- Database: blog_riel
-- Cấu trúc các bảng và dữ liệu mẫu (đã bao gồm các cột mới như Avatar, MoTa, LuotXem)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `blog_riel`
--
CREATE DATABASE IF NOT EXISTS `blog_riel` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `blog_riel`;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `NguoiDung`
--
CREATE TABLE `NguoiDung` (
  `Email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `HoTenNguoiDung` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `TenDangNhap` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `MatKhau` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `MoTa` text COLLATE utf8mb4_unicode_ci,
  `Avatar` mediumblob,
  `DuoiAnhAvatar` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'jpg',
  `VaiTro` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `NguoiDung`
-- (Mật khẩu mặc định là: 123456)
--
INSERT INTO `NguoiDung` (`Email`, `HoTenNguoiDung`, `TenDangNhap`, `MatKhau`, `MoTa`, `VaiTro`) VALUES
('admin@admin.com', 'Admin', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Quản trị viên hệ thống.', 'admin'),
('domixi@gmail.com', 'Độ Mixi', 'domixi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Được biết đến với danh hiệu "Chủ tịch Bộ tộc MixiGaming", Độ Mixi là một trong những streamer có sức ảnh hưởng lớn nhất Việt Nam. Từ những ngày đầu livestream game cho đến khi trở thành cái tên quen thuộc trên khắp các nền tảng mạng xã hội, anh luôn giữ được phong cách gần gũi, chân thật và cực kỳ hài hước.', 'user'),
('nguyen.abc@gmail.com', 'Nguyễn Văn A', 'nguyenvana', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blogger công nghệ từ Hà Nội.', 'user'),
('tran.xyz@gmail.com', 'Trần Thị B', 'tranthib', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Yêu thích âm nhạc và chia sẻ câu chuyện cuộc sống.', 'user');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `TheLoai`
--
CREATE TABLE `TheLoai` (
  `ID_TheLoai` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Ten_TheLoai` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `TheLoai`
--
INSERT INTO `TheLoai` (`ID_TheLoai`, `Ten_TheLoai`) VALUES
('music', 'Music'),
('skill', 'Skill'),
('story', 'Story'),
('technology', 'Technology');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `BaiViet`
--
CREATE TABLE `BaiViet` (
  `ID_BaiViet` int(11) NOT NULL,
  `TieuDe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `NoiDung` longblob,
  `TomTat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `NgayDang` datetime DEFAULT CURRENT_TIMESTAMP,
  `ThoiGianDoc` int(11) DEFAULT 1,
  `LuotXem` int(11) DEFAULT 0,
  `ID_NguoiDung` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ID_The_Loai` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `BaiViet`
--
INSERT INTO `BaiViet` (`ID_BaiViet`, `TieuDe`, `NoiDung`, `TomTat`, `NgayDang`, `ThoiGianDoc`, `LuotXem`, `ID_NguoiDung`, `ID_The_Loai`) VALUES
(1, 'Na ná na nà anh Phùng Thanh Độ', NULL, 'Bài hát này rất tây ....................................', '2026-07-04 10:00:00', 3, 36, 'domixi@gmail.com', 'story'),
(2, 'Hành trình trở thành streamer', NULL, 'Từ những ngày đầu khó khăn cho đến khi nổi tiếng.', '2026-07-03 10:00:00', 2, 50, 'domixi@gmail.com', 'story'),
(3, 'Review gaming chair mới nhất', NULL, 'Ghế gaming nào tốt nhất cho streamer năm 2025?', '2026-07-02 10:00:00', 4, 28, 'domixi@gmail.com', 'technology'),
(4, 'Kỹ năng quản lý cộng đồng', NULL, 'Làm thế nào để giữ cộng đồng fan luôn vui vẻ.', '2026-07-01 10:00:00', 3, 12, 'domixi@gmail.com', 'skill'),
(5, 'Top 5 framework JS năm 2025', NULL, 'Những framework JavaScript hot nhất hiện nay.', '2026-07-04 09:00:00', 5, 30, 'nguyen.abc@gmail.com', 'technology'),
(6, 'Cách học ngoại ngữ hiệu quả', NULL, 'Bí quyết học tiếng Anh trong 6 tháng.', '2026-07-03 09:00:00', 2, 18, 'nguyen.abc@gmail.com', 'skill');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Pics`
--
CREATE TABLE `Pics` (
  `ID_Anh` int(11) NOT NULL,
  `Ten_File_Anh` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Duoi_File_Anh` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Kich_Co_Anh` int(11) NOT NULL,
  `Du_Lieu_Anh` mediumblob NOT NULL,
  `ID_BaiViet` int(11) NOT NULL,
  `IsThumb` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Videos`
--
CREATE TABLE `Videos` (
  `ID_Video` int(11) NOT NULL,
  `Ten_File_Video` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Duoi_file` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Kich_Co_Video` int(11) NOT NULL,
  `Du_Lieu_Video` mediumblob NOT NULL,
  `ID_BaiViet` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

ALTER TABLE `NguoiDung`
  ADD PRIMARY KEY (`Email`),
  ADD UNIQUE KEY `TenDangNhap` (`TenDangNhap`);

ALTER TABLE `TheLoai`
  ADD PRIMARY KEY (`ID_TheLoai`);

ALTER TABLE `BaiViet`
  ADD PRIMARY KEY (`ID_BaiViet`),
  ADD KEY `ID_NguoiDung` (`ID_NguoiDung`),
  ADD KEY `ID_The_Loai` (`ID_The_Loai`);

ALTER TABLE `Pics`
  ADD PRIMARY KEY (`ID_Anh`),
  ADD KEY `ID_BaiViet` (`ID_BaiViet`);

ALTER TABLE `Videos`
  ADD PRIMARY KEY (`ID_Video`),
  ADD KEY `ID_BaiViet` (`ID_BaiViet`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

ALTER TABLE `BaiViet`
  MODIFY `ID_BaiViet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `Pics`
  MODIFY `ID_Anh` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `Videos`
  MODIFY `ID_Video` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

ALTER TABLE `BaiViet`
  ADD CONSTRAINT `BaiViet_ibfk_1` FOREIGN KEY (`ID_NguoiDung`) REFERENCES `NguoiDung` (`Email`) ON DELETE CASCADE,
  ADD CONSTRAINT `BaiViet_ibfk_2` FOREIGN KEY (`ID_The_Loai`) REFERENCES `TheLoai` (`ID_TheLoai`) ON DELETE CASCADE;

ALTER TABLE `Pics`
  ADD CONSTRAINT `Pics_ibfk_1` FOREIGN KEY (`ID_BaiViet`) REFERENCES `BaiViet` (`ID_BaiViet`) ON DELETE CASCADE;

ALTER TABLE `Videos`
  ADD CONSTRAINT `Videos_ibfk_1` FOREIGN KEY (`ID_BaiViet`) REFERENCES `BaiViet` (`ID_BaiViet`) ON DELETE CASCADE;

--
-- Cấu trúc bảng cho bảng `ThichBaiViet`
--
CREATE TABLE `ThichBaiViet` (
  `ID_Thich` int(11) NOT NULL AUTO_INCREMENT,
  `ID_BaiViet` int(11) NOT NULL,
  `Email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `NgayThich` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_Thich`),
  UNIQUE KEY `unique_like` (`ID_BaiViet`, `Email`),
  KEY `ID_BaiViet` (`ID_BaiViet`),
  KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `ThichBaiViet`
  ADD CONSTRAINT `ThichBaiViet_ibfk_1` FOREIGN KEY (`ID_BaiViet`) REFERENCES `BaiViet` (`ID_BaiViet`) ON DELETE CASCADE,
  ADD CONSTRAINT `ThichBaiViet_ibfk_2` FOREIGN KEY (`Email`) REFERENCES `NguoiDung` (`Email`) ON DELETE CASCADE;

--
-- Cấu trúc bảng cho bảng `Reviews`
--
CREATE TABLE `Reviews` (
  `ID_Review` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `HoTen` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `NoiDung` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `NgayTao` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `Reviews`
--
INSERT INTO `Reviews` (`HoTen`, `Email`, `NoiDung`) VALUES
('Vinh Phan', 'vinh@gmail.com', 'Blog rất tuyệt vời, giao diện đẹp và hiện đại!'),
('Lan Anh', 'lananh@gmail.com', 'Rất thích những bài viết chia sẻ về công nghệ của bạn. Keep it up!'),
('Đức Minh', 'minh@example.com', 'Thiết kế đẹp mắt, trải nghiệm người dùng tốt.');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

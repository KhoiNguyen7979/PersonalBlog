-- Tạo (nếu chưa có) Cơ sở dữ liệu: blog_riel

CREATE DATABASE IF NOT EXISTS `blog_riel` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `blog_riel`;

-- Bảng NguoiDung
CREATE TABLE `NguoiDung` (
  `Email` varchar(100) NOT NULL,
  `HoTenNguoiDung` varchar(100) NOT NULL,
  `TenDangNhap` varchar(100) NOT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `MoTa` text DEFAULT NULL,
  `Avatar` mediumblob DEFAULT NULL,
  `DuoiAnhAvatar` varchar(10) DEFAULT 'jpg',
  `VaiTro` varchar(20) DEFAULT 'user',
  PRIMARY KEY (`Email`),
  UNIQUE KEY `TenDangNhap` (`TenDangNhap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng TheLoai
CREATE TABLE `TheLoai` (
  `ID_TheLoai` varchar(50) NOT NULL,
  `Ten_TheLoai` varchar(100) NOT NULL,
  PRIMARY KEY (`ID_TheLoai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng BaiViet
CREATE TABLE `BaiViet` (
  `ID_BaiViet` int(11) NOT NULL AUTO_INCREMENT,
  `TieuDe` varchar(255) NOT NULL,
  `NoiDung` longblob DEFAULT NULL,
  `TomTat` varchar(255) DEFAULT '',
  `NgayDang` datetime DEFAULT CURRENT_TIMESTAMP,
  `ID_NguoiDung` varchar(100) NOT NULL,
  `ID_The_Loai` varchar(50) NOT NULL,
  PRIMARY KEY (`ID_BaiViet`),
  KEY `ID_NguoiDung` (`ID_NguoiDung`),
  KEY `ID_The_Loai` (`ID_The_Loai`),
  CONSTRAINT `BaiViet_ibfk_1` FOREIGN KEY (`ID_NguoiDung`) REFERENCES `NguoiDung` (`Email`) ON DELETE CASCADE,
  CONSTRAINT `BaiViet_ibfk_2` FOREIGN KEY (`ID_The_Loai`) REFERENCES `TheLoai` (`ID_TheLoai`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=7;

-- Bảng Pics
CREATE TABLE `Pics` (
  `ID_Anh` int(11) NOT NULL AUTO_INCREMENT,
  `Ten_File_Anh` varchar(200) NOT NULL,
  `Duoi_File_Anh` varchar(10) NOT NULL,
  `Kich_Co_Anh` int(11) NOT NULL,
  `Du_Lieu_Anh` mediumblob NOT NULL,
  `ID_BaiViet` int(11) NOT NULL,
  `IsThumb` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`ID_Anh`),
  KEY `ID_BaiViet` (`ID_BaiViet`),
  CONSTRAINT `Pics_ibfk_1` FOREIGN KEY (`ID_BaiViet`) REFERENCES `BaiViet` (`ID_BaiViet`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng ThichBaiViet
CREATE TABLE `ThichBaiViet` (
  `ID_Thich` int(11) NOT NULL AUTO_INCREMENT,
  `ID_BaiViet` int(11) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `NgayThich` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_Thich`),
  UNIQUE KEY `unique_like` (`ID_BaiViet`, `Email`),
  KEY `ID_BaiViet` (`ID_BaiViet`),
  KEY `Email` (`Email`),
  CONSTRAINT `ThichBaiViet_ibfk_1` FOREIGN KEY (`ID_BaiViet`) REFERENCES `BaiViet` (`ID_BaiViet`) ON DELETE CASCADE,
  CONSTRAINT `ThichBaiViet_ibfk_2` FOREIGN KEY (`Email`) REFERENCES `NguoiDung` (`Email`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng Reviews
CREATE TABLE `Reviews` (
  `ID_Review` int(11) NOT NULL AUTO_INCREMENT,
  `HoTen` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `NoiDung` text NOT NULL,
  `NgayTao` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_Review`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu mẫu: NguoiDung
-- (Mật khẩu mặc định của các tài khoản là: 123456)
INSERT INTO `NguoiDung` (`Email`, `HoTenNguoiDung`, `TenDangNhap`, `MatKhau`, `MoTa`, `VaiTro`) VALUES
('admin@admin.com', 'Admin', 'admin', '$2y$10$Ng0z5p2i.y/e7Pz3PJ3pMOVQN4z//4C3w/T.Dj.4SJO7vwiYlX4ya', 'Quản trị viên hệ thống.', 'admin'),
('domixi@gmail.com', 'Độ Mixi', 'domixi', '$2y$10$Ng0z5p2i.y/e7Pz3PJ3pMOVQN4z//4C3w/T.Dj.4SJO7vwiYlX4ya', 'Được biết đến với danh hiệu "Chủ tịch Bộ tộc MixiGaming", Độ Mixi là một trong những streamer có sức ảnh hưởng lớn nhất Việt Nam.', 'user'),
('sarah@gmail.com', 'Sarah Johnson', 'sarahj', '$2y$10$Ng0z5p2i.y/e7Pz3PJ3pMOVQN4z//4C3w/T.Dj.4SJO7vwiYlX4ya', 'Travel enthusiast and tech writer.', 'user'),
('mike@gmail.com', 'Mike Chen', 'mikechen', '$2y$10$Ng0z5p2i.y/e7Pz3PJ3pMOVQN4z//4C3w/T.Dj.4SJO7vwiYlX4ya', 'Software developer by day, musician by night.', 'user'),
('emma@gmail.com', 'Emma Wilson', 'emmaw', '$2y$10$Ng0z5p2i.y/e7Pz3PJ3pMOVQN4z//4C3w/T.Dj.4SJO7vwiYlX4ya', 'Digital nomad and lifestyle blogger.', 'user'),
('alex@gmail.com', 'Alex Rivera', 'alexr', '$2y$10$Ng0z5p2i.y/e7Pz3PJ3pMOVQN4z//4C3w/T.Dj.4SJO7vwiYlX4ya', 'Full-stack developer and open source contributor.', 'user'),
('lisa@gmail.com', 'Lisa Park', 'lisap', '$2y$10$Ng0z5p2i.y/e7Pz3PJ3pMOVQN4z//4C3w/T.Dj.4SJO7vwiYlX4ya', 'Music producer and sound engineer.', 'user');

-- Dữ liệu mẫu: TheLoai (gồm 4 thể loại chính: Âm nhạc, Kĩ Năng, Công Nghệ)
INSERT INTO `TheLoai` (`ID_TheLoai`, `Ten_TheLoai`) VALUES
('music', 'Music'),
('skill', 'Skill'),
('story', 'Story'),
('technology', 'Technology');

-- Dữ liệu mẫu: Reviews
INSERT INTO `Reviews` (`HoTen`, `Email`, `NoiDung`) VALUES
('Sarah Johnson', 'sarah@gmail.com', 'I absolutely love this platform!'),
('Mike Chen', 'mike@gmail.com', 'BloggerZ has been a fantastic platform.'),
('Emma Wilson', 'emma@gmail.com', 'As a digital nomad, this is by far the best platform.'),
('Alex Rivera', 'alex@gmail.com', 'The best blogging platform I have ever used.'),
('Lisa Park', 'lisa@gmail.com', 'Finally found a platform that takes music blogging seriously.'),
('Admin', 'admin@admin.com', 'Thank you to everyone who contributes to making this community special.');

-- Dữ liệu mẫu: BaiViet
INSERT INTO `BaiViet` (`TieuDe`, `NoiDung`, `TomTat`, `NgayDang`, `ID_NguoiDung`, `ID_The_Loai`) VALUES
('Na ná na nà anh Phùng Thanh Độ', NULL, 'Bài hát này rất tây', '2026-07-04 10:00:00', 'domixi@gmail.com', 'story'),
('Hành trình trở thành streamer', NULL, 'Từ những ngày đầu khó khăn cho đến khi nổi tiếng.', '2026-07-03 10:00:00', 'domixi@gmail.com', 'story'),
('Review gaming chair mới nhất', NULL, 'Ghế gaming nào tốt nhất cho streamer năm 2025?', '2026-07-02 10:00:00', 'domixi@gmail.com', 'technology'),
('Kỹ năng quản lý cộng đồng', NULL, 'Làm thế nào để giữ cộng đồng fan luôn vui vẻ.', '2026-07-01 10:00:00', 'domixi@gmail.com', 'skill'),
('Top 5 framework JS năm 2025', NULL, 'Những framework JavaScript hot nhất hiện nay.', '2026-07-04 09:00:00', 'nguyen.abc@gmail.com', 'technology'),
('Cách học ngoại ngữ hiệu quả', NULL, 'Bí quyết học tiếng Anh trong 6 tháng.', '2026-07-03 09:00:00', 'nguyen.abc@gmail.com', 'skill'),
('My Journey Through Southeast Asia', 'My three-month backpacking adventure across Southeast Asia was one of the most transformative experiences of my life.', 'A 3-month backpacking adventure across Thailand, Vietnam, and Cambodia.', '2026-07-10 08:00:00', 'sarah@gmail.com', 'story'),
('Top 10 Tech Gadgets for Travelers', 'As a travel lover and a tech enthusiast, I have compiled a list of gadgets that make traveling much easier.', 'Essential gadgets that make traveling easier and more connected.', '2026-07-11 10:00:00', 'sarah@gmail.com', 'technology'),
('How to Start a Travel Blog', 'Starting a travel blog is an exciting way to document your adventures.', 'Step-by-step guide to creating your own travel blog from scratch.', '2026-07-12 09:00:00', 'sarah@gmail.com', 'skill'),
('The Sound of Bangkok', 'Bangkok\'s music scene is as vibrant and diverse as the city itself.', 'Discovering the vibrant music scene in Thailand\'s capital city.', '2026-07-13 14:00:00', 'sarah@gmail.com', 'music'),
('Building Real-Time Apps with WebSockets', 'WebSockets have revolutionized how we build real-time applications.', 'A deep dive into WebSocket technology and its applications.', '2026-07-10 09:00:00', 'mike@gmail.com', 'technology'),
('Learning Guitar as an Adult', 'Picking up a guitar at the age of 30 was one of the best decisions I ever made.', 'It\'s never too late to pick up a musical instrument.', '2026-07-11 09:00:00', 'mike@gmail.com', 'skill'),
('My First Open Source Project', 'Three years ago, I made my first contribution to an open source project.', 'The story of how I contributed to my first open source project.', '2026-07-12 09:00:00', 'mike@gmail.com', 'story'),
('Jazz Influences on Modern Programming', 'It may seem like a stretch, but there is an interesting parallel between jazz and programming.', 'How jazz improvisation parallels creative coding.', '2026-07-13 09:00:00', 'mike@gmail.com', 'music'),
('Working Remotely from Da Nang', 'Da Nang has become my favorite base as a digital nomad.', 'My experience as a digital nomad in one of Vietnam\'s best cities.', '2026-07-14 08:00:00', 'emma@gmail.com', 'story'),
('Best Productivity Tools for Remote Workers', 'Working remotely presents unique challenges, but the right tools can make all the difference.', 'Tools that help me stay organized and productive.', '2026-07-15 10:00:00', 'emma@gmail.com', 'technology'),
('Morning Routines for Success', 'The way you start your morning sets the tone for the entire day.', 'How changing my morning routine transformed my productivity.', '2026-07-16 09:00:00', 'emma@gmail.com', 'skill'),
('Playlists for Deep Focus Work', 'As a remote worker and coffee addict, I need music to maintain focus.', 'Curated music playlists that help me concentrate.', '2026-07-17 11:00:00', 'emma@gmail.com', 'music'),
('Why TypeScript is the Future', 'After working with both JavaScript and TypeScript, I am convinced TypeScript is the future.', 'Exploring the benefits of TypeScript over JavaScript.', '2026-07-14 10:00:00', 'alex@gmail.com', 'technology'),
('Git Workflow Best Practices', 'A good Git workflow is essential for any team.', 'Team collaboration strategies using Git branching and merging.', '2026-07-15 10:00:00', 'alex@gmail.com', 'skill'),
('From Bootcamp to Senior Developer', 'Five years ago, I was working an entry-level job in a completely different field.', 'My 5-year journey from coding bootcamp to senior software engineer.', '2026-07-16 10:00:00', 'alex@gmail.com', 'story'),
('Coding to Lo-Fi Beats', 'There is something magical about coding with lo-fi beats playing in the background.', 'Why lo-fi hip hop has become the soundtrack of modern programming.', '2026-07-17 10:00:00', 'alex@gmail.com', 'music'),
('Getting Started with Ableton Live', 'Ableton Live is one of the most popular DAWs for music production.', 'A beginner\'s guide to Ableton Live.', '2026-07-14 09:00:00', 'lisa@gmail.com', 'technology'),
('Mixing Vocals Like a Pro', 'Achieving a clean, professional vocal mix is the gold standard in music production.', 'Essential techniques for achieving clean, professional vocal mixes.', '2026-07-15 09:00:00', 'lisa@gmail.com', 'skill'),
('My First Year in the Music Industry', 'My first year as a music producer was a whirlwind of emotions.', 'Lessons learned and challenges faced as a new music producer.', '2026-07-16 09:00:00', 'lisa@gmail.com', 'story'),
('The Evolution of Electronic Music', 'Electronic music has come a long way since its origins in the 1970s.', 'Tracing the history of electronic music from the 70s to today.', '2026-07-17 09:00:00', 'lisa@gmail.com', 'music'),
('Platform Updates and New Features', 'We are excited to announce the latest updates to our blogging platform.', 'Announcing the latest improvements to our platform.', '2026-07-18 08:00:00', 'admin@admin.com', 'technology'),
('Community Guidelines', 'We believe in building a vibrant and inclusive community.', 'Important rules and best practices for all community members.', '2026-07-18 10:00:00', 'admin@admin.com', 'skill'),
('Welcome to BloggerZ', 'Welcome to BloggerZ! This platform was born from a simple idea.', 'The story behind BloggerZ and our mission.', '2026-07-18 14:00:00', 'admin@admin.com', 'story'),
('Background Music for Writing', 'Finding the right music to write to can make all the difference.', 'Curated collection of ambient tracks for focused writing.', '2026-07-19 10:00:00', 'admin@admin.com', 'music'),
('Tips for New Bloggers', 'Starting a blog can be both exciting and overwhelming.', 'Essential advice for anyone starting their blogging journey.', '2026-07-20 10:00:00', 'admin@admin.com', 'skill');
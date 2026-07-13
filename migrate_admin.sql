-- Migration: Thêm cột VaiTro và tài khoản Admin
-- Chạy file này trên database blog_riel nếu database đã tồn tại

ALTER TABLE `NguoiDung` ADD COLUMN `VaiTro` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'user' AFTER `DuoiAnhAvatar`;

UPDATE `NguoiDung` SET `VaiTro` = 'user' WHERE `VaiTro` IS NULL;

INSERT INTO `NguoiDung` (`Email`, `HoTenNguoiDung`, `TenDangNhap`, `MatKhau`, `MoTa`, `VaiTro`)
VALUES ('admin@admin.com', 'Admin', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Quản trị viên hệ thống.', 'admin')
ON DUPLICATE KEY UPDATE `VaiTro` = 'admin';

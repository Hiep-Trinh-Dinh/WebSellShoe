USE web_ban_giay;

-- Tạm thời tắt kiểm tra khóa ngoại
SET FOREIGN_KEY_CHECKS = 0;

-- Truncate theo thứ tự đúng: từ bảng con đến bảng cha
TRUNCATE TABLE ChiTietHoaDon;      -- Bảng con của HoaDon và Giay
TRUNCATE TABLE ChiTietPhieuNhap;   -- Bảng con của PhieuNhap và Giay
TRUNCATE TABLE HoaDon;             -- Bảng con của TaiKhoan
TRUNCATE TABLE PhieuNhap;          -- Bảng con của NhaCungCap và TaiKhoan
TRUNCATE TABLE Giay;               -- Bảng con của LoaiGiay
TRUNCATE TABLE TaiKhoan;           -- Bảng con của Quyen
TRUNCATE TABLE LoaiGiay;
TRUNCATE TABLE NhaCungCap;
TRUNCATE TABLE Quyen;

-- Bật lại kiểm tra khóa ngoại
SET FOREIGN_KEY_CHECKS = 1;

-- Phần còn lại giữ nguyên
-- Thêm dữ liệu cho bảng Quyen
INSERT INTO Quyen (tenQuyen) VALUES
('Admin'),
('User');

-- Thêm dữ liệu cho bảng TaiKhoan
INSERT INTO TaiKhoan (tenTK, matKhau, maQuyen, trangThai) VALUES
('admin', '123456', 1, 1),
('user1', '123456', 2, 1),
('user2', '123456', 2, 1),
('user3', '123456', 2, 0); -- Tài khoản bị khóa

-- Thêm dữ liệu cho bảng NhaCungCap
INSERT INTO NhaCungCap (tenNCC, email, diaChi) VALUES
('Nike Vietnam', 'nike@vietnam.com', 'Ho Chi Minh City'),
('Adidas Vietnam', 'adidas@vietnam.com', 'Ha Noi'),
('Biti''s', 'bitis@vietnam.com', 'Ho Chi Minh City'),
('Converse', 'converse@vietnam.com', 'Da Nang'),
('Vans', 'vans@vietnam.com', 'Ho Chi Minh City');

-- Thêm dữ liệu cho bảng LoaiGiay
INSERT INTO LoaiGiay (tenLoaiGiay, trangThai) VALUES
('Giày thể thao', 1),
('Giày da', 1),
('Giày sandal', 1),
('Giày cao gót', 1),
('Giày boots', 1),
('Giày sneaker', 1);

-- Thêm dữ liệu cho bảng Giay
INSERT INTO Giay (tenGiay, maLoaiGiay, size, giaBan, tonKho, trangThai) VALUES
('Nike Air Max', 1, 42, 2500000, 50, 1),
('Adidas Ultraboost', 1, 41, 3000000, 30, 1),
('Clarks Oxford', 2, 43, 1800000, 20, 1),
('Sandal Biti''s', 3, 40, 350000, 100, 1),
('Giày cao gót Charles & Keith', 4, 38, 1200000, 25, 1),
('Nike Air Jordan', 1, 43, 4500000, 15, 1),
('Converse Chuck Taylor', 6, 39, 1500000, 40, 1),
('Vans Old Skool', 6, 40, 1700000, 35, 1),
('Dr. Martens 1460', 5, 42, 3800000, 10, 1),
('Adidas Stan Smith', 6, 38, 1900000, 30, 1),
('Nike Air Force 1', 6, 41, 2200000, 25, 1),
('Biti''s Hunter X', 1, 42, 850000, 60, 1);

-- Thêm dữ liệu cho bảng PhieuNhap
INSERT INTO PhieuNhap (ngayNhap, tongSoLuong, tongTien, maNCC, maTK, trangThai) VALUES
('2024-03-20 10:00:00', 100, 250000000, 1, 1, 1),
('2024-03-21 14:30:00', 150, 450000000, 2, 1, 1),
('2024-03-22 09:15:00', 200, 70000000, 3, 1, 1),
('2024-04-01 11:00:00', 80, 160000000, 4, 1, 1),
('2024-04-05 13:45:00', 120, 230000000, 5, 1, 1);

-- Thêm dữ liệu cho bảng ChiTietPhieuNhap
INSERT INTO ChiTietPhieuNhap (maPN, maGiay, donGia, soLuong, thanhTien) VALUES
(1, 1, 2000000, 50, 100000000),
(1, 2, 2500000, 50, 125000000),
(2, 2, 2500000, 100, 250000000),
(2, 3, 1500000, 50, 75000000),
(3, 4, 300000, 150, 45000000),
(3, 5, 1000000, 50, 50000000),
(4, 6, 3800000, 15, 57000000),
(4, 7, 1200000, 40, 48000000),
(4, 8, 1400000, 25, 35000000),
(5, 9, 3200000, 10, 32000000),
(5, 10, 1500000, 30, 45000000),
(5, 11, 1800000, 25, 45000000),
(5, 12, 700000, 60, 42000000);

-- Thêm dữ liệu cho bảng HoaDon
INSERT INTO HoaDon (ngayTao, tongSoLuong, tongTien, maTK, thanhToan, diaChi, trangThai) VALUES
('2024-03-20 11:00:00', 2, 5500000, 2, 1, 'Ho Chi Minh City', 1),
('2024-03-21 15:30:00', 3, 6600000, 2, 1, 'Ho Chi Minh City', 1),
('2024-03-22 10:15:00', 1, 350000, 2, 1, 'Ho Chi Minh City', 1),
('2024-04-02 14:20:00', 2, 6000000, 3, 1, 'Ha Noi', 1),
('2024-04-05 16:45:00', 3, 5100000, 3, 1, 'Ha Noi', 0),
('2024-04-10 09:30:00', 1, 2500000, 2, 0, 'Da Nang', 0);

-- Thêm dữ liệu cho bảng ChiTietHoaDon
INSERT INTO ChiTietHoaDon (maHD, maGiay, size, giaBan, soLuong, thanhTien) VALUES
(1, 1, 42, 2500000, 1, 2500000),
(1, 2, 41, 3000000, 1, 3000000),
(2, 2, 41, 3000000, 2, 6000000),
(2, 3, 43, 1800000, 1, 1800000),
(3, 4, 40, 350000, 1, 350000),
(4, 6, 43, 4500000, 1, 4500000),
(4, 7, 39, 1500000, 1, 1500000),
(5, 8, 40, 1700000, 3, 5100000),
(6, 1, 42, 2500000, 1, 2500000);

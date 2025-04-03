USE web_ban_giay;

-- Thêm dữ liệu cho bảng Quyen
INSERT INTO Quyen (tenQuyen) VALUES
('Admin'),
('User');

-- Thêm dữ liệu cho bảng TaiKhoan
INSERT INTO TaiKhoan (tenTK, matKhau, maQuyen, trangThai) VALUES
('admin', '123456', 1, 1),
('user', '123456', 2, 1);

-- Thêm dữ liệu cho bảng LoaiGiay
INSERT INTO LoaiGiay (tenLoaiGiay, trangThai) VALUES
('Giày thể thao', 1),
('Giày da', 1),
('Giày sandal', 1),
('Giày cao gót', 1);

-- Thêm dữ liệu cho bảng Giay
INSERT INTO Giay (tenGiay, maLoaiGiay, size, giaBan, tonKho, trangThai) VALUES
('Nike Air Max', 1, 42, 2500000, 50, 1),
('Adidas Ultraboost', 1, 41, 3000000, 30, 1),
('Clarks Oxford', 2, 43, 1800000, 20, 1),
('Sandal Biti''s', 3, 40, 350000, 100, 1),
('Adidas Ultraboost', 1, 41, 3000000, 30, 1),
('Clarks Oxford', 2, 43, 1800000, 20, 1),
('Sandal Biti''s', 3, 40, 350000, 100, 1),
('Giày cao gót Charles & Keith', 4, 38, 1200000, 25, 1);

-- Thêm dữ liệu cho bảng NhaCungCap
INSERT INTO NhaCungCap (tenNCC, email, diaChi) VALUES
('Nike Vietnam', 'nike@vietnam.com', 'Ho Chi Minh City'),
('Adidas Vietnam', 'adidas@vietnam.com', 'Ha Noi'),
('Biti''s', 'bitis@vietnam.com', 'Ho Chi Minh City');

-- Thêm dữ liệu cho bảng PhieuNhap
INSERT INTO PhieuNhap (ngayNhap, tongSoLuong, tongTien, maNCC, maTK, trangThai) VALUES
('2024-03-20 10:00:00', 100, 250000000, 1, 1, 1),
('2024-03-21 14:30:00', 150, 450000000, 2, 1, 1),
('2024-03-22 09:15:00', 200, 70000000, 3, 1, 1);

-- Thêm dữ liệu cho bảng ChiTietPhieuNhap
INSERT INTO ChiTietPhieuNhap (maPN, maGiay, donGia, soLuong, thanhTien) VALUES
(1, 1, 2000000, 50, 100000000),
(1, 2, 2500000, 50, 125000000),
(2, 2, 2500000, 100, 250000000),
(2, 3, 1500000, 50, 75000000),
(3, 4, 300000, 150, 45000000),
(3, 5, 1000000, 50, 50000000);

-- Thêm dữ liệu cho bảng HoaDon
INSERT INTO HoaDon (ngayTao, tongSoLuong, tongTien, maTK, trangThai) VALUES
('2024-03-20 11:00:00', 2, 5500000, 2, 1),
('2024-03-21 15:30:00', 3, 6600000, 2, 1),
('2024-03-22 10:15:00', 1, 350000, 2, 1);

-- Thêm dữ liệu cho bảng ChiTietHoaDon
INSERT INTO ChiTietHoaDon (maHD, maGiay, giaBan, soLuong, thanhTien) VALUES
(1, 1, 2500000, 1, 2500000),
(1, 2, 3000000, 1, 3000000),
(2, 2, 3000000, 2, 6000000),
(2, 3, 1800000, 1, 1800000),
(3, 4, 350000, 1, 350000); 
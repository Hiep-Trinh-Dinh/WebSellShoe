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
INSERT INTO LoaiGiay (tenLoaiGiay) VALUES
('Giày thể thao', 1),
('Giày da', 1),
('Giày sandal', 1),
('Giày cao gót', 1);

-- Thêm dữ liệu cho bảng Giay
INSERT INTO Giay (tenGiay, maLoaiGiay, size, giaBan, tonKho) VALUES
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
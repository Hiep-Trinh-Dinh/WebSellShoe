DROP DATABASE IF EXISTS web_ban_giay;
CREATE DATABASE web_ban_giay;
USE web_ban_giay;

/* DATA TABLE */
DROP TABLE IF EXISTS ChiTietHoaDon;
DROP TABLE IF EXISTS HoaDon;
DROP TABLE IF EXISTS ChiTietPhieuNhap;
DROP TABLE IF EXISTS PhieuNhap;
DROP TABLE IF EXISTS Giay;
DROP TABLE IF EXISTS LoaiGiay;
DROP TABLE IF EXISTS NhaCungCap;
DROP TABLE IF EXISTS TaiKhoan;
DROP TABLE IF EXISTS Quyen;

CREATE TABLE IF NOT EXISTS Quyen (
	maQuyen INT AUTO_INCREMENT PRIMARY KEY,
    tenQuyen VARCHAR(50)
);

/* Thêm dữ liệu mẫu cho bảng Quyen */
INSERT INTO Quyen (maQuyen, tenQuyen) VALUES 
(1, 'Admin'),
(2, 'Khách hàng');

CREATE TABLE IF NOT EXISTS TaiKhoan (
    maTK INT AUTO_INCREMENT PRIMARY KEY,
    tenTK VARCHAR(50),
    matKhau VARCHAR(50),
    hoTen VARCHAR(50),
    email VARCHAR(50),
    soDienThoai VARCHAR(50),
    diaChi VARCHAR(50),
    maQuyen INT,
    trangThai INT DEFAULT 1,
    FOREIGN KEY (maQuyen) REFERENCES Quyen(maQuyen) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS NhaCungCap (
    maNCC INT AUTO_INCREMENT PRIMARY KEY,
    tenNCC VARCHAR(50),
    email VARCHAR(50),
    diaChi VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS LoaiGiay (
    maLoaiGiay INT AUTO_INCREMENT PRIMARY KEY,
    tenLoaiGiay VARCHAR(50),
    trangThai INT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS Giay (
    maGiay INT AUTO_INCREMENT PRIMARY KEY,
    tenGiay VARCHAR(50),
    maLoaiGiay INT,
    size INT,
    giaBan INT,
    tonKho INT,
    hinhAnh LONGBLOB,
    trangThai INT DEFAULT 1,
    FOREIGN KEY (maLoaiGiay) REFERENCES LoaiGiay(maLoaiGiay)
);

CREATE TABLE IF NOT EXISTS PhieuNhap (
    maPN INT AUTO_INCREMENT PRIMARY KEY,
    ngayNhap DATETIME,
    tongSoLuong INT,
    tongTien INT,
    maNCC INT,
    maTK INT,
    trangThai INT,
    FOREIGN KEY (maNCC) REFERENCES NhaCungCap(maNCC),
    FOREIGN KEY (maTK) REFERENCES TaiKhoan(maTK)
);

CREATE TABLE IF NOT EXISTS ChiTietPhieuNhap (
    maPN INT,
    maGiay INT,
    donGia INT,
    soLuong INT,
    thanhTien INT,
    PRIMARY KEY (maPN, maGiay),
    FOREIGN KEY (maPN) REFERENCES PhieuNhap(maPN),
    FOREIGN KEY (maGiay) REFERENCES Giay(maGiay)
);

CREATE TABLE IF NOT EXISTS HoaDon (
    maHD INT AUTO_INCREMENT PRIMARY KEY,
    ngayTao DATETIME,
    tongSoLuong INT,
    tongTien INT,
    maTK INT,
    thanhToan INT,
    diaChi VARCHAR(255), 
    trangThai INT,
    FOREIGN KEY (maTK) REFERENCES TaiKhoan(maTK)
);

CREATE TABLE IF NOT EXISTS ChiTietHoaDon (
    maHD INT,
    maGiay INT,
    size INT,
    giaBan INT,
    soLuong INT,
    thanhTien INT,
    PRIMARY KEY (maHD, maGiay),
    FOREIGN KEY (maHD) REFERENCES HoaDon(maHD),
    FOREIGN KEY (maGiay) REFERENCES Giay(maGiay)
);
















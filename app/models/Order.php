<?php
class Order extends BaseModel {
    protected $table = 'HoaDon';

    public function getAll() {
        $sql = "SELECT hd.*, tk.tenTK 
                FROM HoaDon hd 
                LEFT JOIN TaiKhoan tk ON hd.maTK = tk.maTK 
                ORDER BY hd.ngayTao DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT hd.*, tk.tenTK, tk.email, tk.soDT, tk.diaChi 
                FROM HoaDon hd 
                LEFT JOIN TaiKhoan tk ON hd.maTK = tk.maTK 
                WHERE hd.maHD = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrderDetails($orderId) {
        $sql = "SELECT ct.*, g.tenGiay, g.size 
                FROM ChiTietHoaDon ct 
                LEFT JOIN Giay g ON ct.maGiay = g.maGiay 
                WHERE ct.maHD = :orderId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':orderId', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE {$this->table} SET trangThai = :status WHERE maHD = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);
    }

    public function getRecentOrders($limit = 5) {
        $sql = "SELECT hd.*, tk.tenTK 
                FROM HoaDon hd 
                LEFT JOIN TaiKhoan tk ON hd.maTK = tk.maTK 
                ORDER BY hd.ngayTao DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
?> 
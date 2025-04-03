<?php
class Order extends BaseModel {
    protected $table = 'HoaDon';

    // Thuộc tính
    protected $maHD;
    protected $maTK;
    protected $ngayTao;
    protected $tongTien;
    protected $trangThai;
    protected $items; // Cho các chi tiết đơn hàng

    // Getters
    public function getMaHD() { return $this->maHD; }
    public function getMaTK() { return $this->maTK; }
    public function getNgayTao() { return $this->ngayTao; }
    public function getTongTien() { return $this->tongTien; }
    public function getTrangThai() { return $this->trangThai; }
    public function getItems() { return $this->items; }

    // Setters
    public function setMaHD($value) { $this->maHD = $value; }
    public function setMaTK($value) { $this->maTK = $value; }
    public function setNgayTao($value) { $this->ngayTao = $value; }
    public function setTongTien($value) { $this->tongTien = $value; }
    public function setTrangThai($value) { $this->trangThai = $value; }
    public function setItems($value) { $this->items = $value; }

    public function mapFromArray($data) {
        $this->maHD = $data['maHD'] ?? null;
        $this->maTK = $data['maTK'] ?? null;
        $this->ngayTao = $data['ngayTao'] ?? null;
        $this->tongTien = $data['tongTien'] ?? null;
        $this->trangThai = $data['trangThai'] ?? null;
        $this->items = $data['items'] ?? [];
    }

    public function getAll() {
        try {
            $sql = "SELECT hd.*, tk.tenTK 
                    FROM HoaDon hd
                    LEFT JOIN TaiKhoan tk ON hd.maTK = tk.maTK
                    ORDER BY hd.ngayTao DESC";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Thêm logging để debug
            error_log("Orders data: " . print_r($orders, true));
            
            return $orders;
        } catch (PDOException $e) {
            error_log("Error in getAll: " . $e->getMessage());
            return [];
        }
    }

    public function getById($id) {
        $sql = "SELECT hd.*, tk.tenTK, tk.email, tk.soDT, tk.diaChi 
                FROM HoaDon hd 
                LEFT JOIN TaiKhoan tk ON hd.maTK = tk.maTK 
                WHERE hd.maHD = :id";
        return $this->db->query($sql, ['id' => $id])[0] ?? null;
    }

    public function getOrderDetails($orderId) {
        $sql = "SELECT cthd.*, g.tenGiay 
                FROM ChiTietHoaDon cthd 
                JOIN Giay g ON cthd.maGiay = g.maGiay 
                WHERE cthd.maHD = :orderId";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderWithDetails($orderId) {
        try {
            error_log("=== Starting getOrderWithDetails ===");
            error_log("Order ID: " . $orderId);
            
            // Kiểm tra xem đơn hàng có tồn tại không
            $checkSql = "SELECT COUNT(*) as count FROM HoaDon WHERE maHD = :orderId";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->bindValue(':orderId', $orderId, PDO::PARAM_INT);
            $checkStmt->execute();
            $count = $checkStmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            error_log("Order exists in database: " . ($count > 0 ? 'Yes' : 'No'));
            
            if ($count == 0) {
                error_log("Order not found in database");
                return null;
            }
            
            // Lấy thông tin đơn hàng và thông tin khách hàng
            $sql = "SELECT 
                        hd.maHD,
                        hd.ngayTao,
                        hd.tongSoLuong,
                        hd.tongTien,
                        hd.trangThai,
                        hd.maTK,
                        tk.tenTK,
                        tk.email,
                        tk.soDT,
                        tk.diaChi
                    FROM HoaDon hd
                    LEFT JOIN TaiKhoan tk ON hd.maTK = tk.maTK
                    WHERE hd.maHD = :orderId";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':orderId', $orderId, PDO::PARAM_INT);
            $stmt->execute();

            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            
            error_log("Order data: " . print_r($order, true));
            
            if ($order) {
                // Lấy chi tiết đơn hàng
                $sql = "SELECT 
                            cthd.maGiay,
                            cthd.giaBan,
                            cthd.soLuong,
                            cthd.thanhTien,
                            g.tenGiay,
                            g.size
                        FROM ChiTietHoaDon cthd
                        JOIN Giay g ON cthd.maGiay = g.maGiay
                        WHERE cthd.maHD = :orderId";
                        
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':orderId', $orderId, PDO::PARAM_INT);
                $stmt->execute();
                
                $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                error_log("Order items: " . print_r($order['items'], true));
            } else {
                error_log("Failed to fetch order details");
            }
            
            error_log("=== End getOrderWithDetails ===");
            return $order;
        } catch (PDOException $e) {
            error_log("Error in getOrderWithDetails: " . $e->getMessage());
            error_log("SQL Error Code: " . $e->getCode());
            error_log("SQL Error Info: " . print_r($stmt->errorInfo(), true));
            return null;
        }
    }

    public function updateStatus($id, $status) {
        try {
            $sql = "UPDATE HoaDon SET trangThai = :status WHERE maHD = :id";
            error_log("Executing SQL: " . $sql . " with id: " . $id . ", status: " . $status);
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':status', $status, PDO::PARAM_INT);
            
            $result = $stmt->execute();
            error_log("Update result: " . ($result ? "success" : "failed"));
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error in updateStatus: " . $e->getMessage());
            return false;
        }
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

    public function getOrdersByUserId($userId) {
        try {
            // Lấy thông tin đơn hàng
            $sql = "SELECT hd.*, 
                          GROUP_CONCAT(cthd.maGiay) as maGiays,
                          GROUP_CONCAT(cthd.soLuong) as soLuongs,
                          GROUP_CONCAT(cthd.giaBan) as giaBans,
                          GROUP_CONCAT(g.tenGiay) as tenGiays,
                          GROUP_CONCAT(g.hinhAnh) as hinhAnhs
                   FROM HoaDon hd
                   LEFT JOIN ChiTietHoaDon cthd ON hd.maHD = cthd.maHD
                   LEFT JOIN Giay g ON cthd.maGiay = g.maGiay
                   WHERE hd.maTK = :userId
                   GROUP BY hd.maHD
                   ORDER BY hd.ngayTao DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            $orders = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $order = new Order();
                
                // Xử lý thông tin cơ bản của đơn hàng
                $orderData = [
                    'maHD' => $row['maHD'],
                    'maTK' => $row['maTK'],
                    'ngayTao' => $row['ngayTao'],
                    'tongTien' => $row['tongTien'],
                    'trangThai' => $row['trangThai']
                ];

                // Xử lý chi tiết đơn hàng
                $items = [];
                if ($row['maGiays']) {
                    $maGiays = explode(',', $row['maGiays']);
                    $soLuongs = explode(',', $row['soLuongs']);
                    $giaBans = explode(',', $row['giaBans']);
                    $tenGiays = explode(',', $row['tenGiays']);
                    $hinhAnhs = explode(',', $row['hinhAnhs']);

                    for ($i = 0; $i < count($maGiays); $i++) {
                        $items[] = [
                            'maGiay' => $maGiays[$i],
                            'soLuong' => $soLuongs[$i],
                            'giaBan' => $giaBans[$i],
                            'tenGiay' => $tenGiays[$i],
                            'hinhAnh' => $hinhAnhs[$i]
                        ];
                    }
                }
                
                $orderData['items'] = $items;
                $order->mapFromArray($orderData);
                $orders[] = $order;
            }
            
            return $orders;
            
        } catch (PDOException $e) {
            error_log("Error in getOrdersByUserId: " . $e->getMessage());
            return [];
        }
    }

    public function getOrderById($id) {
        try {
            $sql = "SELECT hd.*, tk.tenTK 
                    FROM HoaDon hd 
                    JOIN TaiKhoan tk ON hd.maTK = tk.maTK 
                    WHERE hd.maHD = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Log kết quả
            error_log("Order query result: " . print_r($result, true));
            
            return $result;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
}
?> 
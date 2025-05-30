<?php
class Order extends BaseModel {
    protected $table = 'HoaDon';

    // Thuộc tính
    protected $maHD;
    protected $maTK;
    protected $ngayTao;
    protected $tongTien;
    protected $thanhToan;
    protected $diaChi;
    protected $trangThai;
    protected $items; // Cho các chi tiết đơn hàng

    // Getters
    public function getMaHD() { return $this->maHD; }
    public function getMaTK() { return $this->maTK; }
    public function getNgayTao() { return $this->ngayTao; }
    public function getTongTien() { return $this->tongTien; }
    public function getThanhToan() { return $this->thanhToan; }
    public function getDiaChi() { return $this->diaChi; }
    public function getTrangThai() { return $this->trangThai; }
    public function getItems() { return $this->items; }

    // Setters
    public function setMaHD($value) { $this->maHD = $value; }
    public function setMaTK($value) { $this->maTK = $value; }
    public function setNgayTao($value) { $this->ngayTao = $value; }
    public function setTongTien($value) { $this->tongTien = $value; }
    public function setThanhToan($value) { $this->thanhToan = $value; }
    public function setDiaChi($value) { $this->diaChi = $value; }
    public function setTrangThai($value) { $this->trangThai = $value; }
    public function setItems($value) { $this->items = $value; }

    public function mapFromArray($data) {
        $this->maHD = $data['maHD'] ?? null;
        $this->maTK = $data['maTK'] ?? null;
        $this->ngayTao = $data['ngayTao'] ?? null;
        $this->tongTien = $data['tongTien'] ?? null;
        $this->thanhToan = $data['thanhToan'] ?? null;
        $this->diaChi = $data['diaChi'] ?? null;
        $this->trangThai = $data['trangThai'] ?? null;
        $this->items = $data['items'] ?? [];
    }

    public function getAll() {
        try {
            $sql = "SELECT hd.*, tk.tenTK 
                    FROM HoaDon hd
                    LEFT JOIN TaiKhoan tk ON hd.maTK = tk.maTK
                    ORDER BY DATE(hd.ngayTao) DESC, hd.maHD ASC";
                    
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
        try {
            $sql = "SELECT 
                        cthd.*,
                        g.tenGiay,
                        g.size
                    FROM ChiTietHoaDon cthd
                    LEFT JOIN Giay g ON cthd.maGiay = g.maGiay
                    WHERE cthd.maHD = :orderId";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error in getOrderDetails: " . $e->getMessage());
            return [];
        }
    }

    public function getOrderWithDetails($orderId) {
        try {
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
            
            if (!$order) {
                return null;
            }

            // Lấy chi tiết sản phẩm trong đơn hàng
            $detailSql = "SELECT 
                            cthd.maGiay,
                            cthd.soLuong,
                            cthd.giaBan,
                            g.tenGiay,
                            g.hinhAnh
                        FROM ChiTietHoaDon cthd
                        LEFT JOIN Giay g ON cthd.maGiay = g.maGiay
                        WHERE cthd.maHD = :orderId";
                        
            $detailStmt = $this->db->prepare($detailSql);
            $detailStmt->bindValue(':orderId', $orderId, PDO::PARAM_INT);
            $detailStmt->execute();
            $order['chiTiet'] = $detailStmt->fetchAll(PDO::FETCH_ASSOC);

            return $order;
        } catch (PDOException $e) {
            error_log("Error in getOrderWithDetails: " . $e->getMessage());
            return null;
        }
    }

    public function createHD($formDataHD) {
        extract($formDataHD);
        $sql = "INSERT INTO HoaDon (ngayTao, tongSoLuong, tongTien, maTK, trangThai, thanhToan, diaChi, soDienThoai) 
                VALUES (:ngayTao, :tongSoLuong, :tongTien, :maTK, :trangThai, :thanhToan, :diaChi, :soDienThoai)";
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            ':ngayTao' => $ngayTao,
            ':tongSoLuong' => $tongSoLuong, 
            ':tongTien' => $tongTien, 
            ':maTK' => $maTK,
            ':trangThai' => $trangThai, 
            ':thanhToan' => $thanhToan,
            ':diaChi' => $diaChi,
            ':soDienThoai' => $soDienThoai ?? null,        
        ]);
        return $success ? $this->db->lastInsertId() : false;
    }

    

    public function createCTHD($formData) {
        extract($formData);
        $sql = "INSERT INTO ChiTietHoaDon (maHD, maGiay, size, giaBan, soLuong, thanhTien) 
                VALUES (:maHD, :maGiay, :size, :giaBan, :soLuong, :thanhTien)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':maHD' => $maHD, 
            ':maGiay' => $maGiay, 
            ':size' => $size, 
            ':giaBan' => $giaBan, 
            ':soLuong' => $soLuong, 
            ':thanhTien' => $thanhTien      
        ]);
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
        try {
            $sql = "SELECT hd.*, tk.tenTK,
                    CASE 
                        WHEN hd.trangThai = 1 THEN 'Đang xử lý'
                        WHEN hd.trangThai = 2 THEN 'Đang giao hàng'
                        WHEN hd.trangThai = 3 THEN 'Đã giao hàng'
                        WHEN hd.trangThai = 4 THEN 'Đã hủy'
                        ELSE 'Không xác định'
                    END as trangThaiText
                    FROM HoaDon hd 
                    LEFT JOIN TaiKhoan tk ON hd.maTK = tk.maTK 
                    ORDER BY hd.ngayTao DESC 
                    LIMIT :limit";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getRecentOrders: " . $e->getMessage());
            return [];
        }
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
                if (!empty($row['maGiays'])) {
                    $maGiays = explode(',', $row['maGiays']);
                    $soLuongs = explode(',', $row['soLuongs'] ?? '');
                    $giaBans = explode(',', $row['giaBans'] ?? '');
                    $tenGiays = explode(',', $row['tenGiays'] ?? '');
                    $hinhAnhs = explode(',', $row['hinhAnhs'] ?? '');

                    for ($i = 0; $i < count($maGiays); $i++) {
                        $items[] = [
                            'maGiay' => $maGiays[$i] ?? '',
                            'soLuong' => isset($soLuongs[$i]) ? $soLuongs[$i] : '',
                            'giaBan' => isset($giaBans[$i]) ? $giaBans[$i] : '',
                            'tenGiay' => isset($tenGiays[$i]) ? $tenGiays[$i] : '',
                            'hinhAnh' => isset($hinhAnhs[$i]) ? $hinhAnhs[$i] : ''
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

    public function getOrderById($orderId) {
        try {
            $sql = "SELECT 
                        hd.*,
                        tk.tenTK
                    FROM HoaDon hd
                    LEFT JOIN TaiKhoan tk ON hd.maTK = tk.maTK
                    WHERE hd.maHD = :orderId";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error in getOrderById: " . $e->getMessage());
            return null;
        }
    }

    public function searchOrdersByDate($startDate, $endDate, $page = 1, $limit = 5) {
        try {
            // Đếm tổng số đơn hàng
            $countSql = "SELECT COUNT(*) as total FROM HoaDon 
                         WHERE DATE(ngayTao) BETWEEN :startDate AND :endDate";
            $stmt = $this->db->prepare($countSql);
            $stmt->execute([
                ':startDate' => $startDate,
                ':endDate' => $endDate
            ]);
            $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Tính tổng số trang
            $totalPages = ceil($total / $limit);
            
            // Lấy đơn hàng theo trang
            $offset = ($page - 1) * $limit;
            $sql = "SELECT hd.*, tk.tenTK 
                    FROM HoaDon hd
                    LEFT JOIN TaiKhoan tk ON hd.maTK = tk.maTK
                    WHERE DATE(hd.ngayTao) BETWEEN :startDate AND :endDate
                    ORDER BY hd.ngayTao DESC
                    LIMIT :limit OFFSET :offset";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':startDate', $startDate);
            $stmt->bindValue(':endDate', $endDate);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return [
                'orders' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                'totalPages' => $totalPages
            ];
        } catch (PDOException $e) {
            error_log("Error in searchOrdersByDate: " . $e->getMessage());
            throw new Exception("Lỗi khi tìm kiếm đơn hàng");
        }
    }

    public function getOrdersWithPagination($page = 1, $limit = 6, $status = null, $sortColumn = 'ngayTao', $sortOrder = 'DESC') {
        try {
            // Tính offset
            $offset = ($page - 1) * $limit;
            
            // Xây dựng điều kiện SQL
            $whereClause = '';
            $params = [];
            
            // Thêm điều kiện lọc theo trạng thái nếu có
            if ($status !== null && $status !== '') {
                $whereClause = ' WHERE hd.trangThai = :status';
                $params[':status'] = $status;
                error_log("Lọc đơn hàng theo trạng thái: $status");
            }
            
            // Xác thực cột sắp xếp để tránh SQL injection
            $allowedColumns = ['maHD', 'ngayTao', 'tongSoLuong', 'tongTien', 'trangThai'];
            if (!in_array($sortColumn, $allowedColumns)) {
                $sortColumn = 'ngayTao'; // Mặc định sắp xếp theo ngày tạo
            }
            
            // Xác thực hướng sắp xếp
            $sortOrder = strtoupper($sortOrder);
            if ($sortOrder != 'ASC' && $sortOrder != 'DESC') {
                $sortOrder = 'DESC'; // Mặc định giảm dần
            }
            
            // Lấy tổng số đơn hàng
            $totalSql = "SELECT COUNT(*) as total FROM HoaDon hd" . $whereClause;
            $totalStmt = $this->db->prepare($totalSql);
            
            // Bind các tham số nếu có
            foreach ($params as $param => $value) {
                $totalStmt->bindValue($param, $value, PDO::PARAM_INT);
            }
            
            $totalStmt->execute();
            $total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Debug số lượng đơn hàng tìm thấy
            error_log("Tổng số đơn hàng: $total" . ($status !== null ? ", trạng thái: $status" : ""));
            
            // Lấy danh sách đơn hàng theo phân trang
            $sql = "SELECT hd.*, tk.tenTK, tk.soDT as soDienThoai 
                    FROM HoaDon hd
                    LEFT JOIN TaiKhoan tk ON hd.maTK = tk.maTK"
                    . $whereClause . 
                   " ORDER BY hd.{$sortColumn} {$sortOrder}, hd.maHD DESC 
                    LIMIT :limit OFFSET :offset";
                    
            error_log("SQL query: $sql");
            $stmt = $this->db->prepare($sql);
            
            // Bind các tham số nếu có
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value, PDO::PARAM_INT);
            }
            
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Debug đơn hàng tìm thấy
            if (!empty($orders)) {
                $orderIds = array_column($orders, 'maHD');
                error_log("Các đơn hàng tìm thấy: " . implode(', ', $orderIds));
            } else {
                error_log("Không tìm thấy đơn hàng nào" . ($status !== null ? " với trạng thái: $status" : ""));
            }
            
            return [
                'orders' => $orders,
                'total' => $total,
                'totalPages' => ceil($total / $limit),
                'currentPage' => $page,
                'status' => $status,
                'sortColumn' => $sortColumn,
                'sortOrder' => $sortOrder
            ];
        } catch (PDOException $e) {
            error_log("Error in getOrdersWithPagination: " . $e->getMessage());
            error_log("SQL state: " . $e->errorInfo[0]);
            error_log("SQL error code: " . $e->errorInfo[1]);
            error_log("SQL error message: " . $e->errorInfo[2]);
            
            return [
                'orders' => [],
                'total' => 0,
                'totalPages' => 1,
                'currentPage' => 1,
                'status' => $status,
                'sortColumn' => $sortColumn,
                'sortOrder' => $sortOrder
            ];
        }
    }

    public function getTopCustomers($month, $year) {
        try {
            $sql = "SELECT 
                        tk.maTK,
                        tk.tenTK,
                        COUNT(DISTINCT hd.maHD) as soLuongDon,
                        COALESCE(SUM(hd.tongTien), 0) as tongChiTieu
                    FROM TaiKhoan tk
                    LEFT JOIN HoaDon hd ON tk.maTK = hd.maTK 
                        AND MONTH(hd.ngayTao) = :month 
                        AND YEAR(hd.ngayTao) = :year
                        AND hd.trangThai != 4 /* Không tính đơn đã hủy */
                    WHERE tk.maQuyen = 2 /* Chỉ lấy tài khoản khách hàng */
                    GROUP BY tk.maTK, tk.tenTK
                    HAVING tongChiTieu > 0
                    ORDER BY tongChiTieu DESC
                    LIMIT 5"; /* Giới hạn 5 người */
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error in getTopCustomers: " . $e->getMessage());
            return [];
        }
    }

    public function getCustomerOrders($userId, $month, $year) {
        try {
            // Lấy thông tin khách hàng và tổng quan về đơn hàng
            $sql = "SELECT 
                        tk.tenTK,
                        COUNT(DISTINCT hd.maHD) as totalOrders,
                        COALESCE(SUM(hd.tongTien), 0) as totalSpent,
                        MAX(hd.ngayTao) as lastOrder
                    FROM TaiKhoan tk
                    LEFT JOIN HoaDon hd ON tk.maTK = hd.maTK 
                        AND MONTH(hd.ngayTao) = :month 
                        AND YEAR(hd.ngayTao) = :year
                        AND hd.trangThai != 4
                    WHERE tk.maTK = :userId
                    GROUP BY tk.maTK, tk.tenTK";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            
            $customerInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$customerInfo) {
                return [
                    'customerName' => 'Không tìm thấy',
                    'orders' => [],
                    'totalSpent' => 0,
                    'totalOrders' => 0,
                    'averageOrder' => 0
                ];
            }

            // Lấy chi tiết từng đơn hàng
            $orderSql = "SELECT 
                            hd.*,
                            COUNT(cthd.maGiay) as soMatHang,
                            GROUP_CONCAT(g.tenGiay SEPARATOR ', ') as danhSachSanPham
                        FROM HoaDon hd
                        LEFT JOIN ChiTietHoaDon cthd ON hd.maHD = cthd.maHD
                        LEFT JOIN Giay g ON cthd.maGiay = g.maGiay
                        WHERE hd.maTK = :userId 
                            AND MONTH(hd.ngayTao) = :month 
                            AND YEAR(hd.ngayTao) = :year
                            AND hd.trangThai != 4
                        GROUP BY hd.maHD
                        ORDER BY hd.ngayTao DESC";
                        
            $orderStmt = $this->db->prepare($orderSql);
            $orderStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $orderStmt->bindParam(':month', $month, PDO::PARAM_INT);
            $orderStmt->bindParam(':year', $year, PDO::PARAM_INT);
            $orderStmt->execute();
            
            $orders = $orderStmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'customerName' => $customerInfo['tenTK'],
                'orders' => $orders,
                'totalSpent' => (int)$customerInfo['totalSpent'],
                'totalOrders' => (int)$customerInfo['totalOrders'],
                'averageOrder' => $customerInfo['totalOrders'] > 0 
                    ? round($customerInfo['totalSpent'] / $customerInfo['totalOrders']) 
                    : 0,
                'lastOrder' => $customerInfo['lastOrder']
            ];
        } catch (PDOException $e) {
            error_log("Error in getCustomerOrders: " . $e->getMessage());
            return [
                'customerName' => '',
                'orders' => [],
                'totalSpent' => 0,
                'totalOrders' => 0,
                'averageOrder' => 0
            ];
        }
    }

    public function getCustomerOrdersByMonth($userId, $month, $year) {
        try {
            // Lấy thông tin khách hàng và tổng quan đơn hàng
            $sql = "SELECT 
                        tk.maTK,
                        tk.tenTK as customerName,
                        COUNT(DISTINCT hd.maHD) as totalOrders,
                        COALESCE(SUM(hd.tongTien), 0) as totalSpent
                    FROM TaiKhoan tk
                    LEFT JOIN HoaDon hd ON tk.maTK = hd.maTK 
                        AND MONTH(hd.ngayTao) = :month 
                        AND YEAR(hd.ngayTao) = :year
                    WHERE tk.maTK = :userId
                    GROUP BY tk.maTK, tk.tenTK";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            
            $summary = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$summary) {
                // Lấy ít nhất thông tin khách hàng
                $sql = "SELECT maTK, tenTK as customerName FROM TaiKhoan WHERE maTK = :userId";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
                $stmt->execute();
                $summary = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$summary) {
                    return null;
                }
                
                $summary['totalOrders'] = 0;
                $summary['totalSpent'] = 0;
            }

            // Tính trung bình chi tiêu/đơn
            $summary['averageOrder'] = $summary['totalOrders'] > 0 
                ? $summary['totalSpent'] / $summary['totalOrders'] 
                : 0;

            // Lấy danh sách đơn hàng
            $sql = "SELECT 
                        hd.maHD,
                        hd.ngayTao,
                        hd.tongTien,
                        hd.trangThai,
                        COUNT(cthd.maGiay) as soMatHang
                    FROM HoaDon hd
                    LEFT JOIN ChiTietHoaDon cthd ON hd.maHD = cthd.maHD
                    WHERE hd.maTK = :userId 
                        AND MONTH(hd.ngayTao) = :month 
                        AND YEAR(hd.ngayTao) = :year
                    GROUP BY hd.maHD, hd.ngayTao, hd.tongTien, hd.trangThai
                    ORDER BY hd.ngayTao DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'customerName' => $summary['customerName'],
                'totalOrders' => $summary['totalOrders'],
                'totalSpent' => $summary['totalSpent'],
                'averageOrder' => $summary['averageOrder'],
                'orders' => $orders
            ];

        } catch (PDOException $e) {
            error_log("Error in getCustomerOrdersByMonth: " . $e->getMessage());
            throw new Exception("Lỗi khi lấy dữ liệu đơn hàng: " . $e->getMessage());
        }
    }

    public function getCustomerMonthlyStats($userId, $month, $year) {
        try {
            $sql = "SELECT 
                        tk.maTK,
                        tk.tenTK,
                        COUNT(DISTINCT hd.maHD) as soLuongDon,
                        COALESCE(SUM(hd.tongTien), 0) as tongChiTieu
                    FROM TaiKhoan tk
                    LEFT JOIN HoaDon hd ON tk.maTK = hd.maTK 
                        AND MONTH(hd.ngayTao) = :month 
                        AND YEAR(hd.ngayTao) = :year
                    WHERE tk.maTK = :userId
                    GROUP BY tk.maTK, tk.tenTK";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getCustomerMonthlyStats: " . $e->getMessage());
            throw new Exception("Lỗi khi lấy thông tin thống kê");
        }
    }

    public function getCustomerMonthlyOrdersWithDetails($userId, $month, $year) {
        try {
            // Debug để kiểm tra tham số
            error_log("Getting orders for user: $userId, month: $month, year: $year");

            // Lấy danh sách đơn hàng
            $sql = "SELECT hd.*
                    FROM HoaDon hd
                    WHERE hd.maTK = :userId 
                    AND MONTH(hd.ngayTao) = :month 
                    AND YEAR(hd.ngayTao) = :year
                    ORDER BY hd.ngayTao DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Debug để kiểm tra kết quả
            error_log("Found " . count($orders) . " orders");
            
            // Nếu không có đơn hàng, trả về mảng rỗng
            if (empty($orders)) {
                return [];
            }

            // Lấy chi tiết từng đơn hàng
            foreach ($orders as &$order) {
                $sql = "SELECT cthd.*, g.tenGiay, g.hinhAnh
                        FROM ChiTietHoaDon cthd
                        JOIN Giay g ON cthd.maGiay = g.maGiay
                        WHERE cthd.maHD = :orderId";

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':orderId', $order['maHD'], PDO::PARAM_INT);
                $stmt->execute();
                $order['products'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            return $orders;
        } catch (PDOException $e) {
            error_log("Error in getCustomerMonthlyOrdersWithDetails: " . $e->getMessage());
            error_log("SQL: " . $sql);
            throw new Exception("Lỗi khi lấy chi tiết đơn hàng: " . $e->getMessage());
        }
    }

    public function getTotalOrders() {
        try {
            $sql = "SELECT COUNT(*) as total FROM HoaDon WHERE trangThai != 5"; // Không đếm đơn đã hủy
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error in getTotalOrders: " . $e->getMessage());
            return 0;
        }
    }

    public function getOrdersByMonth($month, $year) {
        try {
            $sql = "SELECT hd.*, tk.tenTK, tk.email, tk.soDT, tk.diaChi
                    FROM HoaDon hd
                    JOIN TaiKhoan tk ON hd.maTK = tk.maTK
                    WHERE MONTH(hd.ngayTao) = :month 
                    AND YEAR(hd.ngayTao) = :year
                    ORDER BY hd.ngayTao DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getOrdersByMonth: " . $e->getMessage());
            throw new Exception("Lỗi khi lấy danh sách đơn hàng");
        }
    }

    public function getMonthlyStatistics($month, $year) {
        try {
            // Lấy tổng số đơn hàng
            $sql = "SELECT COUNT(*) as total_orders FROM HoaDon 
                    WHERE MONTH(ngayTao) = :month 
                    AND YEAR(ngayTao) = :year";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            $totalOrders = $stmt->fetch(PDO::FETCH_ASSOC)['total_orders'];

            // Lấy tổng số người dùng
            $sql = "SELECT COUNT(*) as total_users FROM TaiKhoan WHERE maQuyen = 2";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

            // Lấy danh sách sản phẩm sắp hết hàng
            $sql = "SELECT * FROM Giay WHERE soLuongTon <= 5";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $lowStock = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Lấy tổng doanh thu
            $sql = "SELECT SUM(tongTien) as total_revenue FROM HoaDon 
                    WHERE MONTH(ngayTao) = :month 
                    AND YEAR(ngayTao) = :year";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            $totalRevenue = $stmt->fetch(PDO::FETCH_ASSOC)['total_revenue'] ?? 0;

            return [
                'total_orders' => $totalOrders,
                'total_users' => $totalUsers,
                'low_stock' => $lowStock,
                'total_revenue' => $totalRevenue
            ];
        } catch (PDOException $e) {
            error_log("Error in getMonthlyStatistics: " . $e->getMessage());
            throw new Exception("Lỗi khi lấy thống kê");
        }
    }

    public function getTopCustomersByDateRange($startDate, $endDate) {
        try {
            $sql = "SELECT 
                        tk.maTK,
                        tk.tenTK,
                        COUNT(DISTINCT hd.maHD) as soLuongDon,
                        COALESCE(SUM(hd.tongTien), 0) as tongChiTieu
                    FROM TaiKhoan tk
                    LEFT JOIN HoaDon hd ON tk.maTK = hd.maTK 
                        AND DATE(hd.ngayTao) BETWEEN :startDate AND :endDate
                        AND hd.trangThai = 3 /* Chỉ tính đơn đã giao hàng */
                    WHERE tk.maQuyen = 2 /* Chỉ lấy tài khoản khách hàng */
                    GROUP BY tk.maTK, tk.tenTK
                    HAVING tongChiTieu > 0
                    ORDER BY tongChiTieu DESC
                    LIMIT 5"; /* Giới hạn 5 người */
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
            $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error in getTopCustomersByDateRange: " . $e->getMessage());
            return [];
        }
    }

    public function getCustomerOrdersByDateRange($userId, $startDate, $endDate) {
        try {
            // Lấy đơn hàng của khách hàng theo khoảng thời gian
            $sql = "SELECT 
                        hd.maHD,
                        hd.ngayTao,
                        hd.tongTien,
                        hd.trangThai,
                        hd.diaChi,
                        COUNT(cthd.maGiay) as soMatHang,
                        GROUP_CONCAT(g.tenGiay SEPARATOR ', ') as danhSachSanPham
                    FROM HoaDon hd
                    LEFT JOIN ChiTietHoaDon cthd ON hd.maHD = cthd.maHD
                    LEFT JOIN Giay g ON cthd.maGiay = g.maGiay
                    WHERE hd.maTK = :userId 
                        AND DATE(hd.ngayTao) BETWEEN :startDate AND :endDate
                        AND hd.trangThai = 3 /* Chỉ tính đơn đã giao hàng */
                    GROUP BY hd.maHD, hd.ngayTao, hd.tongTien, hd.trangThai, hd.diaChi
                    ORDER BY hd.ngayTao DESC";
                        
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
            $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
            $stmt->execute();
            
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Nếu không có đơn hàng, trả về mảng rỗng
            if (empty($orders)) {
                return [];
            }

            // Lấy chi tiết từng đơn hàng
            foreach ($orders as &$order) {
                $sql = "SELECT cthd.*, g.tenGiay, g.hinhAnh
                        FROM ChiTietHoaDon cthd
                        JOIN Giay g ON cthd.maGiay = g.maGiay
                        WHERE cthd.maHD = :orderId";

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':orderId', $order['maHD'], PDO::PARAM_INT);
                $stmt->execute();
                $order['products'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            return $orders;
            
        } catch (PDOException $e) {
            error_log("Error in getCustomerOrdersByDateRange: " . $e->getMessage());
            return [];
        }
    }

    public function searchOrdersByPhoneOrAddress($searchTerm, $page = 1, $limit = 6, $status = null, $sortColumn = 'ngayTao', $sortOrder = 'DESC') {
        try {
            // Debug để kiểm tra từ khóa tìm kiếm và trạng thái
            error_log("Tìm kiếm với từ khóa: " . $searchTerm . ", trạng thái: " . ($status !== null ? $status : 'tất cả'));
            
            // Chuẩn hóa từ khóa tìm kiếm
            $searchTerm = trim($searchTerm);
            
            // Kiểm tra xem từ khóa có phải là số điện thoại không
            $isPhoneSearch = preg_match('/^[0-9\s\-\+\.()]*$/', $searchTerm) && strlen(preg_replace('/[^0-9]/', '', $searchTerm)) >= 3;
            
            // Xử lý số điện thoại để tìm kiếm
            $cleanPhone = preg_replace('/[^0-9]/', '', $searchTerm); // Chỉ giữ lại các chữ số
            
            // Tính offset cho phân trang
            $offset = ($page - 1) * $limit;
            
            // Xây dựng điều kiện WHERE
            $whereConditions = [];
            $params = [];
            
            // Điều kiện tìm kiếm theo số điện thoại hoặc địa chỉ
            if ($isPhoneSearch) {
                // Nếu là số điện thoại, sử dụng tìm kiếm chính xác hơn cho số điện thoại
                $whereConditions[] = "(
                    REPLACE(hd.soDienThoai, ' ', '') LIKE :exactPhone1 OR 
                    REPLACE(tk.soDT, ' ', '') LIKE :exactPhone2 OR
                    REPLACE(hd.soDienThoai, ' ', '') LIKE :endPhone1 OR
                    REPLACE(tk.soDT, ' ', '') LIKE :endPhone2 OR
                    REPLACE(hd.soDienThoai, ' ', '') LIKE :anyPhone1 OR
                    REPLACE(tk.soDT, ' ', '') LIKE :anyPhone2
                )";
                
                // Các mẫu tìm kiếm số điện thoại khác nhau
                $exactPhone = $cleanPhone;                   // Tìm chính xác
                $endPhone = '%' . $cleanPhone;               // Kết thúc với các số này
                $anyPhone = '%' . $cleanPhone . '%';         // Chứa các số này ở bất kỳ vị trí nào
                
                $params[':exactPhone1'] = $exactPhone;
                $params[':exactPhone2'] = $exactPhone;
                $params[':endPhone1'] = $endPhone;
                $params[':endPhone2'] = $endPhone;
                $params[':anyPhone1'] = $anyPhone;
                $params[':anyPhone2'] = $anyPhone;
                
                error_log("Tìm kiếm số điện thoại với mẫu: exactPhone=$exactPhone, endPhone=$endPhone, anyPhone=$anyPhone");
            } else {
                // Xử lý địa chỉ - tách thành các từ để tìm kiếm
                $keywords = explode(' ', $searchTerm);
                $keywords = array_filter($keywords, function($keyword) {
                    return !empty($keyword); // Loại bỏ các từ trống
                });
                
                // Nếu là tìm kiếm địa chỉ
                if (!empty($keywords)) {
                    $addressConditions = "(";
                    foreach ($keywords as $index => $keyword) {
                        if ($index > 0) {
                            $addressConditions .= " AND ";
                        }
                        // Tìm kiếm theo từng từ trong địa chỉ
                        $addressConditions .= "LOWER(hd.diaChi) LIKE LOWER(:addressKeyword$index)";
                        $params[":addressKeyword$index"] = '%' . $keyword . '%';
                    }
                    $addressConditions .= ")";
                    $whereConditions[] = $addressConditions;
                }
            }
            
            // Thêm điều kiện lọc theo trạng thái
            if ($status !== null && $status !== '') {
                $whereConditions[] = "hd.trangThai = :status";
                $params[':status'] = $status;
                error_log("Áp dụng lọc trạng thái: $status");
            }
            
            // Xây dựng WHERE clause cuối cùng
            $whereClause = "";
            if (!empty($whereConditions)) {
                $whereClause = "WHERE " . implode(" AND ", $whereConditions);
            }
            
            // Xác thực cột sắp xếp để tránh SQL injection
            $allowedColumns = ['maHD', 'ngayTao', 'tongSoLuong', 'tongTien', 'trangThai'];
            if (!in_array($sortColumn, $allowedColumns)) {
                $sortColumn = 'ngayTao'; // Mặc định sắp xếp theo ngày tạo
            }
            
            // Xác thực hướng sắp xếp
            $sortOrder = strtoupper($sortOrder);
            if ($sortOrder != 'ASC' && $sortOrder != 'DESC') {
                $sortOrder = 'DESC'; // Mặc định giảm dần
            }
            
            // Truy vấn tìm kiếm đơn hàng để lấy số lượng tổng
            $countSql = "
                SELECT COUNT(DISTINCT hd.maHD) as total 
                FROM HoaDon hd
                LEFT JOIN TaiKhoan tk ON hd.maTK = tk.maTK
                $whereClause";
            
            error_log("SQL count: $countSql");
            $countStmt = $this->db->prepare($countSql);
            
            // Bind các giá trị tìm kiếm
            foreach ($params as $param => $value) {
                $countStmt->bindValue($param, $value, PDO::PARAM_STR);
            }
            
            $countStmt->execute();
            $totalRows = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            error_log("Tìm thấy tổng cộng: $totalRows kết quả");
            
            // Truy vấn để lấy dữ liệu đơn hàng phù hợp với điều kiện tìm kiếm
            $sql = "
                SELECT DISTINCT hd.*, tk.tenTK, COALESCE(hd.soDienThoai, tk.soDT) as soDienThoai
                FROM HoaDon hd
                LEFT JOIN TaiKhoan tk ON hd.maTK = tk.maTK
                $whereClause
                ORDER BY hd.{$sortColumn} {$sortOrder}, hd.maHD DESC
                LIMIT :limit OFFSET :offset
            ";
            
            error_log("SQL search: $sql");
            $stmt = $this->db->prepare($sql);
            
            // Bind các giá trị tìm kiếm
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value, PDO::PARAM_STR);
            }
            
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            
            $stmt->execute();
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Debug kết quả tìm kiếm
            if (!empty($orders)) {
                $orderIds = array_column($orders, 'maHD');
                error_log("Các đơn hàng tìm thấy: " . implode(', ', $orderIds));
                
                // Log vài mẫu dữ liệu để kiểm tra
                foreach (array_slice($orders, 0, 3) as $index => $order) {
                    error_log("Đơn hàng #$index: maHD={$order['maHD']}, soDienThoai={$order['soDienThoai']}, diaChi={$order['diaChi']}, trangThai={$order['trangThai']}");
                }
            } else {
                $statusText = $status !== null ? ", trạng thái: $status" : "";
                error_log("Không tìm thấy đơn hàng nào với từ khóa: '$searchTerm', số đã làm sạch: '$cleanPhone'$statusText");
                
                // Lấy mẫu dữ liệu để hiểu cấu trúc dữ liệu
                $debugSql = "
                    SELECT hd.maHD, hd.soDienThoai, tk.soDT, hd.diaChi, hd.trangThai 
                    FROM HoaDon hd
                    LEFT JOIN TaiKhoan tk ON hd.maTK = tk.maTK
                    LIMIT 10
                ";
                $debugStmt = $this->db->prepare($debugSql);
                $debugStmt->execute();
                $debugResults = $debugStmt->fetchAll(PDO::FETCH_ASSOC);
                error_log("Mẫu dữ liệu trong DB: " . json_encode($debugResults, JSON_UNESCAPED_UNICODE));
            }
            
            return [
                'orders' => $orders,
                'total' => $totalRows,
                'totalPages' => ceil($totalRows / $limit),
                'currentPage' => $page,
                'status' => $status,
                'sortColumn' => $sortColumn,
                'sortOrder' => $sortOrder
            ];
        } catch (PDOException $e) {
            error_log("Error in searchOrdersByPhoneOrAddress: " . $e->getMessage());
            error_log("SQL state: " . $e->errorInfo[0]);
            error_log("SQL error code: " . $e->errorInfo[1]);
            error_log("SQL error message: " . $e->errorInfo[2]);
            
            return [
                'orders' => [],
                'total' => 0,
                'totalPages' => 1,
                'currentPage' => 1,
                'status' => $status,
                'sortColumn' => $sortColumn, 
                'sortOrder' => $sortOrder
            ];
        }
    }
}
?> 
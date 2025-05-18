<?php
class Product extends BaseModel {
    protected $table = 'Giay';
    
    // Định nghĩa các thuộc tính
    protected $maGiay;
    protected $tenGiay;
    protected $maLoaiGiay;
    protected $size;
    protected $giaBan;
    protected $tonKho;
    protected $moTa;
    protected $hinhAnh;
    protected $trangThai;
    protected $tenLoaiGiay; // Thuộc tính phụ từ bảng LoaiGiay

    // Getters
    public function getMaGiay() { return $this->maGiay; }
    public function getTenGiay() { return $this->tenGiay; }
    public function getMaLoaiGiay() { return $this->maLoaiGiay; }
    public function getSize() { return $this->size; }
    public function getGiaBan() { return $this->giaBan; }
    public function getTonKho() { return $this->tonKho; }
    public function getMoTa() { return $this->moTa; }
    public function getHinhAnh() { return $this->hinhAnh; }
    public function getTrangThai() { return $this->trangThai; }
    public function getTenLoaiGiay() { return $this->tenLoaiGiay; }

    // Setters
    public function setMaGiay($maGiay) { $this->maGiay = $maGiay; }
    public function setTenGiay($tenGiay) { $this->tenGiay = $tenGiay; }
    public function setMaLoaiGiay($maLoaiGiay) { $this->maLoaiGiay = $maLoaiGiay; }
    public function setSize($size) { $this->size = $size; }
    public function setGiaBan($giaBan) { $this->giaBan = $giaBan; }
    public function setTonKho($tonKho) { $this->tonKho = $tonKho; }
    public function setMoTa($moTa) { $this->moTa= $moTa; }
    public function setHinhAnh($hinhAnh) { $this->hinhAnh = $hinhAnh; }
    public function setTrangThai($trangThai) { $this->trangThai = $trangThai; }
    public function setTenLoaiGiay($tenLoaiGiay) { $this->tenLoaiGiay = $tenLoaiGiay; }

    // Chuyển đổi từ array sang object
    public function mapFromArray($data) {
        if (!empty($data)) {
            $this->maGiay = $data['maGiay'] ?? null;
            $this->tenGiay = $data['tenGiay'] ?? null;
            $this->maLoaiGiay = $data['maLoaiGiay'] ?? null;
            $this->size = $data['size'] ?? null;
            $this->giaBan = $data['giaBan'] ?? null;
            $this->tonKho = $data['tonKho'] ?? null;
            $this->moTa = $data['moTa'] ?? null;
            $this->hinhAnh = $data['hinhAnh'] ?? null;
            $this->trangThai = $data['trangThai'] ?? null;
            $this->tenLoaiGiay = $data['tenLoaiGiay'] ?? null;
        }
        return $this;
    }

    // Chuyển đổi object sang array
    public function toArray() {
        return [
            'maGiay' => $this->maGiay,
            'tenGiay' => $this->tenGiay,
            'maLoaiGiay' => $this->maLoaiGiay,
            'size' => $this->size,
            'giaBan' => $this->giaBan,
            'tonKho' => $this->tonKho,
            'moTa' => $this->moTa,
            'hinhAnh' => $this->hinhAnh,
            'trangThai' => $this->trangThai,
            'tenLoaiGiay' => $this->tenLoaiGiay
        ];
    }

    // Các phương thức truy vấn
    public function getTonKhoByTenGiayAndSize($tenGiay, $size)
    {
        $sql = "SELECT tonKho FROM {$this->table} WHERE tenGiay = :tenGiay and size = :size";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':tenGiay' => $tenGiay,
            ':size' => $size
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllProducts() {
        $sql = "SELECT g.*, lg.tenLoaiGiay 
                FROM Giay g 
                LEFT JOIN LoaiGiay lg ON g.maLoaiGiay = lg.maLoaiGiay 
                ORDER BY g.maGiay DESC
                WHERE g.trangThai = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $products = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $product = new Product();
            $product->mapFromArray($row);
            $products[] = $product;
        }
        return $products;
    }

    public function getProductsByCategory($categoryId) {
        $sql = "SELECT g.*, lg.tenLoaiGiay 
                FROM Giay g 
                LEFT JOIN LoaiGiay lg ON g.maLoaiGiay = lg.maLoaiGiay 
                WHERE g.maLoaiGiay = :categoryId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        try {
            $sql = "SELECT g.*, lg.tenLoaiGiay 
                    FROM Giay g 
                    LEFT JOIN LoaiGiay lg ON g.maLoaiGiay = lg.maLoaiGiay 
                    WHERE g.maGiay = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                $product = new Product();
                return $product->mapFromArray($data);
            }
            return null;
        } catch (PDOException $e) {
            error_log("Error in getProductById: " . $e->getMessage());
            throw $e;
        }
    }

    public function getProductByIdArray($id) {
        try {
            $sql = "SELECT g.*, lg.tenLoaiGiay 
                    FROM Giay g 
                    LEFT JOIN LoaiGiay lg ON g.maLoaiGiay = lg.maLoaiGiay 
                    WHERE g.maGiay = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                $product = new Product();
                $product->mapFromArray($data);
                return $product->toArray();;
            }
            return null;
        } catch (PDOException $e) {
            error_log("Error in getProductById: " . $e->getMessage());
            throw $e;
        }
    }

    public function getRelatedProducts($categoryId, $currentProductId, $limit = 4) {
        $sql = "SELECT g.*, lg.tenLoaiGiay 
                FROM Giay g 
                LEFT JOIN LoaiGiay lg ON g.maLoaiGiay = lg.maLoaiGiay 
                WHERE g.maLoaiGiay = :categoryId 
                AND g.maGiay != :currentProductId 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':currentProductId', $currentProductId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchProducts($keyword, $filters = [], $page = 1, $perPage = 9) {
        try {
            // Đầu tiên đếm tổng số sản phẩm
            $countSql = "SELECT COUNT(*) as total FROM Giay g 
                         LEFT JOIN LoaiGiay lg ON g.maLoaiGiay = lg.maLoaiGiay 
                         WHERE 1=1";
            
            $sql = "SELECT g.*, lg.tenLoaiGiay 
                    FROM Giay g 
                    LEFT JOIN LoaiGiay lg ON g.maLoaiGiay = lg.maLoaiGiay 
                    WHERE g.trangThai = 1";
                    
            $params = [];
            
            // Thêm điều kiện tìm kiếm theo từ
            if (!empty($keyword)) {
                $searchTerm = trim(mb_strtolower($keyword, 'UTF-8'));
                
                // Tách chuỗi tìm kiếm thành các từ riêng biệt
                $words = preg_split('/\s+/', $searchTerm);
                $searchConditions = [];
                
                foreach ($words as $i => $word) {
                    if (!empty($word)) {
                        $key = ":word{$i}";
                        $searchConditions[] = "LOWER(g.tenGiay) LIKE {$key}";
                        $params[$key] = "%{$word}%";
                    }
                }
                
                if (!empty($searchConditions)) {
                    // Sử dụng AND để tìm tất cả từ trong chuỗi tìm kiếm
                    $whereClause = " AND (" . implode(' AND ', $searchConditions) . ")";
                    $sql .= $whereClause;
                    $countSql .= $whereClause;
                }
            }
            
            // Thêm các điều kiện lọc khác
            if (!empty($filters['categories'])) {
                $categories = array_filter($filters['categories'], function($value) {
                    return !empty($value) && is_numeric($value);
                });
                
                if (!empty($categories)) {
                    $placeholders = array_map(function($i) { 
                        return ':category' . $i;
                    }, array_keys($categories));
                    
                    $categoryClause = " AND g.maLoaiGiay IN (" . implode(',', $placeholders) . ")";
                    $sql .= $categoryClause;
                    $countSql .= $categoryClause;
                    
                    foreach ($categories as $i => $categoryId) {
                        $params[':category' . $i] = $categoryId;
                    }
                }
            }
            
            // Thêm điều kiện lọc giá
            if (!empty($filters['price_range'])) {
                $priceClause = "";
                switch ($filters['price_range']) {
                    case '0-500000':
                        $priceClause = " AND g.giaBan < 500000";
                        break;
                    case '500000-1000000':
                        $priceClause = " AND g.giaBan BETWEEN 500000 AND 1000000";
                        break;
                    case '1000000-2000000':
                        $priceClause = " AND g.giaBan BETWEEN 1000000 AND 2000000";
                        break;
                    case '2000000+':
                        $priceClause = " AND g.giaBan > 2000000";
                        break;
                }
                $sql .= $priceClause;
                $countSql .= $priceClause;
            }
            
            // Thêm sắp xếp
            if (!empty($filters['sort'])) {
                switch ($filters['sort']) {
                    case 'price_asc':
                        $sql .= " ORDER BY g.giaBan ASC";
                        break;
                    case 'price_desc':
                        $sql .= " ORDER BY g.giaBan DESC";
                        break;
                    case 'name_asc':
                        $sql .= " ORDER BY g.tenGiay ASC";
                        break;
                    case 'name_desc':
                        $sql .= " ORDER BY g.tenGiay DESC";
                        break;
                    case 'newest':
                        $sql .= " ORDER BY g.maGiay DESC";
                        break;
                    default:
                        $sql .= " ORDER BY g.maGiay DESC";
                }
            } else {
                $sql .= " ORDER BY g.maGiay DESC";
            }
            
            // Thêm LIMIT và OFFSET cho phân trang
            $sql .= " LIMIT :limit OFFSET :offset";
            
            // Đếm tổng số sản phẩm
            $countStmt = $this->db->prepare($countSql);
            foreach ($params as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            $countStmt->execute();
            $totalProducts = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Thêm params cho phân trang
            $params[':limit'] = $perPage;
            $params[':offset'] = ($page - 1) * $perPage;
            
            // Thực hiện truy vấn chính
            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            
            $products = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $product = new Product();
                $product->mapFromArray($row);
                $products[] = $product;
            }
            
            return [
                'products' => $products,
                'total' => $totalProducts,
                'totalPages' => ceil($totalProducts / $perPage),
                'currentPage' => $page
            ];

        } catch (PDOException $e) {
            error_log("Error in searchProducts: " . $e->getMessage());
            return [
                'products' => [],
                'total' => 0,
                'totalPages' => 0,
                'currentPage' => 1
            ];
        }
    }

    public function getAll() {
        $sql = "SELECT g.*, lg.tenLoaiGiay 
                FROM Giay g 
                LEFT JOIN LoaiGiay lg ON g.maLoaiGiay = lg.maLoaiGiay 
                ORDER BY g.maGiay DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFeaturedProducts($limit = 4) {
        $sql = "SELECT g.*, lg.tenLoaiGiay 
                FROM Giay g 
                LEFT JOIN LoaiGiay lg ON g.maLoaiGiay = lg.maLoaiGiay 
                WHERE g.tonKho > 0 
                ORDER BY g.tonKho DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE maGiay = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function countAll() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getLowStockProducts() {
        try {
            $sql = "SELECT g.*, lg.tenLoaiGiay 
                    FROM Giay g
                    LEFT JOIN LoaiGiay lg ON g.maLoaiGiay = lg.maLoaiGiay
                    WHERE g.tonKho <= 5 
                    ORDER BY g.tonKho ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getLowStockProducts: " . $e->getMessage());
            return [];
        }
    }

    public function add($formData) {
        extract($formData);
        $hinhAnh = base64_encode($hinhAnh);
        $sql = "INSERT INTO Giay (tenGiay, maLoaiGiay, size, giaBan, tonKho, hinhAnh, moTa) 
                VALUES (:tenGiay, :maLoaiGiay, :size, :giaBan, :tonKho, :hinhAnh, :moTa)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':tenGiay' => $tenGiay,
            ':maLoaiGiay' => $maLoaiGiay,
            ':size' => $size,
            ':giaBan' => $giaBan,
            ':tonKho' => $tonKho,
            ':hinhAnh' => $hinhAnh,
            ':moTa' => $moTa
        ]);
    }

    public function isTenGiayExists($tenGiay, $size) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE tenGiay = :tenGiay and size = :size";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tenGiay' => $tenGiay, ':size' => $size]);
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    public function update($id, $formData) {
        extract($formData);
        $hinhAnh = base64_encode($hinhAnh);
        $sql = "UPDATE Giay 
                SET tenGiay = :tenGiay, 
                    maLoaiGiay = :maLoaiGiay, 
                    size = :size, 
                    giaBan = :giaBan, 
                    tonKho = :tonKho,
                    moTa = :moTa,
                    hinhAnh = :hinhAnh,
                    trangThai = :trangThai";
        
        $params = [
            ':tenGiay' => $tenGiay,
            ':maLoaiGiay' => $maLoaiGiay,
            ':size' => $size,
            ':giaBan' => $giaBan,
            ':tonKho' => $tonKho,
            ':moTa' => $moTa,
            ':hinhAnh' => $hinhAnh,
            ':trangThai' => $trangThai,
            ':id' => $id
        ];


        $sql .= " WHERE maGiay = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Kiểm tra xem sản phẩm có nằm trong đơn hàng nào không
     * @param int $productId Mã sản phẩm cần kiểm tra
     * @return bool True nếu sản phẩm đã có trong đơn hàng, False nếu chưa
     */
    public function isProductInOrder($productId) {
        try {
            $sql = "SELECT COUNT(*) as count FROM ChiTietHoaDon WHERE maGiay = :productId";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':productId', $productId, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($result['count'] > 0);
        } catch (PDOException $e) {
            error_log("Error in isProductInOrder: " . $e->getMessage());
            // Trong trường hợp lỗi, để an toàn, chúng ta giả định sản phẩm đã có trong đơn hàng
            return true;
        }
    }

    /**
     * Xóa sản phẩm khỏi cơ sở dữ liệu
     * @param int $id Mã sản phẩm cần xóa
     * @return bool Kết quả thực hiện
     */
    public function permanentDelete($id) {
        $sql = "DELETE FROM {$this->table} WHERE maGiay = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Xóa hoặc khóa sản phẩm tùy vào việc sản phẩm có nằm trong đơn hàng hay không
     * @param int $id Mã sản phẩm
     * @return array Kết quả thực hiện và thông báo
     */
    public function delete($id) {
        try {
            // Kiểm tra xem sản phẩm có trong đơn hàng nào không
            $isInOrder = $this->isProductInOrder($id);
            
            if ($isInOrder) {
                // Nếu sản phẩm đã có trong đơn hàng, chỉ khóa sản phẩm
                $sql = "UPDATE {$this->table} SET trangThai = :trangThai WHERE maGiay = :id";
                $stmt = $this->db->prepare($sql);
                $result = $stmt->execute([
                    ':trangThai' => 0,
                    ':id' => $id
                ]);
                
                return [
                    'success' => $result,
                    'action' => 'lock',
                    'message' => 'Sản phẩm đã được khóa'
                ];
            } else {
                // Nếu sản phẩm chưa có trong đơn hàng nào, xóa hoàn toàn
                $result = $this->permanentDelete($id);
                
                return [
                    'success' => $result,
                    'action' => 'delete',
                    'message' => 'Sản phẩm đã được xóa hoàn toàn'
                ];
            }
        } catch (PDOException $e) {
            error_log("Error in delete: " . $e->getMessage());
            return [
                'success' => false,
                'action' => 'error',
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()
            ];
        }
    }

    public function unlock($id) {
        $sql = "UPDATE {$this->table} SET trangThai = :trangThai WHERE maGiay = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':trangThai' => 1,
            ':id' => $id
        ]);
    }

    public function increaseStock($id, $quantity) {
        $sql = "UPDATE Giay SET tonKho = tonKho + :quantity WHERE maGiay = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':quantity' => $quantity,
            ':id' => $id
        ]);
    }

    public function decreaseStock($id, $quantity) {
        $sql = "UPDATE Giay SET tonKho = tonKho - :quantity WHERE maGiay = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':quantity' => $quantity,
            ':id' => $id
        ]);
    }

    public function getAllWithPagination($page = 1, $perPage = 6) {
        try {
            // Đếm tổng số sản phẩm
            $countSql = "SELECT COUNT(*) as total FROM Giay";
            $countStmt = $this->db->prepare($countSql);
            $countStmt->execute();
            $totalProducts = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Tính offset cho phân trang
            $offset = ($page - 1) * $perPage;

            // Lấy danh sách sản phẩm theo phân trang
            $sql = "SELECT g.*, lg.tenLoaiGiay 
                    FROM Giay g 
                    LEFT JOIN LoaiGiay lg ON g.maLoaiGiay = lg.maLoaiGiay 
                    ORDER BY g.maGiay ASC
                    LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'products' => $products,
                'total' => $totalProducts,
                'totalPages' => ceil($totalProducts / $perPage),
                'currentPage' => $page
            ];
            
        } catch (PDOException $e) {
            error_log("Error in getAllWithPagination: " . $e->getMessage());
            return [
                'products' => [],
                'total' => 0,
                'totalPages' => 0,
                'currentPage' => 1
            ];
        }
    }
}
?> 
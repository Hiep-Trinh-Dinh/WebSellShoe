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
    protected $hinhAnh;
    protected $tenLoaiGiay; // Thuộc tính phụ từ bảng LoaiGiay

    // Getters
    public function getMaGiay() { return $this->maGiay; }
    public function getTenGiay() { return $this->tenGiay; }
    public function getMaLoaiGiay() { return $this->maLoaiGiay; }
    public function getSize() { return $this->size; }
    public function getGiaBan() { return $this->giaBan; }
    public function getTonKho() { return $this->tonKho; }
    public function getHinhAnh() { return $this->hinhAnh; }
    public function getTenLoaiGiay() { return $this->tenLoaiGiay; }

    // Setters
    public function setMaGiay($maGiay) { $this->maGiay = $maGiay; }
    public function setTenGiay($tenGiay) { $this->tenGiay = $tenGiay; }
    public function setMaLoaiGiay($maLoaiGiay) { $this->maLoaiGiay = $maLoaiGiay; }
    public function setSize($size) { $this->size = $size; }
    public function setGiaBan($giaBan) { $this->giaBan = $giaBan; }
    public function setTonKho($tonKho) { $this->tonKho = $tonKho; }
    public function setHinhAnh($hinhAnh) { $this->hinhAnh = $hinhAnh; }
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
            $this->hinhAnh = $data['hinhAnh'] ?? null;
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
            'hinhAnh' => $this->hinhAnh,
            'tenLoaiGiay' => $this->tenLoaiGiay
        ];
    }

    // Các phương thức truy vấn
    public function getAllProducts() {
        $sql = "SELECT g.*, lg.tenLoaiGiay 
                FROM Giay g 
                LEFT JOIN LoaiGiay lg ON g.maLoaiGiay = lg.maLoaiGiay 
                ORDER BY g.maGiay DESC";
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
                    WHERE 1=1";
                    
            $params = [];
            
            // Thêm điều kiện tìm kiếm
            if (!empty($keyword)) {
                $searchTerm = trim(mb_strtolower($keyword, 'UTF-8'));
                $searchConditions = [];
                
                for ($i = 0; $i < mb_strlen($searchTerm, 'UTF-8'); $i++) {
                    $char = mb_substr($searchTerm, $i, 1, 'UTF-8');
                    if ($char !== ' ') {
                        $key = ":char{$i}";
                        $searchConditions[] = "LOWER(g.tenGiay) LIKE {$key}";
                        $params[$key] = "%{$char}%";
                    }
                }
                
                if (!empty($searchConditions)) {
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
        $sql = "INSERT INTO Giay (tenGiay, maLoaiGiay, size, giaBan, tonKho, hinhAnh) 
                VALUES (:tenGiay, :maLoaiGiay, :size, :giaBan, :tonKho, :hinhAnh)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':tenGiay' => $tenGiay,
            ':maLoaiGiay' => $maLoaiGiay,
            ':size' => $size,
            ':giaBan' => $giaBan,
            ':tonKho' => $tonKho,
            ':hinhAnh' => $hinhAnh
        ]);
    }

    public function isTenGiayExists($tenGiay) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE tenGiay = :tenGiay";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tenGiay' => $tenGiay]);
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
                    hinhAnh = :hinhAnh,
                    trangThai = :trangThai";
        
        $params = [
            ':tenGiay' => $tenGiay,
            ':maLoaiGiay' => $maLoaiGiay,
            ':size' => $size,
            ':giaBan' => $giaBan,
            ':tonKho' => $tonKho,
            ':hinhAnh' => $hinhAnh,
            ':trangThai' => $trangThai,
            ':id' => $id
        ];


        $sql .= " WHERE maGiay = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $sql = "UPDATE {$this->table} SET trangThai = :trangThai WHERE maGiay = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':trangThai' => 0,
            ':id' => $id
        ]);
    }

    public function unlock($id) {
        $sql = "UPDATE {$this->table} SET trangThai = :trangThai WHERE maGiay = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':trangThai' => 1,
            ':id' => $id
        ]);
    }

    public function updateStock($id, $quantity) {
        $sql = "UPDATE Giay SET tonKho = tonKho + :quantity WHERE maGiay = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':quantity' => $quantity,
            ':id' => $id
        ]);
    }

    // Giữ lại một bản của mỗi method
    public function getTotalProducts() {
        try {
            $sql = "SELECT COUNT(*) as total FROM Giay";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            error_log("Error in getTotalProducts: " . $e->getMessage());
            return 0;
        }
    }
}
?> 
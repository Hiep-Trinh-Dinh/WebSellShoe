<?php
class Product extends BaseModel {
    protected $table = 'Giay';

    public function getAllProducts() {
        $sql = "SELECT g.*, lg.tenLoaiGiay 
                FROM Giay g 
                LEFT JOIN LoaiGiay lg ON g.maLoaiGiay = lg.maLoaiGiay 
                ORDER BY g.maGiay DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        $sql = "SELECT g.*, lg.tenLoaiGiay 
                FROM Giay g 
                LEFT JOIN LoaiGiay lg ON g.maLoaiGiay = lg.maLoaiGiay 
                WHERE g.maGiay = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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

    public function searchProducts($keyword) {
        $sql = "SELECT g.*, lg.tenLoaiGiay 
                FROM Giay g 
                LEFT JOIN LoaiGiay lg ON g.maLoaiGiay = lg.maLoaiGiay 
                WHERE g.tenGiay LIKE :keyword";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public function getLowStockProducts($limit = 5) {
        $sql = "SELECT g.*, lg.tenLoaiGiay 
                FROM Giay g 
                LEFT JOIN LoaiGiay lg ON g.maLoaiGiay = lg.maLoaiGiay 
                WHERE g.tonKho <= 10 
                ORDER BY g.tonKho ASC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($data) {
        $sql = "INSERT INTO Giay (tenGiay, maLoaiGiay, size, giaBan, tonKho, hinhAnh) 
                VALUES (:tenGiay, :maLoaiGiay, :size, :giaBan, :tonKho, :hinhAnh)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':tenGiay' => $data['tenGiay'],
            ':maLoaiGiay' => $data['maLoaiGiay'],
            ':size' => $data['size'],
            ':giaBan' => $data['giaBan'],
            ':tonKho' => $data['tonKho'],
            ':hinhAnh' => $data['hinhAnh']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE Giay 
                SET tenGiay = :tenGiay, 
                    maLoaiGiay = :maLoaiGiay, 
                    size = :size, 
                    giaBan = :giaBan, 
                    tonKho = :tonKho";
        
        $params = [
            ':tenGiay' => $data['tenGiay'],
            ':maLoaiGiay' => $data['maLoaiGiay'],
            ':size' => $data['size'],
            ':giaBan' => $data['giaBan'],
            ':tonKho' => $data['tonKho'],
            ':id' => $id
        ];

        // Chỉ cập nhật hình ảnh nếu có
        if (isset($data['hinhAnh']) && !empty($data['hinhAnh'])) {
            $sql .= ", hinhAnh = :hinhAnh";
            $params[':hinhAnh'] = $data['hinhAnh'];
        }

        $sql .= " WHERE maGiay = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $sql = "DELETE FROM Giay WHERE maGiay = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function updateStock($id, $quantity) {
        $sql = "UPDATE Giay SET tonKho = tonKho + :quantity WHERE maGiay = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':quantity' => $quantity,
            ':id' => $id
        ]);
    }
}
?> 
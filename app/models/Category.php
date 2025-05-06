<?php
class Category extends BaseModel {
    protected $table = 'LoaiGiay';

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY maLoaiGiay DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE maLoaiGiay = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function add($formData) {
        extract($formData);
        $sql = "INSERT INTO {$this->table} (tenLoaiGiay) VALUES (:tenLoaiGiay)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':tenLoaiGiay' => $tenLoaiGiay,
        ]);
    }

    public function update($id, $formData) {
        extract($formData);
        $sql = "UPDATE {$this->table} SET tenLoaiGiay = :tenLoaiGiay, trangThai = :trangThai 
                    WHERE maLoaiGiay = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':tenLoaiGiay' => $tenLoaiGiay,
            ':trangThai' => $trangThai,
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $sql = "UPDATE {$this->table} SET trangThai = :trangThai WHERE maLoaiGiay = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':trangThai' => 0,
            ':id' => $id
        ]);
    }

    public function unlock($id) {
        $sql = "UPDATE {$this->table} SET trangThai = :trangThai WHERE maLoaiGiay = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':trangThai' => 1,
            ':id' => $id
        ]);
    }

    public function getAllWithPagination($page = 1, $perPage = 6) {
        try {
            // Đếm tổng số loại giày
            $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
            $countStmt = $this->db->prepare($countSql);
            $countStmt->execute();
            $totalCategories = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Tính offset cho phân trang
            $offset = ($page - 1) * $perPage;

            // Lấy danh sách loại giày theo phân trang
            $sql = "SELECT * FROM {$this->table} ORDER BY maLoaiGiay ASC LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'categories' => $categories,
                'total' => $totalCategories,
                'totalPages' => ceil($totalCategories / $perPage),
                'currentPage' => $page
            ];
            
        } catch (PDOException $e) {
            error_log("Error in getAllWithPagination: " . $e->getMessage());
            return [
                'categories' => [],
                'total' => 0,
                'totalPages' => 0,
                'currentPage' => 1
            ];
        }
    }
}

?> 

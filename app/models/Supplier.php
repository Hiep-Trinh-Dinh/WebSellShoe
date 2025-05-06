<?php
class Supplier extends BaseModel {
    protected $table = 'nhacungcap';

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY maNCC DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        // Convert ID to integer and ensure it's positive
        $id = intval($id);
        
        if ($id <= 0) {
            error_log("Invalid ID received in getById: " . $id);
            return false;
        }
        
        error_log("Attempting to fetch supplier with ID: " . $id);
        
        $sql = "SELECT * FROM {$this->table} WHERE maNCC = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        
        // Debug
        error_log("SQL Query: " . $sql);
        error_log("ID (after conversion): " . $id);
        
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            error_log("No supplier found with ID: " . $id);
        } else {
            error_log("Found supplier: " . print_r($result, true));
        }
        
        return $result;
    }

    public function add($data) {
        try {
            $sql = "INSERT INTO {$this->table} (tenNCC, email, diaChi) 
                    VALUES (:tenNCC, :email, :diaChi)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':tenNCC' => $data['tenNCC'],
                ':email' => $data['email'],
                ':diaChi' => $data['diaChi']
            ]);
        } catch (PDOException $e) {
            error_log("Error in Supplier::add: " . $e->getMessage());
            throw new Exception('Có lỗi xảy ra khi thêm nhà cung cấp');
        }
    }

    public function update($id, $data) {
        try {
            // Convert ID to integer to ensure it's a number
            $id = intval($id);
            
            // Debug
            error_log("Updating supplier with ID: " . $id);
            error_log("Update data: " . print_r($data, true));

            // Check if supplier exists
            $checkSql = "SELECT COUNT(*) as count FROM {$this->table} WHERE maNCC = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$id]);
            $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['count'] == 0) {
                error_log("No supplier found with ID: " . $id);
                return false;
            }
            
            $sql = "UPDATE {$this->table} SET tenNCC = ?, email = ?, diaChi = ? WHERE maNCC = ?";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                $data['tenNCC'],
                $data['email'],
                $data['diaChi'],
                $id
            ]);

            // Debug
            error_log("Update result: " . ($result ? "success" : "failed"));
            if (!$result) {
                error_log("PDO Error Info: " . print_r($stmt->errorInfo(), true));
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id) {
        try {
            // Kiểm tra xem nhà cung cấp có tồn tại không
            $checkSql = "SELECT * FROM {$this->table} WHERE maNCC = :id";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->bindValue(':id', $id, PDO::PARAM_INT);
            $checkStmt->execute();
            
            if (!$checkStmt->fetch()) {
                throw new Exception('Không tìm thấy nhà cung cấp');
            }

            // Kiểm tra xem nhà cung cấp có đang được sử dụng trong bảng PhieuNhap không
            $checkUsageSql = "SELECT COUNT(*) as count FROM PhieuNhap WHERE maNCC = :id";
            $checkUsageStmt = $this->db->prepare($checkUsageSql);
            $checkUsageStmt->bindValue(':id', $id, PDO::PARAM_INT);
            $checkUsageStmt->execute();
            $usageResult = $checkUsageStmt->fetch(PDO::FETCH_ASSOC);

            if ($usageResult['count'] > 0) {
                throw new Exception('Không thể xóa nhà cung cấp này vì đã có phiếu nhập liên quan');
            }

            // Thực hiện xóa
            $sql = "DELETE FROM {$this->table} WHERE maNCC = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in Supplier::delete: " . $e->getMessage());
            throw new Exception('Có lỗi xảy ra khi xóa nhà cung cấp');
        }
    }

    public function getAllWithPagination($page = 1, $limit = 6) {
        try {
            // Tính offset
            $offset = ($page - 1) * $limit;
            
            // Lấy tổng số nhà cung cấp
            $totalSql = "SELECT COUNT(*) as total FROM {$this->table}";
            $totalStmt = $this->db->prepare($totalSql);
            $totalStmt->execute();
            $total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Lấy danh sách nhà cung cấp theo phân trang
            $sql = "SELECT * FROM {$this->table} ORDER BY maNCC ASC LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return [
                'suppliers' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                'total' => $total,
                'totalPages' => ceil($total / $limit),
                'currentPage' => $page
            ];
        } catch (PDOException $e) {
            error_log("Error in getAllWithPagination: " . $e->getMessage());
            return [
                'suppliers' => [],
                'total' => 0,
                'totalPages' => 1,
                'currentPage' => 1
            ];
        }
    }
} 
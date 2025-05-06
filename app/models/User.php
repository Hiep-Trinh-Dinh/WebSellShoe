<?php
class User extends BaseModel {
    protected $table = 'TaiKhoan';
    
    // Định nghĩa các thuộc tính
    protected $maTK;
    protected $tenTK;
    protected $matKhau;
    protected $hoTen;
    protected $email;
    protected $soDienThoai;
    protected $diaChi;
    protected $maQuyen;
    protected $trangThai;
    protected $tenQuyen; // từ bảng Quyen

    // Getters
    public function getMaTK() { return $this->maTK; }
    public function getTenTK() { return $this->tenTK; }
    public function getMatKhau() { return $this->matKhau; }
    public function getHoTen() { return $this->hoTen; }
    public function getEmail() { return $this->email; }
    public function getSoDienThoai() { return $this->soDienThoai; }
    public function getDiaChi() { return $this->diaChi; }
    public function getMaQuyen() { return $this->maQuyen; }
    public function getTrangThai() { return $this->trangThai; }
    public function getTenQuyen() { return $this->tenQuyen; }

    // Setters
    public function setMaTK($value) { $this->maTK = $value; }
    public function setTenTK($value) { $this->tenTK = $value; }
    public function setMatKhau($value) { $this->matKhau = $value; }
    public function setHoTen($value) { $this->hoTen = $value; }
    public function setEmail($value) { $this->email = $value; }
    public function setSoDienThoai($value) { $this->soDienThoai = $value; }
    public function setDiaChi($value) { $this->diaChi = $value; }
    public function setMaQuyen($value) { $this->maQuyen = $value; }
    public function setTrangThai($value) { $this->trangThai = $value; }
    public function setTenQuyen($value) { $this->tenQuyen = $value; }

    // Phương thức map dữ liệu từ array sang object
    public function mapFromArray($data) {
        $this->maTK = $data['maTK'] ?? null;
        $this->tenTK = $data['tenTK'] ?? null;
        $this->matKhau = $data['matKhau'] ?? null;
        $this->hoTen = $data['hoTen'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->soDienThoai = $data['soDienThoai'] ?? null;
        $this->diaChi = $data['diaChi'] ?? null;
        $this->maQuyen = $data['maQuyen'] ?? null;
        $this->trangThai = $data['trangThai'] ?? null;
        $this->tenQuyen = $data['tenQuyen'] ?? null;
    }

    public function getAll() {
        $sql = "SELECT tk.*, q.tenQuyen 
                FROM TaiKhoan tk 
                LEFT JOIN Quyen q ON tk.maQuyen = q.maQuyen 
                ORDER BY tk.maTK DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT tk.*, q.tenQuyen 
                FROM TaiKhoan tk 
                LEFT JOIN Quyen q ON tk.maQuyen = q.maQuyen 
                WHERE tk.maTK = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $user = new User();
            $user->mapFromArray($data);
            return $user;
        }
        return null;
    }

    public function add($data) {
        $sql = "INSERT INTO TaiKhoan (tenTK, matKhau, maQuyen, trangThai) 
                VALUES (:tenTK, :matKhau, :maQuyen, :trangThai)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':tenTK' => $data['tenTK'],
            ':matKhau' => $data['matKhau'],
            ':maQuyen' => $data['maQuyen'],
            ':trangThai' => $data['trangThai'] ?? 1
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE TaiKhoan 
                SET tenTK = :tenTK, 
                    maQuyen = :maQuyen, 
                    trangThai = :trangThai 
                WHERE maTK = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':tenTK' => $data['tenTK'],
            ':maQuyen' => $data['maQuyen'],
            ':trangThai' => $data['trangThai'],
            ':id' => $id
        ]);
    }

    public function updatePassword($id, $newPassword) {
        $sql = "UPDATE TaiKhoan SET matKhau = :matKhau WHERE maTK = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':matKhau' => $newPassword,
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM TaiKhoan WHERE maTK = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getAllRoles() {
        $sql = "SELECT * FROM Quyen ORDER BY maQuyen";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkUsername($username, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM TaiKhoan WHERE tenTK = :username";
        if ($excludeId) {
            $sql .= " AND maTK != :excludeId";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $username);
        if ($excludeId) {
            $stmt->bindValue(':excludeId', $excludeId, PDO::PARAM_INT);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    public function getUserByUsername($username) {
        $sql = "SELECT tk.*, q.tenQuyen 
                FROM TaiKhoan tk 
                LEFT JOIN Quyen q ON tk.maQuyen = q.maQuyen 
                WHERE tk.tenTK = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $user = new User();
            $user->mapFromArray($data);
            return $user;
        }
        return null;
    }

    public function createUser($data) {
        $sql = "INSERT INTO TaiKhoan (tenTK, matKhau, maQuyen, trangThai) 
                VALUES (:tenTK, :matKhau, :maQuyen, :trangThai)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function getUserById($userId) {
        try {
            // Debug để kiểm tra userId
            error_log("Getting user with ID: " . $userId);

            $sql = "SELECT * FROM TaiKhoan WHERE maTK = :userId";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Debug để kiểm tra kết quả
            error_log("User data: " . print_r($user, true));
            
            if (!$user) {
                error_log("No user found with ID: " . $userId);
                return null;
            }

            // Đảm bảo các trường không bị null
            $user['email'] = $user['email'] ?? '';
            $user['soDT'] = $user['soDT'] ?? '';
            $user['diaChi'] = $user['diaChi'] ?? '';
            
            return $user;
        } catch (PDOException $e) {
            error_log("Error in getUserById: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("User ID: " . $userId);
            throw new Exception("Lỗi khi lấy thông tin người dùng: " . $e->getMessage());
        }
    }

    public function updateUser($id, $data) {
        $sql = "UPDATE TaiKhoan SET 
                hoTen = :hoTen,
                email = :email,
                soDienThoai = :soDienThoai,
                diaChi = :diaChi
                WHERE maTK = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':hoTen' => $data['hoTen'],
            ':email' => $data['email'],
            ':soDienThoai' => $data['soDienThoai'],
            ':diaChi' => $data['diaChi']
        ]);
    }

    public function getUserByEmail($email) {
        $sql = "SELECT * FROM TaiKhoan WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
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

    public function getTotalUsers() {
        try {
            $sql = "SELECT COUNT(*) as total FROM TaiKhoan WHERE maQuyen = 2";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            error_log("Error in getTotalUsers: " . $e->getMessage());
            return 0;
        }
    }

    public function changeStatus($id, $status) {
        $sql = "UPDATE {$this->table} SET trangThai = :trangThai WHERE maTK = :id";
        $stmt = $this->db->prepare($sql);
        
        // Debug
        error_log("Changing user status: ID = $id, Status = $status");
        
        $result = $stmt->execute([
            ':trangThai' => $status,
            ':id' => $id
        ]);
        
        if (!$result) {
            error_log("Error changing status: " . print_r($stmt->errorInfo(), true));
        }
        
        return $result;
    }

    public function getAllWithPagination($page = 1, $limit = 6) {
        try {
            // Tính offset
            $offset = ($page - 1) * $limit;
            
            // Lấy tổng số người dùng
            $totalSql = "SELECT COUNT(*) as total FROM TaiKhoan";
            $totalStmt = $this->db->prepare($totalSql);
            $totalStmt->execute();
            $total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Lấy danh sách người dùng theo phân trang
            $sql = "SELECT tk.*, q.tenQuyen 
                    FROM TaiKhoan tk 
                    LEFT JOIN Quyen q ON tk.maQuyen = q.maQuyen 
                    ORDER BY tk.maTK ASC 
                    LIMIT :limit OFFSET :offset";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return [
                'users' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                'total' => $total,
                'totalPages' => ceil($total / $limit),
                'currentPage' => $page
            ];
        } catch (PDOException $e) {
            error_log("Error in getAllWithPagination: " . $e->getMessage());
            return [
                'users' => [],
                'total' => 0,
                'totalPages' => 1,
                'currentPage' => 1
            ];
        }
    }
}
?> 
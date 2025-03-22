<?php
class User extends BaseModel {
    protected $table = 'TaiKhoan';

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
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
        $sql = "SELECT * FROM TaiKhoan WHERE tenTK = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($data) {
        $sql = "INSERT INTO TaiKhoan (tenTK, matKhau, maQuyen, trangThai) 
                VALUES (:tenTK, :matKhau, :maQuyen, :trangThai)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM TaiKhoan WHERE maTK = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($id, $data) {
        $sql = "UPDATE TaiKhoan SET 
                hoTen = :hoTen,
                email = :email,
                soDienThoai = :soDienThoai,
                diaChi = :diaChi
                WHERE maTK = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':hoTen', $data['hoTen']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':soDienThoai', $data['soDienThoai']);
        $stmt->bindParam(':diaChi', $data['diaChi']);
        
        return $stmt->execute();
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
}
?> 
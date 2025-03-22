<?php
class Supplier extends BaseModel {
    protected $table = 'NhaCungCap';

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY maNCC DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE maNCC = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function add($data) {
        $sql = "INSERT INTO {$this->table} (tenNCC, email, diaChi) 
                VALUES (:tenNCC, :email, :diaChi)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':tenNCC' => $data['tenNCC'],
            ':email' => $data['email'],
            ':diaChi' => $data['diaChi']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET tenNCC = :tenNCC, 
                    email = :email, 
                    diaChi = :diaChi 
                WHERE maNCC = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':tenNCC' => $data['tenNCC'],
            ':email' => $data['email'],
            ':diaChi' => $data['diaChi'],
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE maNCC = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
} 
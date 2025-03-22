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

    public function add($data) {
        $sql = "INSERT INTO {$this->table} (tenLoaiGiay) VALUES (:tenLoaiGiay)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':tenLoaiGiay' => $data['tenLoaiGiay']]);
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET tenLoaiGiay = :tenLoaiGiay WHERE maLoaiGiay = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':tenLoaiGiay' => $data['tenLoaiGiay'],
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE maLoaiGiay = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?> 
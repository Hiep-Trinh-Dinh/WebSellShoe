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
}

?> 

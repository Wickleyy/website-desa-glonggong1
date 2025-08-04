<?php
// admin/models/Model.php
require_once '../config/database.php';

class Model {
    protected $conn;
    protected $table;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }
    
    public function getAll($where = '', $order = '') {
        $sql = "SELECT * FROM {$this->table}";
        if ($where) $sql .= " WHERE $where";
        if ($order) $sql .= " ORDER BY $order";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $fields = array_keys($data);
        $placeholders = ':' . implode(', :', $fields);
        $fields_str = implode(', ', $fields);
        
        $sql = "INSERT INTO {$this->table} ($fields_str) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        return $stmt->execute();
    }
    
    public function update($id, $data) {
        $fields = array_keys($data);
        $set_clause = implode(' = ?, ', $fields) . ' = ?';
        
        $sql = "UPDATE {$this->table} SET $set_clause WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        
        $values = array_values($data);
        $values[] = $id;
        
        return $stmt->execute($values);
    }
    
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function count($where = '') {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        if ($where) $sql .= " WHERE $where";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}

// UMKM Model
class UmkmModel extends Model {
    protected $table = 'umkm';
    
    public function getByKategori($kategori) {
        return $this->getAll("kategori = '$kategori' AND status = 'aktif'", 'nama_usaha ASC');
    }
    
    public function search($keyword) {
        $sql = "SELECT * FROM {$this->table} WHERE (nama_usaha LIKE ? OR deskripsi LIKE ? OR pemilik LIKE ?) AND status = 'aktif'";
        $stmt = $this->conn->prepare($sql);
        $keyword = "%$keyword%";
        $stmt->execute([$keyword, $keyword, $keyword]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Berita Model
class BeritaModel extends Model {
    protected $table = 'berita';
    
    public function getPublished($limit = null) {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'published' ORDER BY created_at DESC";
        if ($limit) $sql .= " LIMIT $limit";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getBySlug($slug) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE slug = ? AND status = 'published'");
        $stmt->execute([$slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function generateUniqueSlug($title, $id = null) {
        $slug = generate_slug($title);
        $original_slug = $slug;
        $counter = 1;
        
        while (true) {
            $sql = "SELECT id FROM {$this->table} WHERE slug = ?";
            $params = [$slug];
            
            if ($id) {
                $sql .= " AND id != ?";
                $params[] = $id;
            }
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            
            if (!$stmt->fetch()) {
                break;
            }
            
            $slug = $original_slug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}

// Kontak Model
class KontakModel extends Model {
    protected $table = 'kontak';
    
    public function getUnread() {
        return $this->getAll("status = 'baru'", 'created_at DESC');
    }
    
    public function markAsRead($id) {
        return $this->update($id, ['status' => 'dibaca']);
    }
}

// Program Model
class ProgramModel extends Model {
    protected $table = 'program';
    
    public function getActive() {
        return $this->getAll("status IN ('berlangsung', 'akan_datang')", 'tanggal_mulai ASC');
    }
}

// Galeri Model
class GaleriModel extends Model {
    protected $table = 'galeri';
    
    public function getByKategori($kategori) {
        return $this->getAll("kategori = '$kategori'", 'created_at DESC');
    }
}
?>
<?php
class ProdukModel {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    // Ambil semua produk
    public function getAll() {
        $query = "SELECT p.*, k.nama_kategori 
                  FROM produk p 
                  LEFT JOIN kategori k ON p.id_kategori = k.id_kategori
                  ORDER BY p.id_produk DESC";
        return mysqli_query($this->conn, $query);
    }

    // Ambil produk berdasarkan ID
    public function getById($id) {
        $query = "SELECT * FROM produk WHERE id_produk='$id' LIMIT 1";
        return mysqli_fetch_assoc(mysqli_query($this->conn, $query));
    }

    // Tambah produk baru
    public function create($data) {
        $nama = mysqli_real_escape_string($this->conn, $data['nama_produk']);
        $kode = mysqli_real_escape_string($this->conn, $data['kode_produk']);
        $harga = $data['harga'];
        $stok = $data['stok'];
        $kategori = $data['id_kategori'] ?: 'NULL';
        $lokasi = mysqli_real_escape_string($this->conn, $data['lokasi_simpan']);
        $foto = $data['foto_produk'] ?? null;

        $query = "INSERT INTO produk (id_kategori, kode_produk, nama_produk, harga, stok, lokasi_simpan, foto_produk) 
                  VALUES ($kategori, '$kode', '$nama', '$harga', '$stok', '$lokasi', '$foto')";
        return mysqli_query($this->conn, $query);
    }

    // Edit produk
    public function update($id, $data) {
        $nama = mysqli_real_escape_string($this->conn, $data['nama_produk']);
        $harga = $data['harga'];
        $stok = $data['stok'];
        $lokasi = mysqli_real_escape_string($this->conn, $data['lokasi_simpan']);
        $foto = isset($data['foto_produk']) ? ", foto_produk='" . $data['foto_produk'] . "'" : '';

        $query = "UPDATE produk SET 
                    nama_produk='$nama',
                    harga='$harga',
                    stok='$stok',
                    lokasi_simpan='$lokasi' 
                    $foto
                  WHERE id_produk='$id'";
        return mysqli_query($this->conn, $query);
    }

    // Hapus produk
    public function delete($id) {
        return mysqli_query($this->conn, "DELETE FROM produk WHERE id_produk='$id'");
    }
}
?>

<?php
class TransaksiModel {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    // Ambil semua transaksi
    public function getAll() {
        $query = "SELECT t.*, u.nama_lengkap 
                  FROM transaksi t 
                  LEFT JOIN users u ON t.id_user = u.id_user 
                  ORDER BY t.tanggal_transaksi DESC";
        return mysqli_query($this->conn, $query);
    }

    // Tambah transaksi + detail
    public function create($data, $details) {
        mysqli_begin_transaction($this->conn);
        try {
            $kode = mysqli_real_escape_string($this->conn, $data['kode_transaksi']);
            $tanggal = $data['tanggal_transaksi'];
            $jenis = $data['jenis_transaksi'];
            $id_user = $data['id_user'];
            $keterangan = mysqli_real_escape_string($this->conn, $data['keterangan']);
            $total_harga = $data['total_harga'];

            $query = "INSERT INTO transaksi (kode_transaksi, tanggal_transaksi, jenis_transaksi, id_user, keterangan, total_harga)
                      VALUES ('$kode', '$tanggal', '$jenis', '$id_user', '$keterangan', '$total_harga')";
            mysqli_query($this->conn, $query);
            $id_transaksi = mysqli_insert_id($this->conn);

            foreach ($details as $d) {
                $id_produk = $d['id_produk'];
                $jumlah = $d['jumlah'];
                $harga = $d['harga_satuan'];
                mysqli_query($this->conn, 
                    "INSERT INTO detail_transaksi (id_transaksi, id_produk, jumlah, harga_satuan)
                     VALUES ('$id_transaksi', '$id_produk', '$jumlah', '$harga')");
            }

            mysqli_commit($this->conn);
            return true;
        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            return false;
        }
    }

    // Hapus transaksi
    public function delete($id) {
        return mysqli_query($this->conn, "DELETE FROM transaksi WHERE id_transaksi='$id'");
    }
}
?>

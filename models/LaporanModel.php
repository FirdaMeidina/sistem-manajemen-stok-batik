<?php
class LaporanModel {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    // Laporan stok (gabungan produk + total transaksi)
    public function getLaporanStok() {
        $query = "SELECT * FROM laporan_stok";
        return mysqli_query($this->conn, $query);
    }

    // Laporan penjualan per tanggal
    public function getPenjualanByTanggal($start, $end) {
        $query = "SELECT t.*, u.nama_lengkap, SUM(d.subtotal) as total 
                  FROM transaksi t 
                  JOIN detail_transaksi d ON t.id_transaksi = d.id_transaksi
                  JOIN users u ON t.id_user = u.id_user
                  WHERE t.jenis_transaksi='keluar'
                  AND t.tanggal_transaksi BETWEEN '$start' AND '$end'
                  GROUP BY t.id_transaksi";
        return mysqli_query($this->conn, $query);
    }
}
?>

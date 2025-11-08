<?php
include '../../config/koneksi.php';
include '../../config/session_check.php';

$id = $_GET['id'];

// Hapus transaksi (detail_transaksi otomatis terhapus karena ON DELETE CASCADE)
mysqli_query($conn, "DELETE FROM transaksi WHERE id_transaksi = '$id'");

echo "<script>alert('Transaksi berhasil dihapus!');window.location='data_transaksi.php';</script>";

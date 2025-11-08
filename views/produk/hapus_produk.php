<?php
include '../../config/koneksi.php';
include '../../config/session_check.php';

$id = $_GET['id'];

// Hapus file foto jika ada
$q = mysqli_query($conn, "SELECT foto_produk FROM produk WHERE id_produk='$id'");
$d = mysqli_fetch_assoc($q);

if ($d && $d['foto_produk'] && file_exists("../../uploads/foto_produk/" . $d['foto_produk'])) {
    unlink("../../uploads/foto_produk/" . $d['foto_produk']);
}

// Hapus produk dari database
mysqli_query($conn, "DELETE FROM produk WHERE id_produk='$id'");

echo "<script>alert('Produk berhasil dihapus!');window.location='data_produk.php';</script>";
?>

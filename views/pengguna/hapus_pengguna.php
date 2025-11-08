<?php
include '../../config/koneksi.php';
include '../../config/session_check.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM users WHERE id_user=$id");
echo "<script>alert('✅ Pengguna berhasil dihapus!'); window.location='data_pengguna.php';</script>";
exit;
?>

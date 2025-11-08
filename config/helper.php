<?php
// config/helper.php

function check_access($allowed_roles = []) {
  if (!in_array($_SESSION['role'], $allowed_roles)) {
    echo "<script>alert('Akses ditolak! Anda tidak memiliki izin.');history.back();</script>";
    exit;
  }
}

// Fungsi untuk mengamankan input dari SQL Injection dan XSS
function esc($conn, $str) {
    return htmlspecialchars(mysqli_real_escape_string($conn, trim($str)));
}

// Format tanggal Indonesia (kalau belum ada)
function tgl_indo($tanggal) {
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $split = explode('-', $tanggal);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

// Format rupiah (kalau belum ada)
function rupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}
?>

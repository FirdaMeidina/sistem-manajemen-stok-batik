<?php
require('../../vendor/fpdf/fpdf.php');
include '../../config/koneksi.php';
include '../../config/session_check.php';
include '../../config/helper.php';

// Ambil data dari view laporan_stok
$q = mysqli_query($conn, "SELECT * FROM laporan_stok ORDER BY nama_produk ASC");

// ====== Buat objek PDF ======
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// Judul Laporan
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(190, 10, 'LAPORAN STOK BARANG - SISTEM MANAJEMEN STOK BATIK', 0, 1, 'C');
$pdf->Ln(2);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(190, 8, 'Tanggal Cetak: ' . date('d-m-Y H:i:s'), 0, 1, 'C');
$pdf->Ln(4);

// ====== Header tabel ======
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(230, 180, 100);
$pdf->Cell(10, 8, '#', 1, 0, 'C', true);
$pdf->Cell(60, 8, 'Nama Produk', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Harga', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Masuk', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Keluar', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Stok Sekarang', 1, 1, 'C', true);

// ====== Isi tabel ======
$pdf->SetFont('Arial', '', 9);
$no = 1;
$grand_stok = 0;
while ($r = mysqli_fetch_assoc($q)) {
    $pdf->Cell(10, 8, $no++, 1, 0, 'C');
    $pdf->Cell(60, 8, substr($r['nama_produk'], 0, 35), 1, 0);
    $pdf->Cell(25, 8, rupiah($r['harga']), 1, 0, 'R');
    $pdf->Cell(25, 8, $r['total_masuk'], 1, 0, 'C');
    $pdf->Cell(25, 8, $r['total_keluar'], 1, 0, 'C');
    $pdf->Cell(25, 8, $r['stok_saat_ini'], 1, 1, 'C');
    $grand_stok += $r['stok_saat_ini'];
}

// ====== Total ======
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(145, 8, 'TOTAL STOK SAAT INI', 1, 0, 'R');
$pdf->Cell(45, 8, number_format($grand_stok, 0, ',', '.'), 1, 1, 'C');

// ====== Keterangan ======
$pdf->Ln(5);
$pdf->SetFont('Arial', 'I', 9);
$pdf->MultiCell(0, 6, 'Laporan ini dihasilkan otomatis oleh sistem untuk memantau stok barang. Dicetak pada ' . date('d-m-Y H:i') . '.', 0, 'L');

// ====== Simpan otomatis ke folder ======
$path = "../../uploads/dokumen/";
if (!file_exists($path)) mkdir($path, 0777, true);
$filename = "Laporan_Stok_" . date('Ymd_His') . ".pdf";
$pdf->Output('F', $path . $filename); // Simpan ke server

// ====== Download ke user ======
$pdf->Output('D', $filename);
?>

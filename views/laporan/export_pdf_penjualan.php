<?php
require('../../vendor/fpdf/fpdf.php');
include '../../config/koneksi.php';
include '../../config/session_check.php';
include '../../config/helper.php';

$where = "";
if (!empty($_GET['tanggal_dari']) && !empty($_GET['tanggal_sampai'])) {
  $dari = $_GET['tanggal_dari'];
  $sampai = $_GET['tanggal_sampai'];
  $where = "AND t.tanggal_transaksi BETWEEN '$dari' AND '$sampai'";
}

// Ambil data detail_transaksi
$q = mysqli_query($conn, "
  SELECT 
    t.kode_transaksi,
    t.tanggal_transaksi,
    t.keterangan,
    u.nama_lengkap,
    p.nama_produk,
    d.jumlah,
    d.harga_satuan,
    (d.jumlah * d.harga_satuan) AS subtotal
  FROM detail_transaksi d
  INNER JOIN transaksi t ON d.id_transaksi = t.id_transaksi
  INNER JOIN produk p ON d.id_produk = p.id_produk
  LEFT JOIN users u ON t.id_user = u.id_user
  WHERE t.jenis_transaksi = 'keluar' $where
  ORDER BY t.tanggal_transaksi DESC
");

// ===== PDF CONFIG =====
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(190, 10, 'LAPORAN PENJUALAN - SISTEM MANAJEMEN STOK BATIK', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(190, 6, 'Periode: ' . ($_GET['tanggal_dari'] ?? '-') . ' s/d ' . ($_GET['tanggal_sampai'] ?? '-'), 0, 1, 'C');
$pdf->Ln(4);

// Header tabel
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(230, 180, 100);
$pdf->Cell(10, 8, '#', 1, 0, 'C', true);
$pdf->Cell(28, 8, 'Kode', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Tanggal', 1, 0, 'C', true);
$pdf->Cell(35, 8, 'Produk', 1, 0, 'C', true);
$pdf->Cell(15, 8, 'Jumlah', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Harga', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Subtotal', 1, 0, 'C', true);
$pdf->Cell(27, 8, 'User', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 9);
$no = 1;
$grand = 0;
while ($r = mysqli_fetch_assoc($q)) {
  $pdf->Cell(10, 8, $no++, 1, 0, 'C');
  $pdf->Cell(28, 8, $r['kode_transaksi'], 1, 0, 'C');
  $pdf->Cell(25, 8, tgl_indo($r['tanggal_transaksi']), 1, 0, 'C');
  $pdf->Cell(35, 8, substr($r['nama_produk'], 0, 20), 1, 0);
  $pdf->Cell(15, 8, $r['jumlah'], 1, 0, 'C');
  $pdf->Cell(25, 8, rupiah($r['harga_satuan']), 1, 0, 'R');
  $pdf->Cell(25, 8, rupiah($r['subtotal']), 1, 0, 'R');
  $pdf->Cell(27, 8, substr($r['nama_lengkap'], 0, 15), 1, 1);
  $grand += $r['subtotal'];
}

// Total
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(138, 8, 'TOTAL KESELURUHAN', 1, 0, 'R');
$pdf->Cell(52, 8, rupiah($grand), 1, 1, 'R');

// Keterangan laporan
$pdf->Ln(6);
$pdf->SetFont('Arial', 'I', 9);
$pdf->MultiCell(0, 6, 'Keterangan: Laporan ini dihasilkan secara otomatis oleh sistem pada ' . date('d-m-Y H:i') . '.', 0, 'L');

// ===== Simpan otomatis ke uploads/dokumen =====
$path = "../../uploads/dokumen/";
if (!file_exists($path)) mkdir($path, 0777, true);
$filename = "Laporan_Penjualan_" . date('Ymd_His') . ".pdf";
$pdf->Output('F', $path . $filename); // Simpan ke server

// ===== Download ke user =====
$pdf->Output('D', $filename);
?>

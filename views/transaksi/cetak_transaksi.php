<?php
require __DIR__ . '/../../vendor/fpdf/fpdf.php';
include '../../config/koneksi.php';
include '../../config/helper.php';

$id = $_GET['id'];

// Ambil data transaksi + user
$q = mysqli_query($conn, "
  SELECT t.*, u.nama_lengkap 
  FROM transaksi t 
  LEFT JOIN users u ON t.id_user = u.id_user 
  WHERE t.id_transaksi = '$id'
");
$transaksi = mysqli_fetch_assoc($q);

// Ambil detail produk
$d = mysqli_query($conn, "
  SELECT p.nama_produk, dt.jumlah, dt.harga_satuan, (dt.jumlah*dt.harga_satuan) AS subtotal
  FROM detail_transaksi dt
  JOIN produk p ON dt.id_produk = p.id_produk
  WHERE dt.id_transaksi = '$id'
");

// ==============================
// KONFIGURASI STRUK PDF
// ==============================
$pdf = new FPDF('P', 'mm', array(80, 200));
$pdf->AddPage();
$pdf->SetMargins(5, 5, 5);

// Logo & Header
$pdf->Image('../../assets/img/logo.png', 5, 5, 12);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 6, 'Batik Bali Lestari', 0, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 5, 'Jl. Raya Batik No.1, Denpasar - Bali', 0, 1, 'C');
$pdf->Cell(0, 4, 'Telp: (0361) 123456 | batikbalilestari.com', 0, 1, 'C');
$pdf->Ln(2);
$pdf->Cell(0, 1, str_repeat('-', 48), 0, 1, 'C');

// INFO TRANSAKSI
$pdf->SetFont('Arial', '', 8);
$pdf->Ln(2);
$pdf->Cell(25, 5, 'Kode Transaksi', 0, 0);
$pdf->Cell(3, 5, ':', 0, 0);
$pdf->Cell(0, 5, $transaksi['kode_transaksi'], 0, 1);
$pdf->Cell(25, 5, 'Tanggal', 0, 0);
$pdf->Cell(3, 5, ':', 0, 0);
$pdf->Cell(0, 5, tgl_indo($transaksi['tanggal_transaksi']), 0, 1);
$pdf->Cell(25, 5, 'Jenis', 0, 0);
$pdf->Cell(3, 5, ':', 0, 0);
$pdf->Cell(0, 5, ucfirst($transaksi['jenis_transaksi']), 0, 1);
$pdf->Cell(25, 5, 'Petugas', 0, 0);
$pdf->Cell(3, 5, ':', 0, 0);
$pdf->Cell(0, 5, $transaksi['nama_lengkap'], 0, 1);
$pdf->Cell(25, 5, 'Keterangan', 0, 0);
$pdf->Cell(3, 5, ':', 0, 0);
$pdf->MultiCell(0, 5, $transaksi['keterangan'] ?: '-', 0, 'L');
$pdf->Cell(0, 2, str_repeat('-', 48), 0, 1, 'C');
$pdf->Ln(1);

// TABEL PRODUK
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(28, 5, 'Nama Produk', 0, 0);
$pdf->Cell(10, 5, 'Qty', 0, 0, 'C');
$pdf->Cell(18, 5, 'Harga', 0, 0, 'R');
$pdf->Cell(18, 5, 'Subtotal', 0, 1, 'R');

$pdf->SetFont('Arial', '', 8);
$total = 0;
while ($r = mysqli_fetch_assoc($d)) {
    $pdf->Cell(28, 5, substr($r['nama_produk'], 0, 18), 0, 0);
    $pdf->Cell(10, 5, $r['jumlah'], 0, 0, 'C');
    $pdf->Cell(18, 5, number_format($r['harga_satuan'], 0, ',', '.'), 0, 0, 'R');
    $pdf->Cell(18, 5, number_format($r['subtotal'], 0, ',', '.'), 0, 1, 'R');
    $total += $r['subtotal'];
}
$pdf->Cell(0, 2, str_repeat('-', 48), 0, 1, 'C');
$pdf->Ln(1);

// TOTAL
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(45, 6, 'TOTAL', 0, 0, 'R');
$pdf->Cell(25, 6, 'Rp ' . number_format($total, 0, ',', '.'), 0, 1, 'R');
$pdf->Ln(3);

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 5, 'Denpasar, ' . date('d M Y'), 0, 1, 'R');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 4, 'Terima kasih telah bertransaksi.', 0, 1, 'C');
$pdf->Cell(0, 4, 'Barang yang sudah keluar tidak dapat dikembalikan.', 0, 1, 'C');
$pdf->Ln(3);
$pdf->Cell(0, 1, str_repeat('-', 48), 0, 1, 'C');
$pdf->Cell(0, 4, 'Nota ini dicetak otomatis oleh sistem', 0, 1, 'C');

// ==============================
// SIMPAN KE FOLDER uploads/dokumen
// ==============================
$folder = '../../uploads/dokumen/';
$filename = 'Nota-' . $transaksi['kode_transaksi'] . '.pdf';
$filepath = $folder . $filename;

// Buat folder jika belum ada
if (!is_dir($folder)) mkdir($folder, 0777, true);

// Simpan PDF
$pdf->Output('F', $filepath); // Simpan ke file

// Tampilkan PDF di browser
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"');
readfile($filepath);
exit;

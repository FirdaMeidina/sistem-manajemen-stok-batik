<?php
include '../../config/koneksi.php';
include '../../config/session_check.php';
include '../../config/helper.php';

$where = "";
if (!empty($_GET['tanggal_dari']) && !empty($_GET['tanggal_sampai'])) {
  $dari = $_GET['tanggal_dari'];
  $sampai = $_GET['tanggal_sampai'];
  $where = "AND t.tanggal_transaksi BETWEEN '$dari' AND '$sampai'";
}

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

ob_start();
?>
<h3 style="text-align:center;">LAPORAN PENJUALAN<br>Sistem Manajemen Stok Batik</h3>
<p style="text-align:center;">Periode: <?= $_GET['tanggal_dari'] ?? '-'; ?> s/d <?= $_GET['tanggal_sampai'] ?? '-'; ?></p>

<table border="1" cellspacing="0" cellpadding="5">
  <thead style="background-color:#f7c67b;">
    <tr>
      <th>#</th>
      <th>Kode Transaksi</th>
      <th>Tanggal</th>
      <th>Produk</th>
      <th>Jumlah</th>
      <th>Harga Satuan</th>
      <th>Subtotal</th>
      <th>User</th>
      <th>Keterangan</th>
    </tr>
  </thead>
  <tbody>
<?php
$no = 1;
$grand = 0;
while ($r = mysqli_fetch_assoc($q)):
  $grand += $r['subtotal'];
?>
  <tr>
    <td><?= $no++; ?></td>
    <td><?= $r['kode_transaksi']; ?></td>
    <td><?= $r['tanggal_transaksi']; ?></td>
    <td><?= $r['nama_produk']; ?></td>
    <td><?= $r['jumlah']; ?></td>
    <td><?= rupiah($r['harga_satuan']); ?></td>
    <td><?= rupiah($r['subtotal']); ?></td>
    <td><?= $r['nama_lengkap']; ?></td>
    <td><?= $r['keterangan']; ?></td>
  </tr>
<?php endwhile; ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="6" align="right"><b>Total Keseluruhan</b></td>
      <td colspan="3"><b><?= rupiah($grand); ?></b></td>
    </tr>
  </tfoot>
</table>
<?php
$html = ob_get_clean();

// ==== Simpan otomatis ke server ====
$path = "../../uploads/dokumen/";
if (!file_exists($path)) mkdir($path, 0777, true);
$filename = "Laporan_Penjualan_" . date('Ymd_His') . ".xls";
file_put_contents($path . $filename, $html);

// ==== Kirim ke browser (download) ====
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$filename");
echo $html;
exit;
?>

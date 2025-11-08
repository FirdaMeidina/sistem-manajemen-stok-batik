<?php
include '../../config/koneksi.php';
include '../../config/session_check.php';
include '../../config/helper.php';

$q = mysqli_query($conn, "SELECT * FROM laporan_stok ORDER BY nama_produk ASC");

ob_start();
?>
<h3 style="text-align:center;">LAPORAN STOK BARANG<br>Sistem Manajemen Stok Batik</h3>
<p style="text-align:center;">Tanggal Cetak: <?= date('d-m-Y H:i:s'); ?></p>

<table border="1" cellspacing="0" cellpadding="5">
  <thead style="background-color:#f7c67b;">
    <tr>
      <th>#</th>
      <th>Nama Produk</th>
      <th>Harga</th>
      <th>Total Masuk</th>
      <th>Total Keluar</th>
      <th>Stok Saat Ini</th>
    </tr>
  </thead>
  <tbody>
<?php
$no = 1;
$total_stok = 0;
while ($r = mysqli_fetch_assoc($q)):
  $total_stok += $r['stok_saat_ini'];
?>
  <tr>
    <td><?= $no++; ?></td>
    <td><?= htmlspecialchars($r['nama_produk']); ?></td>
    <td><?= rupiah($r['harga']); ?></td>
    <td><?= $r['total_masuk']; ?></td>
    <td><?= $r['total_keluar']; ?></td>
    <td><?= $r['stok_saat_ini']; ?></td>
  </tr>
<?php endwhile; ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="5" align="right"><b>Total Keseluruhan Stok</b></td>
      <td><b><?= number_format($total_stok, 0, ',', '.'); ?></b></td>
    </tr>
  </tfoot>
</table>
<?php
$html = ob_get_clean();

// ====== Simpan otomatis ke folder ======
$path = "../../uploads/dokumen/";
if (!file_exists($path)) mkdir($path, 0777, true);
$filename = "Laporan_Stok_" . date('Ymd_His') . ".xls";
file_put_contents($path . $filename, $html);

// ====== Download langsung ke user ======
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$filename");
echo $html;
exit;
?>

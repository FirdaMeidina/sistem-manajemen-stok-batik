<?php
include '../../config/koneksi.php';
include '../../config/session_check.php';
include '../../config/helper.php';
check_access(['admin', 'kasir', 'owner']);

$title = "Laporan Penjualan | Sistem Manajemen Stok Batik";
include '../includes/header.php';

// ==== Filter tanggal ====
$where = "";
if (!empty($_GET['tanggal_dari']) && !empty($_GET['tanggal_sampai'])) {
  $dari = $_GET['tanggal_dari'];
  $sampai = $_GET['tanggal_sampai'];
  $where = "AND t.tanggal_transaksi BETWEEN '$dari' AND '$sampai'";
}

// ==== Query ambil data detail_transaksi ====
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
  ORDER BY t.tanggal_transaksi DESC, t.id_transaksi DESC
");
?>

<h4><i class="fa-solid fa-file-invoice-dollar me-2"></i>Laporan Penjualan</h4>

<!-- Form filter tanggal -->
<form method="GET" class="row g-3 mb-4">
  <div class="col-md-4">
    <label>Dari Tanggal</label>
    <input type="date" name="tanggal_dari" class="form-control" value="<?= $_GET['tanggal_dari'] ?? ''; ?>">
  </div>
  <div class="col-md-4">
    <label>Sampai Tanggal</label>
    <input type="date" name="tanggal_sampai" class="form-control" value="<?= $_GET['tanggal_sampai'] ?? ''; ?>">
  </div>
  <div class="col-md-4 d-flex align-items-end">
    <button class="btn btn-primary me-2"><i class="fa-solid fa-filter me-1"></i> Filter</button>
    <a href="export_pdf_penjualan.php?type=penjualan_detail<?= !empty($_GET['tanggal_dari']) ? '&tanggal_dari='.$_GET['tanggal_dari'].'&tanggal_sampai='.$_GET['tanggal_sampai'] : ''; ?>" 
       class="btn btn-danger"><i class="fa-solid fa-file-pdf me-1"></i> PDF</a>
    <a href="export_excel_penjualan.php?type=penjualan_detail<?= !empty($_GET['tanggal_dari']) ? '&tanggal_dari='.$_GET['tanggal_dari'].'&tanggal_sampai='.$_GET['tanggal_sampai'] : ''; ?>" 
       class="btn btn-success ms-2"><i class="fa-solid fa-file-excel me-1"></i> Excel</a>
  </div>
</form>

<!-- Tabel laporan -->
<div class="table-responsive">
  <table class="table table-striped table-hover align-middle">
    <thead class="table-warning text-center">
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
      $grand_total = 0;
      while ($r = mysqli_fetch_assoc($q)): 
        $grand_total += $r['subtotal'];
      ?>
      <tr>
        <td class="text-center"><?= $no++; ?></td>
        <td><?= htmlspecialchars($r['kode_transaksi']); ?></td>
        <td><?= tgl_indo($r['tanggal_transaksi']); ?></td>
        <td><?= htmlspecialchars($r['nama_produk']); ?></td>
        <td class="text-center"><?= (int)$r['jumlah']; ?></td>
        <td class="text-end"><?= rupiah($r['harga_satuan']); ?></td>
        <td class="text-end"><?= rupiah($r['subtotal']); ?></td>
        <td><?= htmlspecialchars($r['nama_lengkap'] ?? '-'); ?></td>
        <td><?= htmlspecialchars($r['keterangan'] ?? '-'); ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
    <tfoot class="table-light fw-bold">
      <tr>
        <td colspan="6" class="text-end">Total Keseluruhan:</td>
        <td colspan="3" class="text-start"><?= rupiah($grand_total); ?></td>
      </tr>
    </tfoot>
  </table>
</div>

<?php include '../includes/footer.php'; ?>

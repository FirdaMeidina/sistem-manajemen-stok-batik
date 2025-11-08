<?php
include '../../config/koneksi.php';
include '../../config/session_check.php';
include '../../config/helper.php';

$title = "Detail Transaksi | Sistem Manajemen Stok Batik";
include '../includes/header.php';

if (!isset($_GET['id'])) {
  echo "<script>alert('ID Transaksi tidak ditemukan!');window.location='data_transaksi.php';</script>";
  exit;
}

$id = $_GET['id'];

// Ambil data utama transaksi
$q_trans = mysqli_query($conn, "
  SELECT t.*, u.nama_lengkap 
  FROM transaksi t
  LEFT JOIN users u ON t.id_user = u.id_user
  WHERE t.id_transaksi = '$id'
");
$trans = mysqli_fetch_assoc($q_trans);

if (!$trans) {
  echo "<script>alert('Data transaksi tidak ditemukan!');window.location='data_transaksi.php';</script>";
  exit;
}

// Ambil detail produk pada transaksi ini
$q_detail = mysqli_query($conn, "
  SELECT d.*, p.nama_produk, p.kode_produk 
  FROM detail_transaksi d
  LEFT JOIN produk p ON d.id_produk = p.id_produk
  WHERE d.id_transaksi = '$id'
");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4><i class="fa-solid fa-file-invoice me-2"></i>Detail Transaksi</h4>
  <a href="data_transaksi.php" class="btn btn-secondary">
    <i class="fa-solid fa-arrow-left me-1"></i> Kembali
  </a>
</div>

<!-- INFORMASI TRANSAKSI -->
<div class="card mb-4 shadow-sm">
  <div class="card-body">
    <h5 class="mb-3 text-primary"><i class="fa-solid fa-receipt me-2"></i>Informasi Transaksi</h5>
    <div class="row">
      <div class="col-md-6">
        <p><strong>Kode Transaksi:</strong> <?= htmlspecialchars($trans['kode_transaksi']); ?></p>
        <p><strong>Tanggal:</strong> <?= tgl_indo($trans['tanggal_transaksi']); ?></p>
        <p><strong>Jenis Transaksi:</strong> 
          <span class="badge <?= $trans['jenis_transaksi'] == 'masuk' ? 'bg-success' : 'bg-danger'; ?>">
            <?= strtoupper($trans['jenis_transaksi']); ?>
          </span>
        </p>
      </div>
      <div class="col-md-6">
        <p><strong>Pengguna:</strong> <?= htmlspecialchars($trans['nama_lengkap'] ?? '-'); ?></p>
        <p><strong>Total Harga:</strong> <?= rupiah($trans['total_harga']); ?></p>
        <p><strong>Keterangan:</strong> <?= htmlspecialchars($trans['keterangan'] ?? '-'); ?></p>
      </div>
    </div>
  </div>
</div>

<!-- DETAIL PRODUK -->
<div class="card shadow-sm">
  <div class="card-body">
    <h5 class="mb-3 text-primary"><i class="fa-solid fa-boxes me-2"></i>Daftar Produk</h5>
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle">
        <thead class="table-warning text-center">
          <tr>
            <th>#</th>
            <th>Kode Produk</th>
            <th>Nama Produk</th>
            <th>Jumlah</th>
            <th>Harga Satuan</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $no = 1; 
          $total = 0;
          while ($r = mysqli_fetch_assoc($q_detail)): 
          $subtotal = $r['jumlah'] * $r['harga_satuan'];
          $total += $subtotal;
          ?>
          <tr>
            <td class="text-center"><?= $no++; ?></td>
            <td><?= htmlspecialchars($r['kode_produk']); ?></td>
            <td><?= htmlspecialchars($r['nama_produk']); ?></td>
            <td class="text-center"><?= (int)$r['jumlah']; ?></td>
            <td><?= rupiah($r['harga_satuan']); ?></td>
            <td><?= rupiah($subtotal); ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
        <tfoot class="table-light">
          <tr>
            <th colspan="5" class="text-end">Total</th>
            <th><?= rupiah($total); ?></th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>

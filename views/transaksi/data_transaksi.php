<?php
include '../../config/koneksi.php';
include '../../config/session_check.php';
include '../../config/helper.php';
check_access(['admin', 'staf_gudang', 'kasir']);
$title = "Data Transaksi | Sistem Manajemen Stok Batik";
include '../includes/header.php';

// Ambil data transaksi + nama user
$q = mysqli_query($conn, "
  SELECT t.*, u.nama_lengkap 
  FROM transaksi t 
  LEFT JOIN users u ON t.id_user = u.id_user 
  ORDER BY t.tanggal_transaksi DESC
");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4><i class="fa-solid fa-exchange-alt me-2"></i>Data Transaksi</h4>
  <div>
    <a href="stok_masuk.php" class="btn btn-success me-2">
      <i class="fa-solid fa-plus me-1"></i> Stok Masuk
    </a>
    <a href="stok_keluar.php" class="btn btn-danger">
      <i class="fa-solid fa-minus me-1"></i> Stok Keluar
    </a>
  </div>
</div>

<!-- Tabel responsif -->
<div class="table-responsive">
  <table class="table table-hover table-striped align-middle">
    <thead class="table-warning text-center">
      <tr>
        <th>#</th>
        <th>Kode Transaksi</th>
        <th>Tanggal</th>
        <th>Jenis</th>
        <th>User</th>
        <th>Total Harga</th>
        <th>Keterangan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $no = 1; 
      while ($r = mysqli_fetch_assoc($q)): 
      ?>
      <tr>
        <td class="text-center"><?= $no++; ?></td>
        <td><?= htmlspecialchars($r['kode_transaksi']); ?></td>
        <td><?= tgl_indo($r['tanggal_transaksi']); ?></td>
        <td class="text-center">
          <span class="badge <?= $r['jenis_transaksi'] == 'masuk' ? 'bg-success' : 'bg-danger'; ?>">
            <?= strtoupper($r['jenis_transaksi']); ?>
          </span>
        </td>
        <td><?= htmlspecialchars($r['nama_lengkap'] ?? '-'); ?></td>
        <td><?= rupiah($r['total_harga']); ?></td>
        <td><?= htmlspecialchars($r['keterangan'] ?? '-'); ?></td>
        <td class="text-center">
          <!-- Detail -->
          <a href="detail_transaksi.php?id=<?= $r['id_transaksi']; ?>" 
             class="btn btn-sm btn-info" title="Lihat Detail">
            <i class="fa-solid fa-eye"></i>
          </a>

          <!-- Edit -->
          <a href="edit_transaksi.php?id=<?= $r['id_transaksi']; ?>" 
             class="btn btn-sm btn-warning" title="Edit Transaksi">
            <i class="fa-solid fa-pen"></i>
          </a>

          <!-- Cetak PDF -->
          <a href="cetak_transaksi.php?id=<?= $r['id_transaksi']; ?>" 
             target="_blank" class="btn btn-sm btn-success" title="Cetak Nota PDF">
            <i class="fa-solid fa-file-pdf"></i>
          </a>

          <!-- Hapus -->
          <a href="hapus_transaksi.php?id=<?= $r['id_transaksi']; ?>" 
             class="btn btn-sm btn-danger" 
             title="Hapus Transaksi"
             onclick="return confirm('Yakin ingin menghapus transaksi ini? Data detail juga akan terhapus!');">
            <i class="fa-solid fa-trash"></i>
          </a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include '../includes/footer.php'; ?>

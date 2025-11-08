<?php
include '../../config/koneksi.php';
include '../../config/session_check.php';
include '../../config/helper.php';
check_access(['admin', 'staf_gudang']);

$title = "Data Produk | Sistem Manajemen Stok Batik";
include '../includes/header.php';

// Ambil data produk dan kategori
$q = mysqli_query($conn, "
  SELECT p.*, k.nama_kategori 
  FROM produk p 
  LEFT JOIN kategori k ON p.id_kategori = k.id_kategori 
  ORDER BY p.id_produk DESC
");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4><i class="fa-solid fa-shirt me-2"></i>Data Produk</h4>
  <a href="tambah_produk.php" class="btn btn-success">
    <i class="fa-solid fa-plus me-1"></i> Tambah Produk
  </a>
</div>

<div class="table-responsive">
  <table class="table table-striped table-hover align-middle">
    <thead class="table-warning text-center">
      <tr>
        <th>#</th>
        <th>Kode Produk</th>
        <th>Nama Produk</th>
        <th>Kategori</th>
        <th>Jenis Batik</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Lokasi Simpan</th>
        <th>Foto</th>
        <th>Tanggal Ditambahkan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; while ($r = mysqli_fetch_assoc($q)): ?>
      <tr>
        <td class="text-center"><?= $no++; ?></td>
        <td><?= htmlspecialchars($r['kode_produk']); ?></td>
        <td><?= htmlspecialchars($r['nama_produk']); ?></td>
        <td><?= htmlspecialchars($r['nama_kategori'] ?? '-'); ?></td>
        <td><?= htmlspecialchars($r['jenis_batik'] ?? '-'); ?></td>
        <td><?= rupiah($r['harga']); ?></td>
        <td class="text-center"><?= (int)$r['stok']; ?></td>
        <td><?= htmlspecialchars($r['lokasi_simpan'] ?? '-'); ?></td>
        <td class="text-center">
          <?php if (!empty($r['foto_produk']) && file_exists("../../uploads/foto_produk/" . $r['foto_produk'])): ?>
            <img src="../../uploads/foto_produk/<?= htmlspecialchars($r['foto_produk']); ?>" 
                 alt="Foto Produk" width="60" height="60" 
                 style="object-fit: cover; border-radius: 6px;">
          <?php else: ?>
            <span class="text-muted">-</span>
          <?php endif; ?>
        </td>
        <td><?= date('d M Y', strtotime($r['tanggal_ditambahkan'])); ?></td>
        <td class="text-center">
          <a href="edit_produk.php?id=<?= $r['id_produk']; ?>" class="btn btn-sm btn-warning">
            <i class="fa-solid fa-pen"></i>
          </a>
          <a href="hapus_produk.php?id=<?= $r['id_produk']; ?>" 
             class="btn btn-sm btn-danger"
             onclick="return confirm('Yakin ingin menghapus produk ini?');">
            <i class="fa-solid fa-trash"></i>
          </a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include '../includes/footer.php'; ?>

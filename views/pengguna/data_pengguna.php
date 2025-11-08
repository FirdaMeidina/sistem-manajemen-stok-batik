<?php
include '../../config/koneksi.php';
include '../../config/session_check.php';
include '../../config/helper.php';
check_access(['admin']);
$title = "Data Pengguna | Sistem Manajemen Stok Batik";
include '../includes/header.php';

$q = mysqli_query($conn, "SELECT * FROM users ORDER BY role ASC, nama_lengkap ASC");
?>
<h4><i class="fa-solid fa-users me-2"></i>Daftar Pengguna</h4>

<a href="tambah_pengguna.php" class="btn btn-primary mb-3"><i class="fa-solid fa-user-plus me-1"></i> Tambah Pengguna</a>

<table class="table table-bordered table-striped align-middle">
  <thead class="table-warning text-center">
    <tr>
      <th>#</th>
      <th>Nama Lengkap</th>
      <th>Username</th>
      <th>Role</th>
      <th>Status</th>
      <th>Email</th>
      <th>Tanggal Dibuat</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $no=1; while($r=mysqli_fetch_assoc($q)): ?>
    <tr>
      <td class="text-center"><?= $no++; ?></td>
      <td><?= htmlspecialchars($r['nama_lengkap']); ?></td>
      <td><?= htmlspecialchars($r['username']); ?></td>
      <td><?= ucfirst($r['role']); ?></td>
      <td><?= $r['status_aktif'] == 'aktif' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>'; ?></td>
      <td><?= htmlspecialchars($r['email']); ?></td>
      <td><?= tgl_indo(substr($r['tanggal_dibuat'],0,10)); ?></td>
      <td class="text-center">
        <a href="edit_pengguna.php?id=<?= $r['id_user']; ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen"></i></a>
        <a href="hapus_pengguna.php?id=<?= $r['id_user']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pengguna ini?')"><i class="fa-solid fa-trash"></i></a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include '../includes/footer.php'; ?>

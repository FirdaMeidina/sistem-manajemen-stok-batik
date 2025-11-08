<?php
include '../../config/koneksi.php';
include '../../config/session_check.php';
include '../../config/helper.php';

$title = "Edit Produk | Sistem Manajemen Stok Batik";
include '../includes/header.php';

$id = $_GET['id'];
$q = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk='$id'");
$d = mysqli_fetch_assoc($q);

$kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

if (isset($_POST['update'])) {
  $nama = esc($conn, $_POST['nama_produk']);
  $idkat = esc($conn, $_POST['id_kategori']);
  $jenis = esc($conn, $_POST['jenis_batik']);
  $harga = esc($conn, $_POST['harga']);
  $stok = esc($conn, $_POST['stok']);
  $lokasi = esc($conn, $_POST['lokasi_simpan']);
  $foto = $d['foto_produk'];

  if (!empty($_FILES['foto_produk']['name'])) {
    $target_dir = "../../uploads/foto_produk/";
    if (file_exists($target_dir . $foto)) unlink($target_dir . $foto);
    $foto = 'produk_' . time() . '_' . basename($_FILES['foto_produk']['name']);
    move_uploaded_file($_FILES['foto_produk']['tmp_name'], $target_dir . $foto);
  }

  mysqli_query($conn, "UPDATE produk SET 
                      id_kategori='$idkat',
                      nama_produk='$nama',
                      jenis_batik='$jenis',
                      harga='$harga',
                      stok='$stok',
                      lokasi_simpan='$lokasi',
                      foto_produk='$foto'
                      WHERE id_produk='$id'");

  echo "<script>alert('Produk berhasil diperbarui!');window.location='data_produk.php';</script>";
}
?>

<h4><i class="fa-solid fa-pen me-2"></i>Edit Produk</h4>

<form method="POST" enctype="multipart/form-data">
  <div class="mb-3">
    <label>Nama Produk</label>
    <input type="text" name="nama_produk" value="<?= htmlspecialchars($d['nama_produk']); ?>" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Kategori</label>
    <select name="id_kategori" class="form-select">
      <?php while($k=mysqli_fetch_assoc($kategori)): ?>
        <option value="<?= $k['id_kategori']; ?>" <?= $d['id_kategori']==$k['id_kategori']?'selected':''; ?>>
          <?= $k['nama_kategori']; ?>
        </option>
      <?php endwhile; ?>
    </select>
  </div>

  <div class="mb-3">
    <label>Jenis Batik</label>
    <input type="text" name="jenis_batik" value="<?= htmlspecialchars($d['jenis_batik']); ?>" class="form-control">
  </div>

  <div class="row mb-3">
    <div class="col-md-4">
      <label>Harga</label>
      <input type="number" name="harga" value="<?= $d['harga']; ?>" class="form-control">
    </div>
    <div class="col-md-4">
      <label>Stok</label>
      <input type="number" name="stok" value="<?= $d['stok']; ?>" class="form-control">
    </div>
    <div class="col-md-4">
      <label>Lokasi Simpan</label>
      <input type="text" name="lokasi_simpan" value="<?= htmlspecialchars($d['lokasi_simpan']); ?>" class="form-control">
    </div>
  </div>

  <div class="mb-3">
    <label>Foto Produk</label><br>
    <?php if($d['foto_produk']): ?>
      <img src="../../uploads/foto_produk/<?= $d['foto_produk']; ?>" width="100" class="mb-2 rounded">
    <?php endif; ?>
    <input type="file" name="foto_produk" class="form-control">
  </div>

  <button type="submit" name="update" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Update</button>
  <a href="data_produk.php" class="btn btn-secondary">Kembali</a>
</form>

<?php include '../includes/footer.php'; ?>

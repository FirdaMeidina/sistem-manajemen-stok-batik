<?php
include '../../config/koneksi.php';
include '../../config/session_check.php';
include '../../config/helper.php';

$title = "Tambah Produk | Sistem Manajemen Stok Batik";
include '../includes/header.php';

// Ambil daftar kategori
$kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

if (isset($_POST['simpan'])) {
  $kode = esc($conn, $_POST['kode_produk']);
  $nama = esc($conn, $_POST['nama_produk']);
  $idkat = esc($conn, $_POST['id_kategori']);
  $jenis = esc($conn, $_POST['jenis_batik']);
  $harga = esc($conn, $_POST['harga']);
  $stok = esc($conn, $_POST['stok']);
  $lokasi = esc($conn, $_POST['lokasi_simpan']);
  $foto = null;

  // Upload foto
  if (!empty($_FILES['foto_produk']['name'])) {
    $target_dir = "../../uploads/foto_produk/";
    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
    $foto = 'produk_' . time() . '_' . basename($_FILES['foto_produk']['name']);
    move_uploaded_file($_FILES['foto_produk']['tmp_name'], $target_dir . $foto);
  }

  mysqli_query($conn, "INSERT INTO produk 
    (id_kategori, kode_produk, nama_produk, jenis_batik, harga, stok, lokasi_simpan, foto_produk)
    VALUES ('$idkat', '$kode', '$nama', '$jenis', '$harga', '$stok', '$lokasi', '$foto')
  ");

  echo "<script>alert('Produk berhasil ditambahkan!');window.location='data_produk.php';</script>";
}
?>

<h4><i class="fa-solid fa-plus me-2"></i>Tambah Produk</h4>

<form method="POST" enctype="multipart/form-data">
  <div class="row mb-3">
    <div class="col-md-6">
      <label>Kode Produk</label>
      <input type="text" name="kode_produk" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label>Nama Produk</label>
      <input type="text" name="nama_produk" class="form-control" required>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-6">
      <label>Kategori</label>
      <select name="id_kategori" class="form-select" required>
        <option value="">-- Pilih Kategori --</option>
        <?php while($k=mysqli_fetch_assoc($kategori)): ?>
          <option value="<?= $k['id_kategori']; ?>"><?= $k['nama_kategori']; ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="col-md-6">
      <label>Jenis Batik</label>
      <input type="text" name="jenis_batik" class="form-control">
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-4">
      <label>Harga</label>
      <input type="number" name="harga" class="form-control" required>
    </div>
    <div class="col-md-4">
      <label>Stok</label>
      <input type="number" name="stok" class="form-control" required>
    </div>
    <div class="col-md-4">
      <label>Lokasi Simpan</label>
      <input type="text" name="lokasi_simpan" class="form-control">
    </div>
  </div>

  <div class="mb-3">
    <label>Foto Produk</label>
    <input type="file" name="foto_produk" class="form-control">
  </div>

  <button type="submit" name="simpan" class="btn btn-success"><i class="fa-solid fa-save me-1"></i> Simpan</button>
  <a href="data_produk.php" class="btn btn-secondary">Kembali</a>
</form>

<?php include '../includes/footer.php'; ?>

<?php
include '../../config/koneksi.php';
include '../../config/session_check.php';
include '../../config/helper.php';
$title = "Stok Keluar | Sistem Manajemen Stok Batik";
include '../includes/header.php';

// Ambil semua produk
$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY nama_produk ASC");

if (isset($_POST['simpan'])) {
    $kode = 'OUT' . time();
    $id_user = $_SESSION['id_user'];
    $tanggal = date('Y-m-d');
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];
    $harga = $_POST['harga'];
    $keterangan = esc($conn, $_POST['keterangan']);
    $total = $jumlah * $harga;

    // Cek stok cukup atau tidak
    $cek = mysqli_query($conn, "SELECT stok FROM produk WHERE id_produk='$id_produk'");
    $stok = mysqli_fetch_assoc($cek)['stok'];

    if ($jumlah > $stok) {
        echo "<script>alert('Stok tidak mencukupi!');history.back();</script>";
        exit;
    }

    // Simpan ke transaksi
    mysqli_query($conn, "INSERT INTO transaksi (kode_transaksi, tanggal_transaksi, jenis_transaksi, id_user, keterangan, total_harga)
                        VALUES ('$kode', '$tanggal', 'keluar', '$id_user', '$keterangan', '$total')");
    $id_transaksi = mysqli_insert_id($conn);

    // Simpan detail transaksi
    mysqli_query($conn, "INSERT INTO detail_transaksi (id_transaksi, id_produk, jumlah, harga_satuan)
                        VALUES ('$id_transaksi', '$id_produk', '$jumlah', '$harga')");

    // Update stok produk
    mysqli_query($conn, "UPDATE produk SET stok = stok - $jumlah WHERE id_produk='$id_produk'");

    echo "<script>alert('Stok keluar berhasil disimpan!');window.location='data_transaksi.php';</script>";
}
?>

<h4><i class="fa-solid fa-box-open me-2"></i>Tambah Stok Keluar</h4>

<form method="POST">
    <div class="mb-3">
        <label>Produk</label>
        <select name="id_produk" id="id_produk" class="form-select" required>
            <option value="">-- Pilih Produk --</option>
            <?php while($p=mysqli_fetch_assoc($produk)): ?>
                <option value="<?= $p['id_produk']; ?>" data-harga="<?= $p['harga']; ?>">
                    <?= $p['nama_produk']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Jumlah</label>
        <input type="number" name="jumlah" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Harga Satuan</label>
        <input type="number" name="harga" id="harga" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Keterangan</label>
        <textarea name="keterangan" class="form-control" rows="2"></textarea>
    </div>

    <button type="submit" name="simpan" class="btn btn-danger"><i class="fa-solid fa-save me-1"></i> Simpan</button>
    <a href="data_transaksi.php" class="btn btn-secondary">Kembali</a>
</form>

<script>
document.getElementById('id_produk').addEventListener('change', function(){
    var selected = this.options[this.selectedIndex];
    var harga = selected.getAttribute('data-harga');
    document.getElementById('harga').value = harga ? harga : '';
});
</script>

<?php include '../includes/footer.php'; ?>

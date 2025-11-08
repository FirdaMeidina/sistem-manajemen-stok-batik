<?php
include '../../config/koneksi.php';
include '../../config/session_check.php';
include '../../config/helper.php';
$title = "Edit Pengguna | Sistem Manajemen Stok Batik";
include '../includes/header.php';

$id = $_GET['id'];
$q = mysqli_query($conn, "SELECT * FROM users WHERE id_user=$id");
$r = mysqli_fetch_assoc($q);

if (isset($_POST['update'])) {
    $nama = esc($conn, $_POST['nama_lengkap']);
    $username = esc($conn, $_POST['username']);
    $email = esc($conn, $_POST['email']);
    $role = esc($conn, $_POST['role']);
    $status = esc($conn, $_POST['status_aktif']);

    $update = "UPDATE users SET nama_lengkap='$nama', username='$username', email='$email', role='$role', status_aktif='$status'";
    if (!empty($_POST['password'])) {
        $pass = md5($_POST['password']);
        $update .= ", password='$pass'";
    }
    $update .= " WHERE id_user=$id";

    if (mysqli_query($conn, $update)) {
        echo "<script>alert('✅ Data berhasil diperbarui!'); window.location='data_pengguna.php';</script>";
        exit;
    } else {
        $error = "⚠️ Gagal memperbarui data!";
    }
}
?>

<h4><i class="fa-solid fa-user-pen me-2"></i>Edit Pengguna</h4>
<?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

<form method="POST">
    <div class="row mb-3">
        <div class="col-md-6">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($r['nama_lengkap']); ?>" required>
        </div>
        <div class="col-md-6">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($r['username']); ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label>Password (kosongkan jika tidak diubah)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="col-md-6">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($r['email']); ?>">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label>Role</label>
            <select name="role" class="form-select" required>
                <?php
                // Ambil semua nilai ENUM dari kolom 'role'
                $result = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'role'");
                $row_enum = mysqli_fetch_assoc($result);
                $type = $row_enum['Type']; // contoh: enum('admin','staf_gudang','kasir','owner')
                $enumValues = str_getcsv(substr($type, 5, -1), ',', "'"); // ubah menjadi array

                foreach ($enumValues as $val) {
                    $selected = ($r['role'] == $val) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($val) . "' $selected>" . ucfirst(htmlspecialchars($val)) . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-6">
            <label>Status</label>
            <select name="status_aktif" class="form-select" required>
                <option value="aktif" <?= $r['status_aktif']=='aktif'?'selected':''; ?>>Aktif</option>
                <option value="nonaktif" <?= $r['status_aktif']=='nonaktif'?'selected':''; ?>>Nonaktif</option>
            </select>
        </div>
    </div>

    <button name="update" class="btn btn-success"><i class="fa-solid fa-save me-1"></i> Update</button>
    <a href="data_pengguna.php" class="btn btn-secondary">Kembali</a>
</form>

<?php include '../includes/footer.php'; ?>

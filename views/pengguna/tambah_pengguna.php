<?php
include '../../config/koneksi.php';
include '../../config/session_check.php';
include '../../config/helper.php';
$title = "Tambah Pengguna | Sistem Manajemen Stok Batik";
include '../includes/header.php';

if (isset($_POST['simpan'])) {
    $nama = esc($conn, $_POST['nama_lengkap']);
    $username = esc($conn, $_POST['username']);
    $password = md5($_POST['password']);
    $email = esc($conn, $_POST['email']);
    $role = esc($conn, $_POST['role']);
    $status = esc($conn, $_POST['status_aktif']);

    // Cek apakah username sudah digunakan
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "❌ Username sudah digunakan!";
    } else {
        $q = mysqli_query($conn, "INSERT INTO users (nama_lengkap, username, password, email, role, status_aktif) VALUES ('$nama','$username','$password','$email','$role','$status')");
        if ($q) {
            echo "<script>alert('✅ Pengguna berhasil ditambahkan!'); window.location='data_pengguna.php';</script>";
            exit;
        } else {
            $error = "⚠️ Gagal menyimpan data!";
        }
    }
}
?>

<h4><i class="fa-solid fa-user-plus me-2"></i>Tambah Pengguna</h4>
<?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

<form method="POST">
    <div class="row mb-3">
        <div class="col-md-6">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label>Role</label>
            <select name="role" class="form-select" required>
                <?php
                // Ambil semua nilai enum dari kolom 'role'
                $result = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'role'");
                $row = mysqli_fetch_assoc($result);
                $type = $row['Type']; // contoh: enum('admin','staf_gudang','kasir','owner')
                $enumValues = str_getcsv(substr($type, 5, -1), ',', "'"); // ubah menjadi array

                foreach ($enumValues as $val) {
                    echo "<option value='" . htmlspecialchars($val) . "'>" . ucfirst(htmlspecialchars($val)) . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-6">
            <label>Status</label>
            <select name="status_aktif" class="form-select" required>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>
    </div>

    <button name="simpan" class="btn btn-success"><i class="fa-solid fa-save me-1"></i> Simpan</button>
    <a href="data_pengguna.php" class="btn btn-secondary">Kembali</a>
</form>

<?php include '../includes/footer.php'; ?>

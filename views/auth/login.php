<?php
session_start();
include('../../config/koneksi.php');

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = md5(trim($_POST['password'])); // sesuai DB kamu

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND status_aktif='aktif' LIMIT 1");

    if ($query && mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);

        if ($password === $data['password']) {
            $_SESSION['id_user'] = $data['id_user'];
            $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
            $_SESSION['role'] = $data['role'];

            header("Location: ../../index.php");
            exit;
        } else {
            $error = "⚠️ Password salah!";
        }
    } else {
        $error = "❌ Username tidak ditemukan atau akun nonaktif!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem Manajemen Stok Batik</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f7c67b, #b5651d);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }
        .login-card {
            width: 400px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.25);
            padding: 35px;
            animation: fadeIn 1s ease-in-out;
        }
        .login-card img {
            width: 90px;
            display: block;
            margin: 0 auto 15px auto;
        }
        .btn-batik {
            background-color: #b5651d;
            color: white;
            font-weight: 600;
            transition: 0.3s;
            border-radius: 8px;
        }
        .btn-batik:hover {
            background-color: #8a460f;
            transform: scale(1.03);
        }
        .toggle-password {
            cursor: pointer;
            color: #b5651d;
        }
        .alert {
            animation: shake 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-3px); }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <img src="../../assets/img/logo.png" alt="Logo">
        <h4 class="text-center text-brown fw-bold">Sistem Manajemen Stok Batik</h4>
        <p class="text-center text-muted">PT KONIA PUTRA LESTARI</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                    <button type="button" class="input-group-text toggle-password" onclick="togglePassword()">
                        <i id="eye-icon" class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" name="login" class="btn btn-batik w-100 py-2">
                <i class="fa-solid fa-right-to-bracket me-1"></i> Login
            </button>
        </form>

        <p class="text-center mt-4 text-muted">© <?= date('Y') ?> Sistem Manajemen Stok Batik</p>
    </div>

    <script>
        function togglePassword() {
            const pw = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            pw.type = pw.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('fa-eye-slash');
        }
    </script>
</body>
</html>

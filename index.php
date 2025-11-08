<?php
include 'config/koneksi.php';
include 'config/session_check.php';
include 'config/helper.php';

// Judul halaman
$title = "Dashboard | Sistem Manajemen Stok Batik";

// ===== RINGKASAN DATA =====
$q1 = mysqli_query($conn, "SELECT COUNT(*) AS jml_produk FROM produk");
$r1 = mysqli_fetch_assoc($q1);

$q2 = mysqli_query($conn, "SELECT COALESCE(SUM(stok), 0) AS total_stok FROM produk");
$r2 = mysqli_fetch_assoc($q2);

$q3 = mysqli_query($conn, "SELECT COUNT(*) AS jml_transaksi FROM transaksi");
$r3 = mysqli_fetch_assoc($q3);

$q4 = mysqli_query($conn, "SELECT COUNT(*) AS jml_user FROM users WHERE status_aktif='aktif'");
$r4 = mysqli_fetch_assoc($q4);

// ===== PRODUK DENGAN STOK TERENDAH =====
$q_low = mysqli_query($conn, "SELECT nama_produk, stok FROM produk ORDER BY stok ASC LIMIT 5");
$produk_nama = [];
$produk_stok = [];
while ($row = mysqli_fetch_assoc($q_low)) {
    $produk_nama[] = $row['nama_produk'];
    $produk_stok[] = $row['stok'];
}

// ===== PRODUK TERLARIS BULAN INI =====
$q_best = mysqli_query($conn, "
    SELECT p.nama_produk, SUM(dt.jumlah) AS total_terjual
FROM detail_transaksi dt
JOIN produk p ON p.id_produk = dt.id_produk
JOIN transaksi t ON t.id_transaksi = dt.id_transaksi
WHERE t.jenis_transaksi = 'keluar' 
  AND t.tanggal_transaksi >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
GROUP BY p.id_produk
ORDER BY total_terjual DESC
LIMIT 5

");


$produk_laris = [];
$jumlah_terjual = [];
while ($row = mysqli_fetch_assoc($q_best)) {
    $produk_laris[] = $row['nama_produk'];
    $jumlah_terjual[] = $row['total_terjual'];
}

include 'views/includes/header.php'; // Navbar + Sidebar + CSS
?>

<div class="dashboard-wrapper fade-in">
    <div class="container-fluid py-4">
        <!-- ===== TITLE ===== -->
        <div class="text-center mb-4">
            <h2 class="fw-bold text-dark">Selamat Datang di Sistem Manajemen Stok Batik</h2>
            <p class="text-muted">Pantau data produk, stok, transaksi, dan pengguna aktif secara real-time</p>
        </div>

        <!-- ===== SUMMARY CARDS ===== -->
        <div class="row g-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card text-center p-3 shadow-sm">
                    <h6 class="text-muted">Total Produk</h6>
                    <h2 class="fw-bold text-primary"><?= $r1['jml_produk']; ?></h2>
                    <i class="fa-solid fa-box fa-2x text-primary"></i>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card text-center p-3 shadow-sm">
                    <h6 class="text-muted">Total Stok</h6>
                    <h2 class="fw-bold text-success"><?= $r2['total_stok']; ?></h2>
                    <i class="fa-solid fa-layer-group fa-2x text-success"></i>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card text-center p-3 shadow-sm">
                    <h6 class="text-muted">Transaksi</h6>
                    <h2 class="fw-bold text-warning"><?= $r3['jml_transaksi']; ?></h2>
                    <i class="fa-solid fa-exchange-alt fa-2x text-warning"></i>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card text-center p-3 shadow-sm">
                    <h6 class="text-muted">Pengguna Aktif</h6>
                    <h2 class="fw-bold text-danger"><?= $r4['jml_user']; ?></h2>
                    <i class="fa-solid fa-users fa-2x text-danger"></i>
                </div>
            </div>
        </div>

        <!-- ===== CHARTS ===== -->
        <div class="row mt-5">
            <!-- Grafik Stok Terendah -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="card p-4 shadow-sm">
                    <h5 class="mb-3"><i class="fa-solid fa-boxes-stacked me-2"></i> Stok Produk Terendah</h5>
                    <div class="chart-container">
                        <canvas id="stokChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Grafik Produk Terlaris -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="card p-4 shadow-sm">
                    <h5 class="mb-3"><i class="fa-solid fa-chart-line me-2"></i> Produk Terlaris Bulan Ini</h5>
                    <div class="chart-container">
                        <canvas id="larisChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/includes/footer.php'; ?> <!-- Footer -->

<!-- ===== CHART.JS ===== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- Grafik Stok Terendah ---
    const stokCtx = document.getElementById('stokChart');
    if (stokCtx) {
        new Chart(stokCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($produk_nama ?: ['Tidak ada data']); ?>,
                datasets: [{
                    label: 'Jumlah Stok',
                    data: <?= json_encode($produk_stok ?: [0]); ?>,
                    backgroundColor: ['#b5651d', '#d48c3c', '#f7c67b', '#e6b347', '#a8561d'],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } },
                plugins: { legend: { display: false } }
            }
        });
    }

    // --- Grafik Produk Terlaris ---
    const larisCtx = document.getElementById('larisChart');
    if (larisCtx) {
        new Chart(larisCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($produk_laris ?: ['Belum ada transaksi']); ?>,
                datasets: [{
                    label: 'Jumlah Terjual',
                    data: <?= json_encode($jumlah_terjual ?: [0]); ?>,
                    backgroundColor: ['#f7c67b', '#d48c3c', '#b5651d', '#8a460f', '#6b3209'],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y', // Horizontal bar chart
                scales: { x: { beginAtZero: true } },
                plugins: { legend: { display: false } }
            }
        });
    }
});
</script>

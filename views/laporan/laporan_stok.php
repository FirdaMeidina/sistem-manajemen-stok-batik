<?php
include '../../config/koneksi.php';
include '../../config/session_check.php';
include '../../config/helper.php';
check_access(['admin', 'staf_gudang', 'owner']);
$title = "Laporan Stok | Sistem Manajemen Stok Batik";
include '../includes/header.php';

// Query laporan stok dengan stok awal yang dihitung
$q = mysqli_query($conn, "
    SELECT 
        p.id_produk,
        p.nama_produk,
        p.harga,
        (p.stok - COALESCE(SUM(CASE WHEN t.jenis_transaksi='masuk' THEN d.jumlah END),0) 
                + COALESCE(SUM(CASE WHEN t.jenis_transaksi='keluar' THEN d.jumlah END),0)) AS stok_awal,
        COALESCE(SUM(CASE WHEN t.jenis_transaksi='masuk' THEN d.jumlah END),0) AS total_masuk,
        COALESCE(SUM(CASE WHEN t.jenis_transaksi='keluar' THEN d.jumlah END),0) AS total_keluar,
        p.stok AS stok_saat_ini
    FROM produk p
    LEFT JOIN detail_transaksi d ON p.id_produk = d.id_produk
    LEFT JOIN transaksi t ON d.id_transaksi = t.id_transaksi
    GROUP BY p.id_produk
    ORDER BY p.nama_produk ASC
");
?>

<h4><i class="fa-solid fa-warehouse me-2"></i>Laporan Stok Barang</h4>

<div class="mb-3">
    <a href="export_pdf_stok.php?type=stok" class="btn btn-danger"><i class="fa-solid fa-file-pdf me-1"></i> Export PDF</a>
    <a href="export_excel_stok.php?type=stok" class="btn btn-success"><i class="fa-solid fa-file-excel me-1"></i> Export Excel</a>
</div>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-warning text-center">
        <tr>
            <th>#</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Stok Awal</th>
            <th>Total Masuk</th>
            <th>Total Keluar</th>
            <th>Stok Sekarang</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; while($r=mysqli_fetch_assoc($q)): ?>
        <tr>
            <td class="text-center"><?= $no++; ?></td>
            <td><?= htmlspecialchars($r['nama_produk']); ?></td>
            <td><?= rupiah($r['harga']); ?></td>
            <td class="text-center"><?= $r['stok_awal']; ?></td>
            <td class="text-success text-center"><?= $r['total_masuk']; ?></td>
            <td class="text-danger text-center"><?= $r['total_keluar']; ?></td>
            <td class="text-center fw-bold"><?= $r['stok_saat_ini']; ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>

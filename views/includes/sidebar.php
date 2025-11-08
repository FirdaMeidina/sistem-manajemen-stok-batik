<a href="/sistem-manajemen-stok-batik/index.php"><i class="fa-solid fa-chart-line me-2"></i> Dashboard</a>
<br>

<?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staf_gudang'): ?>
  <a href="/sistem-manajemen-stok-batik/views/produk/data_produk.php"><i class="fa-solid fa-box-open me-2"></i> Produk</a>
  <br>
<?php endif; ?>

<?php if (in_array($_SESSION['role'], ['admin', 'staf_gudang', 'kasir'])): ?>
  <a href="/sistem-manajemen-stok-batik/views/transaksi/data_transaksi.php"><i class="fa-solid fa-exchange-alt me-2"></i> Transaksi</a>
  <br>
<?php endif; ?>

<?php if (in_array($_SESSION['role'], ['admin', 'staf_gudang', 'owner'])): ?>
  <a href="/sistem-manajemen-stok-batik/views/laporan/laporan_stok.php"><i class="fa-solid fa-file-lines me-2"></i> Laporan Stok</a>
  <br>
<?php endif; ?>

<?php if (in_array($_SESSION['role'], ['admin', 'kasir', 'owner'])): ?>
  <a href="/sistem-manajemen-stok-batik/views/laporan/laporan_penjualan.php"><i class="fa-solid fa-file-lines me-2"></i> Laporan Penjualan</a>
  <br>
<?php endif; ?>

<?php if ($_SESSION['role'] === 'admin'): ?>
  <a href="/sistem-manajemen-stok-batik/views/pengguna/data_pengguna.php"><i class="fa-solid fa-user-gear me-2"></i> Pengguna</a>
<?php endif; ?>

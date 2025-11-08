<?php
// views/transaksi/edit_transaksi.php
include '../../config/koneksi.php';
include '../../config/session_check.php';
include '../../config/helper.php';

$title = "Edit Transaksi | Sistem Manajemen Stok Batik";
include '../includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    echo "<div class='alert alert-danger'>ID transaksi tidak valid.</div>";
    include '../includes/footer.php';
    exit;
}

// ambil transaksi
$q = mysqli_query($conn, "SELECT t.*, u.nama_lengkap 
                          FROM transaksi t 
                          LEFT JOIN users u ON t.id_user = u.id_user
                          WHERE t.id_transaksi = '$id' LIMIT 1");
$trans = mysqli_fetch_assoc($q);
if (!$trans) {
    echo "<div class='alert alert-danger'>Transaksi tidak ditemukan.</div>";
    include '../includes/footer.php';
    exit;
}

// semua produk utk dropdown (nama + stok)
$produk_all = mysqli_query($conn, "SELECT id_produk, nama_produk, stok FROM produk ORDER BY nama_produk ASC");

// proses update
$pesan = '';
if (isset($_POST['update'])) {
    // ambil data post
    $keterangan = esc($conn, $_POST['keterangan']);
    $jenis = $trans['jenis_transaksi']; // 'masuk' atau 'keluar'

    // arrays dari form: produk[], jumlah[], harga[]
    $prod_ids = isset($_POST['id_produk']) ? $_POST['id_produk'] : [];
    $jumlahs = isset($_POST['jumlah']) ? $_POST['jumlah'] : [];
    $hargas  = isset($_POST['harga_satuan']) ? $_POST['harga_satuan'] : [];

    // normalisasi: pastikan panjang sama
    $n = max(count($prod_ids), count($jumlahs), count($hargas));

    // mulai transaksi MySQL
    mysqli_begin_transaction($conn);

    try {
        // 1) ambil semua detail lama untuk rollback stok (reverse efek)
        $old = [];
        $res_old = mysqli_query($conn, "SELECT * FROM detail_transaksi WHERE id_transaksi = '$id'");
        while ($row = mysqli_fetch_assoc($res_old)) {
            $old[] = $row;
        }

        // 2) reverse efek stok berdasarkan detail lama
        foreach ($old as $od) {
            $idp = (int)$od['id_produk'];
            $j   = (int)$od['jumlah'];

            if ($jenis === 'masuk') {
                // transaksi lama menambahkan stok => kita kurangi stok untuk "membalikkan"
                mysqli_query($conn, "UPDATE produk SET stok = stok - $j WHERE id_produk = $idp");
            } else { // 'keluar'
                // transaksi lama mengurangi stok => kita tambahkan stok untuk "membalikkan"
                mysqli_query($conn, "UPDATE produk SET stok = stok + $j WHERE id_produk = $idp");
            }
        }

        // 3) hapus detail lama
        mysqli_query($conn, "DELETE FROM detail_transaksi WHERE id_transaksi = '$id'");

        // 4) masukkan detail baru dan apply stok, juga hitung total
        $total = 0;
        for ($i = 0; $i < $n; $i++) {
            $pid = isset($prod_ids[$i]) ? (int)$prod_ids[$i] : 0;
            $qty = isset($jumlahs[$i]) ? (int)$jumlahs[$i] : 0;
            $hrg = isset($hargas[$i]) ? (float)$hargas[$i] : 0;

            // ignore empty rows (produk 0 or qty <=0)
            if ($pid <= 0 || $qty <= 0) continue;

            // cek stok saat apply untuk transaksi 'keluar'
            if ($jenis === 'keluar') {
                // ambil stok sekarang
                $r = mysqli_query($conn, "SELECT stok FROM produk WHERE id_produk = $pid LIMIT 1");
                $cur = mysqli_fetch_assoc($r);
                $stok_now = $cur ? (int)$cur['stok'] : 0;

                if ($stok_now < $qty) {
                    // stok tidak mencukupi -> rollback dan tampilkan error
                    mysqli_rollback($conn);
                    $pesan = "Stok produk ID {$pid} tidak mencukupi (tersisa: {$stok_now}, diminta: {$qty}). Perubahan dibatalkan.";
                    throw new Exception($pesan);
                }
                // kurangi stok
                mysqli_query($conn, "UPDATE produk SET stok = stok - $qty WHERE id_produk = $pid");
            } else { // masuk -> tambah stok
                mysqli_query($conn, "UPDATE produk SET stok = stok + $qty WHERE id_produk = $pid");
            }

            $subtotal = $qty * $hrg;
            $total += $subtotal;

            // insert detail
            $pid_esc = (int)$pid;
            $qty_esc = (int)$qty;
            $hrg_esc = (float)$hrg;
            mysqli_query($conn, "INSERT INTO detail_transaksi (id_transaksi, id_produk, jumlah, harga_satuan) 
                                VALUES ('$id', '$pid_esc', '$qty_esc', '$hrg_esc')");
        }

        // 5) update transaksi (keterangan + total_harga)
        mysqli_query($conn, "UPDATE transaksi SET keterangan = '{$keterangan}', total_harga = '$total' WHERE id_transaksi = '$id'");

        // commit
        mysqli_commit($conn);
        echo "<script>alert('Transaksi berhasil diperbarui!');window.location='data_transaksi.php';</script>";
        exit;

    } catch (Exception $e) {
        // jika throw, $pesan sudah diisi
        if (empty($pesan)) $pesan = "Gagal memperbarui transaksi: " . $e->getMessage();
        // pastikan rollback
        if (mysqli_connect_errno() === 0) mysqli_rollback($conn);
    }
}

// ambil detail transaksi saat ini (setelah perubahan jika reload)
$detail = mysqli_query($conn, "
    SELECT dt.*, p.nama_produk 
    FROM detail_transaksi dt 
    LEFT JOIN produk p ON dt.id_produk = p.id_produk
    WHERE dt.id_transaksi = '$id'
    ORDER BY dt.id_detail ASC
");

// ambil list produk utk dropdown (lagi) - reset pointer jika perlu
$produk_all = mysqli_query($conn, "SELECT id_produk, nama_produk, stok FROM produk ORDER BY nama_produk ASC");
?>

<div class="mb-3">
  <h4><i class="fa-solid fa-pen me-2"></i>Edit Transaksi</h4>
  <p><strong>Kode:</strong> <?= htmlspecialchars($trans['kode_transaksi']); ?> 
     &nbsp; | &nbsp; <strong>Jenis:</strong> <?= htmlspecialchars($trans['jenis_transaksi']); ?> 
     &nbsp; | &nbsp; <strong>Tanggal:</strong> <?= htmlspecialchars($trans['tanggal_transaksi']); ?></p>
</div>

<?php if (!empty($pesan)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($pesan); ?></div>
<?php endif; ?>

<form method="POST" id="form-transaksi">
  <div class="mb-3">
    <label class="form-label">Keterangan</label>
    <textarea name="keterangan" class="form-control" rows="2"><?= htmlspecialchars($trans['keterangan']); ?></textarea>
  </div>

  <h6>Detail Produk</h6>
  <div class="table-responsive">
    <table class="table table-sm table-bordered" id="tbl-detail">
      <thead class="table-light text-center">
        <tr>
          <th style="width:40%;">Produk</th>
          <th style="width:15%;">Stok Sekarang</th>
          <th style="width:15%;">Jumlah</th>
          <th style="width:15%;">Harga Satuan</th>
          <th style="width:10%;">Subtotal</th>
          <th style="width:5%;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($detail)): ?>
          <tr>
            <td>
              <select name="id_produk[]" class="form-select form-select-sm sel-produk" required>
                <option value="">-- Pilih --</option>
                <?php
                // reset produk_all pointer
                mysqli_data_seek($produk_all, 0);
                while ($p = mysqli_fetch_assoc($produk_all)): ?>
                  <option value="<?= $p['id_produk']; ?>" <?= $p['id_produk'] == $row['id_produk'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['nama_produk']); ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </td>
            <td class="text-center stok-now"><?= (int)$row['stok'] ?? '-' ?></td>
            <td><input type="number" name="jumlah[]" class="form-control form-control-sm jumlah" min="1" value="<?= (int)$row['jumlah']; ?>" required></td>
            <td><input type="number" name="harga_satuan[]" class="form-control form-control-sm harga" min="0" step="0.01" value="<?= (float)$row['harga_satuan']; ?>" required></td>
            <td class="text-end subtotal"><?= number_format($row['jumlah'] * $row['harga_satuan'], 0, ',', '.'); ?></td>
            <td class="text-center">
              <button type="button" class="btn btn-sm btn-danger btn-remove">×</button>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="6">
            <button type="button" id="add-row" class="btn btn-sm btn-success"><i class="fa-solid fa-plus"></i> Tambah Item</button>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>

  <div class="mt-3">
    <button type="submit" name="update" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Simpan Perubahan</button>
    <a href="data_transaksi.php" class="btn btn-secondary">Batal</a>
  </div>
</form>

<!-- skrip kecil untuk interaksi client-side -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // fungsi bantu format rupiah sederhana (client-side display)
  function fmt(n){ return new Intl.NumberFormat('id-ID').format(n); }

  // tambahkan baris baru
  document.getElementById('add-row').addEventListener('click', function() {
    const tbody = document.querySelector('#tbl-detail tbody');
    const tr = document.createElement('tr');
    // build select options from a data-list snapshot
    let options = '<option value="">-- Pilih --</option>';
    <?php
    // ambil produk sekali lagi utk options
    $opt_rows = [];
    mysqli_data_seek($produk_all, 0);
    while ($pp = mysqli_fetch_assoc($produk_all)) {
        $idp = (int)$pp['id_produk'];
        $nm = addslashes($pp['nama_produk']);
        $stk = (int)$pp['stok'];
        // store as JS push lines
        echo "options += '<option value=\"{$idp}\" data-stok=\"{$stk}\">{$nm}</option>';\n";
    }
    ?>
    tr.innerHTML = `
      <td>
        <select name="id_produk[]" class="form-select form-select-sm sel-produk" required>
          ${options}
        </select>
      </td>
      <td class="text-center stok-now">-</td>
      <td><input type="number" name="jumlah[]" class="form-control form-control-sm jumlah" min="1" value="1" required></td>
      <td><input type="number" name="harga_satuan[]" class="form-control form-control-sm harga" min="0" step="0.01" value="0" required></td>
      <td class="text-end subtotal">0</td>
      <td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-remove">×</button></td>
    `;
    tbody.appendChild(tr);
    attachRowHandlers(tr);
  });

  // attach handlers to current rows
  function attachRowHandlers(tr) {
    // when produk change -> update stok-now
    const sel = tr.querySelector('.sel-produk');
    const stokTd = tr.querySelector('.stok-now');
    const qty = tr.querySelector('.jumlah');
    const harga = tr.querySelector('.harga');
    const subtotalTd = tr.querySelector('.subtotal');

    function recalcSubtotal(){
      const q = Number(qty.value) || 0;
      const h = Number(harga.value) || 0;
      subtotalTd.textContent = fmt(q * h);
    }

    if (sel) {
      sel.addEventListener('change', function(){
        const opt = sel.selectedOptions[0];
        const s = opt ? opt.dataset.stok : null;
        stokTd.textContent = s !== undefined ? s : '-';
      });
      // trigger once to show stok for prefilled rows
      sel.dispatchEvent(new Event('change'));
    }

    if (qty) qty.addEventListener('input', recalcSubtotal);
    if (harga) harga.addEventListener('input', recalcSubtotal);

    // remove button
    const btnRemove = tr.querySelector('.btn-remove');
    if (btnRemove) {
      btnRemove.addEventListener('click', function(){
        tr.remove();
      });
    }
  }

  // attach for existing rows
  document.querySelectorAll('#tbl-detail tbody tr').forEach(function(tr){
    attachRowHandlers(tr);
  });
});
</script>

<?php include '../includes/footer.php'; ?>

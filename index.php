<?php
// index.php
require 'functions.php';

$products = [
    ['label'=>'Obat Pertanian', 'category'=>'Obat'],
    ['label'=>'Pupuk NPK', 'category'=>'Pupuk NPK'],
    ['label'=>'Pupuk Urea', 'category'=>'Pupuk Urea'],
];

$pdo = null;
try {
    $pdo = getPDO();
    $stmt = $pdo->query("SELECT COUNT(*) as cnt, SUM(total) as sum FROM sales");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalSalesCount = $row['cnt'] ?? 0;
    $totalRevenue = $row['sum'] ?? 0;
} catch (Exception $e) {
    $totalSalesCount = 0;
    $totalRevenue = 0;
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Form Penjualan - POS Sederhana</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
  <h1>Form Penjualan</h1>
  <p class="muted">Inputkan transaksi penjualan produk: Obat Pertanian, Pupuk NPK, Pupuk Urea.</p>

  <form action="add_sale.php" method="post" class="form">
    <label>Tanggal</label>
    <input type="date" name="sale_date" value="<?= date('Y-m-d') ?>" required>

    <label>Produk</label>
    <select name="product" id="product" required>
      <option value="">-- Pilih Produk --</option>
      <?php foreach($products as $p): ?>
        <option value="<?= h($p['label']) ?>" data-category="<?= h($p['category']) ?>"><?= h($p['label']) ?></option>
      <?php endforeach; ?>
    </select>

    <label>Kategori</label>
    <input type="text" name="category" id="category" readonly>

    <label>Jumlah (kg/pcs)</label>
    <input type="number" name="quantity" step="0.01" min="0.01" required>

    <label>Harga satuan (Rp)</label>
    <input type="number" name="unit_price" step="1" min="0" required>

    <label>Catatan (opsional)</label>
    <input type="text" name="notes">

    <div class="actions">
      <button type="submit">Simpan Transaksi</button>
      <a class="btn" href="report.php">Lihat Laporan</a>
    </div>
  </form>

  <hr>
  <h3>Ringkasan</h3>
  <p>Total Transaksi: <strong><?= (int)$totalSalesCount ?></strong></p>
  <p>Total Pemasukan: <strong>Rp <?= number_format($totalRevenue,0,',','.') ?></strong></p>
</div>

<script>
document.getElementById('product').addEventListener('change', function(){
  var opt = this.options[this.selectedIndex];
  document.getElementById('category').value = opt.getAttribute('data-category') || '';
});
</script>
</body>
</html>

<?php
require 'functions.php';
$pdo = getPDO();

$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$product = $_GET['product'] ?? '';

// Build query
$where = [];
$params = [];

if ($from) { $where[] = "sale_date >= ?"; $params[] = $from; }
if ($to)   { $where[] = "sale_date <= ?"; $params[] = $to; }
if ($product) { $where[] = "product = ?"; $params[] = $product; }

$sql = "SELECT * FROM sales";
if ($where) $sql .= " WHERE " . implode(' AND ', $where);
$sql .= " ORDER BY sale_date DESC, id DESC";

$stmt = $pdo->prepare($sql);
stmt_execute($stmt = $pdo->prepare($sql), $params); // compatibility helper below

function stmt_execute($stmt, $params){
    if(!$stmt) return null;
    return $stmt->execute($params) ? $stmt : null;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Summary
$summarySql = "SELECT COUNT(*) as cnt, COALESCE(SUM(total),0) as total FROM sales" . ($where ? " WHERE " . implode(' AND ', $where) : '');
$summaryStmt = $pdo->prepare($summarySql);
$summaryStmt->execute($params);
$summary = $summaryStmt->fetch(PDO::FETCH_ASSOC);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Laporan Penjualan</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
  <h1>Laporan Penjualan</h1>

  <form method="get" class="filter">
    <label>From</label><input type="date" name="from" value="<?= h($from) ?>">
    <label>To</label><input type="date" name="to" value="<?= h($to) ?>">
    <label>Produk</label>
    <select name="product">
      <option value="">-- Semua --</option>
      <option value="Obat Pertanian" <?= $product=='Obat Pertanian' ? 'selected' : '' ?>>Obat Pertanian</option>
      <option value="Pupuk NPK" <?= $product=='Pupuk NPK' ? 'selected' : '' ?>>Pupuk NPK</option>
      <option value="Pupuk Urea" <?= $product=='Pupuk Urea' ? 'selected' : '' ?>>Pupuk Urea</option>
    </select>
    <button type="submit">Filter</button>
    <a class="btn" href="export_csv.php?<?= http_build_query($_GET) ?>">Ekspor CSV</a>
    <a class="btn" href="index.php">Input Baru</a>
  </form>

  <h3>Ringkasan</h3>
  <p>Total Transaksi: <strong><?= (int)$summary['cnt'] ?></strong></p>
  <p>Total Pemasukan: <strong>Rp <?= number_format($summary['total'],0,',','.') ?></strong></p>

  <table class="table">
    <thead>
      <tr><th>#</th><th>Tanggal</th><th>Produk</th><th>Kategori</th><th>Jumlah</th><th>Harga</th><th>Total</th><th>Catatan</th></tr>
    </thead>
    <tbody>
    <?php foreach($rows as $i => $r): ?>
      <tr>
        <td><?= $i+1 ?></td>
        <td><?= h($r['sale_date']) ?></td>
        <td><?= h($r['product']) ?></td>
        <td><?= h($r['category']) ?></td>
        <td><?= number_format($r['quantity'],2,',','.') ?></td>
        <td>Rp <?= number_format($r['unit_price'],0,',','.') ?></td>
        <td>Rp <?= number_format($r['total'],0,',','.') ?></td>
        <td><?= h($r['notes']) ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if(empty($rows)): ?>
      <tr><td colspan="8">Tidak ada data.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>

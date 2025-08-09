<?php
// export_csv.php
require 'functions.php';
$pdo = getPDO();

$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$product = $_GET['product'] ?? '';

$where = [];
$params = [];
if ($from) { $where[] = "sale_date >= ?"; $params[] = $from; }
if ($to)   { $where[] = "sale_date <= ?"; $params[] = $to; }
if ($product) { $where[] = "product = ?"; $params[] = $product; }

$sql = "SELECT * FROM sales" . ($where ? " WHERE " . implode(' AND ', $where) : "") . " ORDER BY sale_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=laporan_penjualan_'.date('Ymd_His').'.csv');

$out = fopen('php://output', 'w');
fputcsv($out, ['id','sale_date','product','category','quantity','unit_price','total','notes']);
foreach($rows as $r){
    fputcsv($out, [$r['id'],$r['sale_date'],$r['product'],$r['category'],$r['quantity'],$r['unit_price'],$r['total'],$r['notes']]);
}
fclose($out);
exit;

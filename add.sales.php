<?php
// add_sale.php
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php'); exit;
}

$sale_date = $_POST['sale_date'] ?? '';
$product = trim($_POST['product'] ?? '');
$category = trim($_POST['category'] ?? '');
$quantity = (float)($_POST['quantity'] ?? 0);
$unit_price = (float)($_POST['unit_price'] ?? 0);
$notes = trim($_POST['notes'] ?? '');

if (!$sale_date || !$product || $quantity <= 0) {
    die("Data tidak lengkap. Kembali dan periksa isian.");
}

$total = $quantity * $unit_price;

$pdo = getPDO();
$stmt = $pdo->prepare("INSERT INTO sales (sale_date, product, category, quantity, unit_price, total, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$sale_date, $product, $category, $quantity, $unit_price, $total, $notes]);

header('Location: report.php?msg=ok');
exit;

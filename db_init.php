<?php
// db_init.php - Jalankan sekali untuk membuat DB SQLite (via browser atau CLI: php db_init.php)

$dbDir = __DIR__ . '/data';
if (!is_dir($dbDir)) {
    mkdir($dbDir, 0755, true);
}
$dbFile = $dbDir . '/sales.db';

$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Buat tabel sales
$pdo->exec("
CREATE TABLE IF NOT EXISTS sales (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    sale_date TEXT NOT NULL,
    product TEXT NOT NULL,
    category TEXT NOT NULL,
    quantity REAL NOT NULL,
    unit_price REAL NOT NULL,
    total REAL NOT NULL,
    notes TEXT
);
");

echo "Database dan tabel berhasil dibuat di: " . $dbFile . PHP_EOL;
echo "Hapus atau amankan file db_init.php setelah inisialisasi.\n";

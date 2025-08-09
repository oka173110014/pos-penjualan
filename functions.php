<?php
// functions.php
function getPDO(){
    $dbFile = __DIR__ . '/data/sales.db';
    if (!file_exists($dbFile)) {
        // jika belum ada, beri pesan
        die("Database belum dibuat. Jalankan db_init.php terlebih dahulu.");
    }
    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

function h($v){ return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }

<?php

require __DIR__ . '/../config/config.php';

$dsn = "mysql:dbname=glimpsgone;host=localhost;port=3306";
$pdo = new PDO($dsn, 'root', 'Mamandu13007.', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$files = scandir(__DIR__ . "/sql");

$files = array_diff($files, ['.', '..']);

foreach ($files as $sqlFile) {
    echo "Processing $sqlFile...";
    $sql = file_get_contents(__DIR__ . "/sql/" . $sqlFile);

    try {
        $pdo->exec($sql);
        echo " done!\n";
    } catch (PDOException $e) {
        echo " failed: " . $e->getMessage() . "\n";
    }
}

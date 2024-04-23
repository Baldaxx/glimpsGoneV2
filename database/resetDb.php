<?php

require __DIR__ . '/config/config.php';

$dsn = "mysql:dbname=glimpsgone;host=localhost;port=3306";
$pdo = new PDO($dsn, 'root', 'Mamandu13007.', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$files = scandir(__DIR__ . "/sql");

// Remove ./ and ../
array_shift($files);
array_shift($files);

foreach ($files as $sqlFile) {
    echo "Processing $sqlFile...";
    $sql = file_get_contents(__DIR__ . "/sql/" . $sqlFile);

    $pdo->exec($sql);

    echo " done!\n";
}

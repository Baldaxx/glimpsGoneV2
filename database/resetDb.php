<?php

require __DIR__ . '/config/config.php';

$PDO = new PDO("mysql:dbname={DB['name']};host={DB['host']}", DB['user'], DB['password'], [
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

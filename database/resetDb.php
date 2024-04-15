<?php

require __DIR__ . '/../config/config.php';

$dsn = "mysql:dbname=" . DB["name"] . ";host=127.0.0.1";
$pdo = new PDO($dsn, DB["user"], DB["password"]);

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

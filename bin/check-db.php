<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Database\Connection;

$connection = new Connection();
$pdo = $connection->connect();

$result = $pdo->query('SELECT 1')->fetchColumn();
if ((int) $result !== 1) {
    throw new RuntimeException('Unexpected database response');
}

echo 'Database connect successful' . PHP_EOL;
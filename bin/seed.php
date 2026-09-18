<?php

declare(strict_types=1);

use App\Database\Connection;
use App\Database\Seeders\ArticleSeeder;
use App\Database\Seeders\CategorySeeder;

require __DIR__ . '/../vendor/autoload.php';

$pdo = null;

try {
    $pdo = (new Connection())->connect();
    $pdo->beginTransaction();

    (new CategorySeeder())->run($pdo);
    (new ArticleSeeder())->run($pdo);

    $pdo->commit();
    echo 'Database seeded successfully' . PHP_EOL;
} catch (Throwable $exception) {
    if ($pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    fwrite(STDERR, 'Seeding failed: ' . $exception->getMessage() . PHP_EOL);
    exit(1);
}

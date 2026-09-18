<?php

declare(strict_types=1);

use App\Database\Connection;
use App\Database\Migrator;

require __DIR__ . '/../vendor/autoload.php';

try {
    $command = $argv[1] ?? 'up';
    $migrator = new Migrator(
        (new Connection())->connect(),
        dirname(__DIR__) . '/database/migrations'
    );

    $migrations = match ($command) {
        'up' => $migrator->migrate(),
        'down' => $migrator->rollback(),
        default => throw new InvalidArgumentException('Available commands: up, down'),
    };

    if ($migrations === []) {
        echo $command === 'up'
            ? 'Nothing to migrate' . PHP_EOL
            : 'Nothing to rollback' . PHP_EOL;
        exit(0);
    }

    foreach ($migrations as $migration) {
        echo "{$command}: {$migration}" . PHP_EOL;
    }
} catch (Throwable $exception) {
    fwrite(STDERR, 'Migration failed: ' . $exception->getMessage() . PHP_EOL);
    exit(1);
}

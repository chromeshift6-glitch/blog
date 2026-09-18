<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use RuntimeException;

final readonly class Migrator
{
    public function __construct(
        private PDO    $pdo,
        private string $migrationPath
    ) {
    }

    /** @return list<string> */
    public function migrate(): array
    {
        $this->ensureMigrationsTableExists();

        $appliedMigrations = $this->appliedMigrations();
        $batch = $this->nextBatch();
        $executed = [];

        foreach ($this->migrationFiles() as $file) {
            $name = basename($file);

            if (isset($appliedMigrations[$name])) {
                continue;
            }

            $migration = $this->loadMigration($file);
            $migration->up($this->pdo);

            $statement = $this->pdo->prepare(
                'INSERT INTO migrations (migration, batch) VALUES (:migration, :batch)'
            );
            $statement->execute([
                'migration' => $name,
                'batch' => $batch,
            ]);

            $executed[] = $name;
        }

        return $executed;
    }

    /** @return list<string> */
    public function rollback(): array
    {
        $this->ensureMigrationsTableExists();

        $batch = $this->latestBatch();
        if ($batch === null) {
            return [];
        }

        $statement = $this->pdo->prepare(
            'SELECT migration FROM migrations WHERE batch = :batch ORDER BY id DESC'
        );
        $statement->execute(['batch' => $batch]);

        $rolledBack = [];
        foreach ($statement->fetchAll(PDO::FETCH_COLUMN) as $name) {
            $file = $this->migrationPath . DIRECTORY_SEPARATOR . $name;
            if (!is_file($file)) {
                throw new RuntimeException("Migration file {$name} was not found");
            }

            $migration = $this->loadMigration($file);
            $migration->down($this->pdo);

            $delete = $this->pdo->prepare('DELETE FROM migrations WHERE migration = :migration');
            $delete->execute(['migration' => $name]);

            $rolledBack[] = $name;
        }

        return $rolledBack;
    }

    private function ensureMigrationsTableExists(): void
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS migrations (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL UNIQUE,
                batch INT UNSIGNED NOT NULL,
                executed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            )'
        );
    }

    /** @return array<string, true> */
    private function appliedMigrations(): array
    {
        $names = $this->pdo
            ->query('SELECT migration FROM migrations')
            ->fetchAll(PDO::FETCH_COLUMN);

        return array_fill_keys($names, true);
    }

    private function nextBatch(): int
    {
        return (int) $this->pdo
            ->query('SELECT COALESCE(MAX(batch), 0) + 1 FROM migrations')
            ->fetchColumn();
    }

    private function latestBatch(): ?int
    {
        $batch = $this->pdo
            ->query('SELECT MAX(batch) FROM migrations')
            ->fetchColumn();

        return $batch === null ? null : (int) $batch;
    }

    /** @return list<string> */
    private function migrationFiles(): array
    {
        $files = glob($this->migrationPath . DIRECTORY_SEPARATOR . '*.php');
        if ($files === false) {
            throw new RuntimeException('Unable to read migration directory');
        }

        sort($files, SORT_STRING);

        return $files;
    }

    private function loadMigration(string $file): MigrationInterface
    {
        $migration = require $file;

        if (!$migration instanceof MigrationInterface) {
            throw new RuntimeException("Migration file {$file} must return MigrationInterface");
        }

        return $migration;
    }
}

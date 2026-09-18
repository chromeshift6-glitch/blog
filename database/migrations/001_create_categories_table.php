<?php

declare(strict_types=1);

use App\Database\MigrationInterface;

return new class implements MigrationInterface
{
    public function up(PDO $pdo): void
    {
        $pdo->exec('
        CREATE TABLE categories (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL UNIQUE,
            description TEXT NOT NULL
        )
        ');
    }

    public function down(PDO $pdo): void
    {
        $pdo->exec('DROP TABLE categories');
    }
};

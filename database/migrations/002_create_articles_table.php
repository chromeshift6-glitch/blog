<?php

declare(strict_types=1);

use App\Database\MigrationInterface;

return new class implements MigrationInterface
{
    public function up(PDO $pdo): void
    {
        $pdo->exec('
        CREATE TABLE articles (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            image VARCHAR(255) NOT NULL,
            name VARCHAR(255) UNIQUE NOT NULL,
            description VARCHAR(500) NOT NULL,
            text TEXT NOT NULL,
            view_count INT UNSIGNED NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,

            INDEX idx_view_count (view_count),
            INDEX idx_created_at (created_at)
        )
        ');
    }

    public function down(PDO $pdo): void
    {
        $pdo->exec('DROP TABLE articles');
    }
};

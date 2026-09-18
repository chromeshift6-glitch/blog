<?php

declare(strict_types=1);

use App\Database\MigrationInterface;

return new class implements MigrationInterface
{
    public function up(PDO $pdo): void
    {
        $pdo->exec('
        CREATE TABLE article_categories (
            article_id INT UNSIGNED NOT NULL,
            category_id INT UNSIGNED NOT NULL,

            PRIMARY KEY (article_id, category_id),

            FOREIGN KEY (article_id)
                REFERENCES articles(id)
                ON DELETE CASCADE,

            FOREIGN KEY (category_id)
                REFERENCES categories(id)
                ON DELETE CASCADE,

            INDEX idx_category_id (category_id)
        )
        ');
    }

    public function down(PDO $pdo): void
    {
        $pdo->exec('DROP TABLE article_categories');
    }
};

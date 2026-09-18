<?php

declare(strict_types=1);

namespace App\Database\Seeders;

use App\Database\SeederInterface;
use PDO;

final class CategorySeeder implements SeederInterface
{
    private const CATEGORIES = [
        [
            'name' => 'PHP',
            'description' => 'Материалы о PHP, работе с языком и серверной разработке.',
        ],
        [
            'name' => 'MySQL',
            'description' => 'Статьи о проектировании баз данных и написании SQL-запросов.',
        ],
        [
            'name' => 'JavaScript',
            'description' => 'Клиентская разработка на JavaScript и работа с интерфейсами.',
        ],
        [
            'name' => 'DevOps',
            'description' => 'Настройка окружения, Docker и развёртывание приложений.',
        ],
    ];

    public function run(PDO $pdo): void
    {
        $statement = $pdo->prepare(
            'INSERT INTO categories (name, description)
             VALUES (:name, :description)
             ON DUPLICATE KEY UPDATE description = :updated_description'
        );

        foreach (self::CATEGORIES as $category) {
            $statement->execute([
                'name' => $category['name'],
                'description' => $category['description'],
                'updated_description' => $category['description'],
            ]);
        }
    }
}

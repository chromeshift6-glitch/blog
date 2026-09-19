<?php

declare(strict_types=1);

namespace App\Repository;

use InvalidArgumentException;
use PDO;

final class CategoryRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * Returns only categories containing articles. Each category contains its
     * latest articles ordered by publication date.
     *
     * @return list<array{
     *     id: int,
     *     name: string,
     *     description: string,
     *     articles: list<array<string, mixed>>
     * }>
     */
    public function findAllWithLatestArticles(int $articlesPerCategory = 3): array
    {
        if ($articlesPerCategory < 1) {
            throw new InvalidArgumentException('Articles per category must be greater than zero');
        }

        $statement = $this->pdo->prepare(
            'SELECT
                c.id AS category_id,
                c.name AS category_name,
                c.description AS category_description,
                ranked.id AS article_id,
                ranked.image AS article_image,
                ranked.name AS article_name,
                ranked.description AS article_description,
                ranked.view_count AS article_view_count,
                ranked.created_at AS article_created_at
             FROM categories c
             INNER JOIN (
                 SELECT
                     a.id,
                     a.image,
                     a.name,
                     a.description,
                     a.view_count,
                     a.created_at,
                     ac.category_id,
                     ROW_NUMBER() OVER (
                         PARTITION BY ac.category_id
                         ORDER BY a.created_at DESC, a.id DESC
                     ) AS article_position
                 FROM articles a
                 INNER JOIN article_categories ac ON ac.article_id = a.id
             ) ranked ON ranked.category_id = c.id
             WHERE ranked.article_position <= :articles_per_category
             ORDER BY c.name ASC, ranked.created_at DESC, ranked.id DESC'
        );
        $statement->bindValue(':articles_per_category', $articlesPerCategory, PDO::PARAM_INT);
        $statement->execute();

        $categories = [];
        foreach ($statement->fetchAll() as $row) {
            $categoryId = (int) $row['category_id'];

            if (!isset($categories[$categoryId])) {
                $categories[$categoryId] = [
                    'id' => $categoryId,
                    'name' => $row['category_name'],
                    'description' => $row['category_description'],
                    'articles' => [],
                ];
            }

            $categories[$categoryId]['articles'][] = [
                'id' => (int) $row['article_id'],
                'image' => $row['article_image'],
                'name' => $row['article_name'],
                'description' => $row['article_description'],
                'view_count' => (int) $row['article_view_count'],
                'created_at' => $row['article_created_at'],
            ];
        }

        return array_values($categories);
    }

    /** @return array{id: int, name: string, description: string}|null */
    public function findById(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, name, description FROM categories WHERE id = :id'
        );
        $statement->execute(['id' => $id]);

        $category = $statement->fetch();
        if ($category === false) {
            return null;
        }

        $category['id'] = (int) $category['id'];

        return $category;
    }
}

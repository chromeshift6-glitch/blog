<?php

declare(strict_types=1);

namespace App\Repository;

use InvalidArgumentException;
use PDO;

final class ArticleRepository
{
    public const SORT_BY_DATE = 'date';
    public const SORT_BY_VIEWS = 'views';

    private const SORT_EXPRESSIONS = [
        self::SORT_BY_DATE => 'a.created_at DESC, a.id DESC',
        self::SORT_BY_VIEWS => 'a.view_count DESC, a.created_at DESC, a.id DESC',
    ];

    public function __construct(private readonly PDO $pdo)
    {
    }

    /** @return list<array<string, mixed>> */
    public function findByCategory(
        int $categoryId,
        string $sort = self::SORT_BY_DATE,
        int $page = 1,
        int $perPage = 6
    ): array {
        if (!isset(self::SORT_EXPRESSIONS[$sort])) {
            throw new InvalidArgumentException("Unknown article sorting: {$sort}");
        }
        if ($page < 1) {
            throw new InvalidArgumentException('Page must be greater than zero');
        }
        if ($perPage < 1) {
            throw new InvalidArgumentException('Articles per page must be greater than zero');
        }

        $offset = ($page - 1) * $perPage;
        $orderBy = self::SORT_EXPRESSIONS[$sort];
        $statement = $this->pdo->prepare(
            "SELECT
                a.id,
                a.image,
                a.name,
                a.description,
                a.view_count,
                a.created_at
             FROM articles a
             INNER JOIN article_categories ac ON ac.article_id = a.id
             WHERE ac.category_id = :category_id
             ORDER BY {$orderBy}
             LIMIT :limit OFFSET :offset"
        );
        $statement->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return array_map(
            $this->normalizeArticle(...),
            $statement->fetchAll()
        );
    }

    public function countByCategory(int $categoryId): int
    {
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*) FROM article_categories WHERE category_id = :category_id'
        );
        $statement->execute(['category_id' => $categoryId]);

        return (int) $statement->fetchColumn();
    }

    /** @return array<string, mixed>|null */
    public function findByIdWithCategories(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, image, name, description, text, view_count, created_at, updated_at
             FROM articles
             WHERE id = :id'
        );
        $statement->execute(['id' => $id]);

        $article = $statement->fetch();
        if ($article === false) {
            return null;
        }

        $categoryStatement = $this->pdo->prepare(
            'SELECT c.id, c.name, c.description
             FROM categories c
             INNER JOIN article_categories ac ON ac.category_id = c.id
             WHERE ac.article_id = :article_id
             ORDER BY c.name ASC'
        );
        $categoryStatement->execute(['article_id' => $id]);

        $categories = array_map(
            static function (array $category): array {
                $category['id'] = (int) $category['id'];

                return $category;
            },
            $categoryStatement->fetchAll()
        );

        $article = $this->normalizeArticle($article);
        $article['categories'] = $categories;

        return $article;
    }

    public function incrementViewCount(int $id): bool
    {
        $statement = $this->pdo->prepare(
            'UPDATE articles SET view_count = view_count + 1 WHERE id = :id'
        );
        $statement->execute(['id' => $id]);

        return $statement->rowCount() === 1;
    }

    /** @return list<array<string, mixed>> */
    public function findSimilar(int $articleId, int $limit = 3): array
    {
        if ($limit < 1) {
            throw new InvalidArgumentException('Similar articles limit must be greater than zero');
        }

        $statement = $this->pdo->prepare(
            'SELECT
                a.id,
                a.image,
                a.name,
                a.description,
                a.view_count,
                a.created_at,
                COUNT(DISTINCT shared.category_id) AS shared_categories
             FROM article_categories current_article
             INNER JOIN article_categories shared
                 ON shared.category_id = current_article.category_id
                 AND shared.article_id <> current_article.article_id
             INNER JOIN articles a ON a.id = shared.article_id
             WHERE current_article.article_id = :article_id
             GROUP BY
                a.id,
                a.image,
                a.name,
                a.description,
                a.view_count,
                a.created_at
             ORDER BY shared_categories DESC, a.created_at DESC, a.id DESC
             LIMIT :limit'
        );
        $statement->bindValue(':article_id', $articleId, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return array_map(
            static function (array $article): array {
                $article['shared_categories'] = (int) $article['shared_categories'];

                return self::normalizeArticle($article);
            },
            $statement->fetchAll()
        );
    }

    /** @param array<string, mixed> $article @return array<string, mixed> */
    private static function normalizeArticle(array $article): array
    {
        $article['id'] = (int) $article['id'];
        $article['view_count'] = (int) $article['view_count'];

        return $article;
    }
}

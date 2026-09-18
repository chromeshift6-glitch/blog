<?php

declare(strict_types=1);

namespace App\Database\Seeders;

use App\Database\SeederInterface;
use PDO;
use RuntimeException;

final class ArticleSeeder implements SeederInterface
{
    private const IMAGE = '/images/article-placeholder.svg';

    private const ARTICLES = [
        [
            'name' => 'Основы современного PHP',
            'description' => 'Краткое знакомство с типами, функциями и классами в PHP 8.',
            'text' => 'PHP остаётся практичным языком для серверной веб-разработки. Строгая типизация, классы и стандартные интерфейсы помогают писать понятный и поддерживаемый код.',
            'view_count' => 125,
            'created_at' => '2026-08-01 10:00:00',
            'categories' => ['PHP'],
        ],
        [
            'name' => 'Подключение к MySQL через PDO',
            'description' => 'Создаём безопасное подключение и выполняем подготовленные запросы.',
            'text' => 'PDO предоставляет единый интерфейс для работы с базами данных. Режим исключений и настоящие prepared statements позволяют вовремя обнаруживать ошибки и безопасно передавать значения в SQL.',
            'view_count' => 310,
            'created_at' => '2026-08-05 11:30:00',
            'categories' => ['PHP', 'MySQL'],
        ],
        [
            'name' => 'Шаблоны Smarty без лишней логики',
            'description' => 'Разделяем подготовку данных в PHP и их отображение в шаблоне.',
            'text' => 'Шаблонизатор отвечает за представление данных. Запросы к базе и бизнес-правила остаются в PHP-классах, а шаблон получает уже подготовленные категории и статьи.',
            'view_count' => 184,
            'created_at' => '2026-08-10 09:15:00',
            'categories' => ['PHP'],
        ],
        [
            'name' => 'Индексы в MySQL',
            'description' => 'Разбираемся, когда индекс ускоряет выборку и сортировку.',
            'text' => 'Индекс сокращает объём данных, который MySQL должен просмотреть. Порядок столбцов в составном индексе выбирают с учётом реальных условий WHERE, JOIN и ORDER BY.',
            'view_count' => 420,
            'created_at' => '2026-08-14 14:00:00',
            'categories' => ['MySQL'],
        ],
        [
            'name' => 'Транзакции в приложении',
            'description' => 'Объединяем несколько изменений базы данных в одну операцию.',
            'text' => 'Транзакция гарантирует принцип всё или ничего. Если одна операция завершается ошибкой, rollback возвращает базу к состоянию до начала группы изменений.',
            'view_count' => 275,
            'created_at' => '2026-08-18 16:20:00',
            'categories' => ['PHP', 'MySQL'],
        ],
        [
            'name' => 'JavaScript для серверного разработчика',
            'description' => 'Минимальный набор знаний для интерактивных страниц.',
            'text' => 'JavaScript обрабатывает события пользователя и изменяет страницу без полной перезагрузки. Для начала достаточно уверенно работать с DOM, событиями и сетевыми запросами.',
            'view_count' => 198,
            'created_at' => '2026-08-22 12:10:00',
            'categories' => ['JavaScript'],
        ],
        [
            'name' => 'Практическое знакомство с jQuery',
            'description' => 'Селекторы, события и изменение DOM с помощью jQuery.',
            'text' => 'jQuery предоставляет компактный API для поиска элементов, подписки на события и выполнения AJAX-запросов. В небольшом проекте важно использовать его только там, где он действительно упрощает код.',
            'view_count' => 147,
            'created_at' => '2026-08-26 18:00:00',
            'categories' => ['JavaScript'],
        ],
        [
            'name' => 'AJAX-запросы к PHP',
            'description' => 'Отправляем данные без перезагрузки страницы и обрабатываем JSON.',
            'text' => 'Клиент отправляет HTTP-запрос, PHP проверяет входные данные и возвращает JSON. Интерфейс обрабатывает успешный ответ и ошибки отдельно.',
            'view_count' => 356,
            'created_at' => '2026-08-30 13:40:00',
            'categories' => ['PHP', 'JavaScript'],
        ],
        [
            'name' => 'Docker Compose для PHP-проекта',
            'description' => 'Объединяем PHP-FPM, Nginx и MySQL в одном окружении.',
            'text' => 'Docker Compose описывает сервисы приложения и создаёт общую сеть. Каждый контейнер выполняет одну роль, а исходный код подключается в PHP-контейнер через bind mount.',
            'view_count' => 510,
            'created_at' => '2026-09-03 10:25:00',
            'categories' => ['PHP', 'DevOps'],
        ],
        [
            'name' => 'Настройка Nginx и PHP-FPM',
            'description' => 'Передаём PHP-запросы в FastCGI и настраиваем единую точку входа.',
            'text' => 'Nginx обслуживает статические файлы и передаёт выполнение PHP-скриптов процессу PHP-FPM. Правило try_files направляет неизвестные маршруты в public/index.php.',
            'view_count' => 233,
            'created_at' => '2026-09-07 15:30:00',
            'categories' => ['PHP', 'DevOps'],
        ],
        [
            'name' => 'MySQL в Docker без потери данных',
            'description' => 'Храним базу в volume и передаём конфигурацию через окружение.',
            'text' => 'Docker volume живёт независимо от контейнера MySQL. Благодаря этому пересоздание контейнера не удаляет таблицы, а параметры подключения остаются вне исходного кода.',
            'view_count' => 389,
            'created_at' => '2026-09-11 09:50:00',
            'categories' => ['MySQL', 'DevOps'],
        ],
        [
            'name' => 'Как устроен небольшой PHP-блог',
            'description' => 'Соединяем маршрутизацию, базу данных и шаблоны в одном приложении.',
            'text' => 'Запрос проходит через единую точку входа, маршрутизатор выбирает обработчик, репозиторий получает данные из MySQL, а Smarty формирует итоговую HTML-страницу.',
            'view_count' => 625,
            'created_at' => '2026-09-15 17:45:00',
            'categories' => ['PHP', 'MySQL', 'JavaScript'],
        ],
    ];

    public function run(PDO $pdo): void
    {
        $categoryIds = $this->categoryIds($pdo);
        $upsertArticle = $this->articleStatement($pdo);
        $findArticleId = $pdo->prepare('SELECT id FROM articles WHERE name = :name');
        $deleteCategories = $pdo->prepare(
            'DELETE FROM article_categories WHERE article_id = :article_id'
        );
        $attachCategory = $pdo->prepare(
            'INSERT INTO article_categories (article_id, category_id)
             VALUES (:article_id, :category_id)'
        );

        foreach (self::ARTICLES as $article) {
            $this->upsertArticle($upsertArticle, $article);

            $findArticleId->execute(['name' => $article['name']]);
            $articleId = $findArticleId->fetchColumn();
            if ($articleId === false) {
                throw new RuntimeException("Seeded article {$article['name']} was not found");
            }

            $deleteCategories->execute(['article_id' => $articleId]);

            foreach ($article['categories'] as $categoryName) {
                if (!isset($categoryIds[$categoryName])) {
                    throw new RuntimeException("Seed category {$categoryName} was not found");
                }

                $attachCategory->execute([
                    'article_id' => $articleId,
                    'category_id' => $categoryIds[$categoryName],
                ]);
            }
        }
    }

    /** @return array<string, int> */
    private function categoryIds(PDO $pdo): array
    {
        $categories = $pdo
            ->query('SELECT id, name FROM categories')
            ->fetchAll();

        $ids = [];
        foreach ($categories as $category) {
            $ids[$category['name']] = (int) $category['id'];
        }

        return $ids;
    }

    private function articleStatement(PDO $pdo): \PDOStatement
    {
        return $pdo->prepare(
            'INSERT INTO articles (
                image, name, description, text, view_count, created_at, updated_at
            ) VALUES (
                :image, :name, :description, :text, :view_count, :created_at, :updated_at
            ) ON DUPLICATE KEY UPDATE
                image = :updated_image,
                description = :updated_description,
                text = :updated_text,
                created_at = :updated_created_at,
                updated_at = :updated_updated_at'
        );
    }

    /** @param array<string, mixed> $article */
    private function upsertArticle(\PDOStatement $statement, array $article): void
    {
        $statement->execute([
            'image' => self::IMAGE,
            'name' => $article['name'],
            'description' => $article['description'],
            'text' => $article['text'],
            'view_count' => $article['view_count'],
            'created_at' => $article['created_at'],
            'updated_at' => $article['created_at'],
            'updated_image' => self::IMAGE,
            'updated_description' => $article['description'],
            'updated_text' => $article['text'],
            'updated_created_at' => $article['created_at'],
            'updated_updated_at' => $article['created_at'],
        ]);
    }
}

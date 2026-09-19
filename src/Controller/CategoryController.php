<?php

declare(strict_types=1);

namespace App\Controller;

use App\Http\NotFoundException;
use App\Http\Request;
use App\Http\Response;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\View\SmartyRenderer;

final class CategoryController
{
    private const ARTICLES_PER_PAGE = 6;

    public function __construct(
        private readonly SmartyRenderer $renderer,
        private readonly CategoryRepository $categoryRepository,
        private readonly ArticleRepository $articleRepository
    ) {
    }

    /** @param array<string, string> $parameters */
    public function show(Request $request, array $parameters): Response
    {
        $categoryId = $this->positiveInteger($parameters['id'] ?? null);
        if ($categoryId === null) {
            throw new NotFoundException('Категория не найдена');
        }

        $category = $this->categoryRepository->findById($categoryId);
        if ($category === null) {
            throw new NotFoundException('Категория не найдена');
        }

        $sort = $request->query('sort', ArticleRepository::SORT_BY_DATE);
        if (!is_string($sort) || !in_array($sort, [
            ArticleRepository::SORT_BY_DATE,
            ArticleRepository::SORT_BY_VIEWS,
        ], true)) {
            $sort = ArticleRepository::SORT_BY_DATE;
        }

        $page = $this->positiveInteger($request->query('page')) ?? 1;
        $articlesCount = $this->articleRepository->countByCategory($categoryId);
        $totalPages = max(1, (int) ceil($articlesCount / self::ARTICLES_PER_PAGE));

        if ($page > $totalPages) {
            throw new NotFoundException('Страница категории не найдена');
        }

        $articles = $this->articleRepository->findByCategory(
            $categoryId,
            $sort,
            $page,
            self::ARTICLES_PER_PAGE
        );

        return new Response($this->renderer->render('category.tpl', [
            'pageTitle' => $category['name'],
            'category' => $category,
            'articles' => $articles,
            'sort' => $sort,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $articlesCount,
            ],
        ]));
    }

    private function positiveInteger(mixed $value): ?int
    {
        if (!is_scalar($value)) {
            return null;
        }

        $integer = filter_var($value, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        return $integer === false ? null : $integer;
    }
}

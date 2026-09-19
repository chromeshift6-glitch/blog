<?php

declare(strict_types=1);

namespace App\Controller;

use App\Http\NotFoundException;
use App\Http\Request;
use App\Http\Response;
use App\Repository\ArticleRepository;
use App\View\SmartyRenderer;

final class ArticleController
{
    public function __construct(
        private readonly SmartyRenderer $renderer,
        private readonly ArticleRepository $articleRepository
    ) {
    }

    /** @param array<string, string> $parameters */
    public function show(Request $request, array $parameters): Response
    {
        $articleId = $this->positiveInteger($parameters['id'] ?? null);
        if ($articleId === null) {
            throw new NotFoundException('Статья не найдена');
        }

        $article = $this->articleRepository->findByIdWithCategories($articleId);
        if ($article === null || !$this->articleRepository->incrementViewCount($articleId)) {
            throw new NotFoundException('Статья не найдена');
        }

        ++$article['view_count'];

        return new Response($this->renderer->render('article.tpl', [
            'pageTitle' => $article['name'],
            'article' => $article,
            'similarArticles' => $this->articleRepository->findSimilar($articleId),
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

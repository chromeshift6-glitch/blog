<?php

declare(strict_types=1);

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Repository\CategoryRepository;
use App\View\SmartyRenderer;

final class HomeController
{
    public function __construct(
        private readonly SmartyRenderer $renderer,
        private readonly CategoryRepository $categoryRepository
    ) {
    }

    /** @param array<string, string> $parameters */
    public function index(Request $request, array $parameters): Response
    {
        return new Response($this->renderer->render('home.tpl', [
            'pageTitle' => 'Блог',
            'categories' => $this->categoryRepository->findAllWithLatestArticles(),
        ]));
    }
}

<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Application;
use App\Controller\ArticleController;
use App\Controller\CategoryController;
use App\Controller\HomeController;
use App\Database\Connection;
use App\Http\ErrorHandler;
use App\Http\Request;
use App\Http\Response;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Routing\Router;
use App\View\SmartyFactory;
use App\View\SmartyRenderer;

$projectRoot = dirname(__DIR__);

try {
    $renderer = new SmartyRenderer(
        (new SmartyFactory($projectRoot))->create()
    );
    $pdo = (new Connection())->connect();

    $categoryRepository = new CategoryRepository($pdo);
    $articleRepository = new ArticleRepository($pdo);

    $homeController = new HomeController($renderer, $categoryRepository);
    $categoryController = new CategoryController(
        $renderer,
        $categoryRepository,
        $articleRepository
    );
    $articleController = new ArticleController($renderer, $articleRepository);

    $router = new Router();
    $router->get('/', [$homeController, 'index']);
    $router->get('/category/{id}', [$categoryController, 'show']);
    $router->get('/article/{id}', [$articleController, 'show']);

    (new Application($router, new ErrorHandler($renderer)))
        ->run(Request::fromGlobals());
} catch (Throwable $exception) {
    error_log((string) $exception);

    (new Response(
        'Внутренняя ошибка сервера',
        Response::HTTP_INTERNAL_SERVER_ERROR,
        ['Content-Type' => 'text/plain; charset=UTF-8']
    ))->send();
}

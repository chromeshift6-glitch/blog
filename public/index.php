<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Application;
use App\Controller\HomeController;
use App\Http\ErrorHandler;
use App\Http\Request;
use App\Http\Response;
use App\Routing\Router;
use App\View\SmartyFactory;
use App\View\SmartyRenderer;

$projectRoot = dirname(__DIR__);

try {
    $renderer = new SmartyRenderer(
        (new SmartyFactory($projectRoot))->create()
    );

    $homeController = new HomeController($renderer);

    $router = new Router();
    $router->get('/', [$homeController, 'index']);

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

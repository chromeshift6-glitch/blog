<?php

declare(strict_types=1);

namespace App;

use App\Http\ErrorHandler;
use App\Http\Request;
use App\Routing\Router;
use Throwable;

final class Application
{
    public function __construct(
        private readonly Router $router,
        private readonly ErrorHandler $errorHandler
    ) {
    }

    public function run(Request $request): void
    {
        try {
            $response = $this->router->dispatch($request);
        } catch (Throwable $exception) {
            $response = $this->errorHandler->handle($exception);
        }

        $response->send();
    }
}

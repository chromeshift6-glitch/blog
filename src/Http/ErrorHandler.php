<?php

declare(strict_types=1);

namespace App\Http;

use App\View\SmartyRenderer;
use Throwable;

final class ErrorHandler
{
    public function __construct(private readonly SmartyRenderer $renderer)
    {
    }

    public function handle(Throwable $exception): Response
    {
        if ($exception instanceof HttpException) {
            $statusCode = $exception->statusCode();
            $message = $exception->getMessage();
        } else {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = 'Внутренняя ошибка сервера';
            error_log((string) $exception);
        }

        $template = $statusCode === Response::HTTP_NOT_FOUND
            ? 'errors/404.tpl'
            : 'errors/500.tpl';

        return new Response(
            $this->renderer->render($template, [
                'pageTitle' => "Ошибка {$statusCode}",
                'message' => $message,
            ]),
            $statusCode
        );
    }
}

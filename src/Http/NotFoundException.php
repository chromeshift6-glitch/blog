<?php

declare(strict_types=1);

namespace App\Http;

class NotFoundException extends HttpException
{
    public function __construct(string $message = 'Страница не найдена')
    {
        parent::__construct(Response::HTTP_NOT_FOUND, $message);
    }
}

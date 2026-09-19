<?php

declare(strict_types=1);

namespace App\Routing;

use App\Http\HttpException;
use App\Http\Response;

final class RouteNotFoundException extends HttpException
{
    public function __construct()
    {
        parent::__construct(Response::HTTP_NOT_FOUND, 'Страница не найдена');
    }
}

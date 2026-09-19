<?php

declare(strict_types=1);

namespace App\Routing;

use App\Http\NotFoundException;

final class RouteNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct();
    }
}

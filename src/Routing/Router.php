<?php

declare(strict_types=1);

namespace App\Routing;

use App\Http\Request;
use App\Http\Response;
use InvalidArgumentException;

final class Router
{
    /**
     * @var list<array{
     *     method: string,
     *     pattern: string,
     *     handler: callable(Request, array<string, string>): Response
     * }>
     */
    private array $routes = [];

    /** @param callable(Request, array<string, string>): Response $handler */
    public function get(string $path, callable $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    /** @param callable(Request, array<string, string>): Response $handler */
    public function add(string $method, string $path, callable $handler): void
    {
        if ($path === '' || $path[0] !== '/') {
            throw new InvalidArgumentException('Route path must start with a slash');
        }

        $quotedPath = preg_quote($path, '#');
        $pattern = preg_replace(
            '#\\\\\{([a-zA-Z_][a-zA-Z0-9_]*)\\\\\}#',
            '(?P<$1>[^/]+)',
            $quotedPath
        );

        if ($pattern === null) {
            throw new InvalidArgumentException("Unable to compile route {$path}");
        }

        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => '#^' . $pattern . '/?$#',
            'handler' => $handler,
        ];
    }

    public function dispatch(Request $request): Response
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $request->method()) {
                continue;
            }

            if (preg_match($route['pattern'], $request->path(), $matches) !== 1) {
                continue;
            }

            $parameters = array_filter(
                $matches,
                static fn (string|int $key): bool => is_string($key),
                ARRAY_FILTER_USE_KEY
            );

            return ($route['handler'])($request, $parameters);
        }

        throw new RouteNotFoundException();
    }
}

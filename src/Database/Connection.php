<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use RuntimeException;

final class Connection
{
    public function connect(): PDO
    {
        $host = $this->environment('DB_HOST');
        $port = $this->environment('DB_PORT');
        $dbName = $this->environment('DB_DATABASE');
        $userName = $this->environment('DB_USERNAME');
        $password = $this->environment('DB_PASSWORD');

        $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4";

        return new PDO(
            $dsn,
            $userName,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }

    private function environment(string $name): string
    {
        $value = getenv($name);

        if ($value === false || $value === '') {
            throw new RuntimeException("Environment variable {$name} is not configured");
        }

        return $value;
    }
}

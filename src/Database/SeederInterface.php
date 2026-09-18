<?php

declare(strict_types=1);

namespace App\Database;

use PDO;

interface SeederInterface
{
    public function run(PDO $pdo): void;
}

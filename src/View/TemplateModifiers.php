<?php

declare(strict_types=1);

namespace App\View;

use DateTimeImmutable;

final class TemplateModifiers
{
    public static function formatDate(string $value): string
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $value);

        return $date === false ? $value : $date->format('d.m.Y');
    }

    public static function viewsLabel(int $count): string
    {
        $lastTwoDigits = $count % 100;
        $lastDigit = $count % 10;

        if ($lastTwoDigits >= 11 && $lastTwoDigits <= 14) {
            $word = 'просмотров';
        } elseif ($lastDigit === 1) {
            $word = 'просмотр';
        } elseif ($lastDigit >= 2 && $lastDigit <= 4) {
            $word = 'просмотра';
        } else {
            $word = 'просмотров';
        }

        return "{$count} {$word}";
    }
}
